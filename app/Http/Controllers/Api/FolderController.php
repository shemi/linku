<?php

namespace Linku\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;

use Log;
use Linku\Http\Requests;
use Linku\Linku\Folders\FolderRepo;
use Linku\Linku\Folders\Hierarchy as FoldersHierarchy;
use Linku\Linku\Transformers\FolderTransformer;
use Linku\Models\Folder;
use Linku\Models\Share;
use Linku\Models\User;

class FolderController extends ApiController
{

    /**
     * @var FoldersHierarchy
     */
    private $hierarchy;

    private $folderRepo;

    /**
    * @var User
    */
    private $user;

    private $transformer;

    /**
     * FolderController constructor.
     * @param FoldersHierarchy $hierarchy
     * @param FolderRepo $folderRepo
     * @param FolderTransformer $transformer
     */
    public function __construct(FoldersHierarchy $hierarchy, FolderRepo $folderRepo, FolderTransformer $transformer)
    {
        $this->user = Auth::guard('api')->user();

        $this->hierarchy = $hierarchy;
        $this->folderRepo = $folderRepo;
        $this->transformer = $transformer;

        $this->transformer->setUser($this->user);

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folders = FoldersHierarchy::getUserFoldersWithLinks($this->user);

        $transformedFolders = $this->transformer
            ->setUser($this->user)
            ->transformCollection($folders);

        return $this->respond($transformedFolders);
    }

    public function show($id)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'   => 'required|max:255',
            'parent' => 'numeric',
        ]);

        $user = $this->user;

        $parentId = $request->input('parent', function() use ($user) {
            return $user->rootFolder()->id;
        });

        /** @var Folder $parentFolder */
        $parentFolder = Folder::findOrFail($parentId);

        if($user->cannot('create-folder', compact('parentFolder'))) {
            return $this->setStatusCode(403)->respondWithError('You can\'t create folder here');
        }

        /** @var Folder $folder */
        $folder = $parentFolder->children()->create([
            'name' => $request->input('name', 'new folder'),
            'user_id' => $user->id
        ]);

        return $this->respond($this->transformer->transform($folder));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Folder $folder)
    {

        if($this->user->cannot('update-folder', compact('folder'))) {
            return $this->setStatusCode(403)->respondWithError('You can\'t update this folder.');
        };

        $this->validate($request, [
            'name'   => 'required|max:255',
            'parentId' => 'numeric',
        ]);

        $folder->name = $request->input('name');

        $parentId = $request->input('parentId', false);

        if($parentId && $parentId != $folder->parent_id) {

            /** @var Folder $destinationFolder */
            $destinationFolder = Folder::where('id', $parentId)->firstOrFail();

            if
            (
                $destinationFolder->isOwnedBy($this->user) &&
                ! $destinationFolder->isSharedWith($this->user) &&
                $share = $folder->getTopShareWith($this->user)
            )
            {
                Log::info('is Owned the destination and is not shared and and the folder is the top share ');

                return $this->setShareFolderParentId($share, $folder, $destinationFolder);
            }

            if
            (
                $folder->isOwnedBy($this->user) &&
                $destinationFolder->isOwnedBy($this->user) &&
                $folder->hasChildrenNotOwnedBy($this->user)
            )
            {
                Log::info('is Owned the folder and the destination and the folder don`t have jst his folders');
                return $this->setStatusCode(403)->respondWithError('You can\'t move this folder.');
            }

            if($this->user->cannot('move-folder', compact('folder', 'destinationFolder'))) {
                return $this->setStatusCode(403)->respondWithError('You can\'t move this folder.');
            }

            $folder->parent_id = $destinationFolder->id;

        }

        $folder->save();

        return $this->respond($this->transformer->transform($folder));
    }

    public function setShareFolderParentId(Share $share, Folder $folder, Folder $parent)
    {
        $share->folder_parent_id = $parent->id;
        $share->save();

        $folder->parent_id = $parent->id;

        return $this->respond($this->transformer->transform($folder));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Folder $folder)
    {

        if($share = $folder->getTopShareWith($this->user)) {
            logger('delete top shared folder');
            $share->delete();

            return $this->respond(true);
        }

        if($folder->isSharedBy($this->user)) {
            logger('deleting his shared folder');

            $this->folderRepo->deleteAllWithShares($folder);

            return $this->respond(true);
        }

        if($folder->isSharedWith($this->user)) {
            logger('check if can delete folder that shared with him');

            if($this->user->can('delete-shared-folder', compact('folder'))) {
                logger('the user deleted folder that shared with him');

                $this->folderRepo->deleteAllWithShares($folder);

                return $this->respond(true);
            }

        }

        if($this->user->cannot('delete-folder', compact('folder'))) {

            return $this->setStatusCode(403)
                        ->respondWithError('You can\'t delete this folder...');
        }

        logger('deleting regular folder');

        $this->folderRepo->deleteAll($folder);

        return $this->respond(true);
    }
}
