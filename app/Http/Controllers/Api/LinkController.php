<?php

namespace Linku\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;

use Linku\Http\Requests;
use Linku\Linku\Folders\Hierarchy;
use Linku\Linku\Transformers\LinkTransformer;
use Linku\Models\Folder;
use Linku\Models\Link;
use Linku\Models\User;

class LinkController extends ApiController
{
    /**
     * @var User|null
     */

    private $user;

    private $transformet;

    /**
     * Create a new controller instance.
     *
     * @param LinkTransformer $transformer
     */
    public function __construct(LinkTransformer $transformer)
    {
        $this->user = Auth::guard('api')->user();
        $this->transformet = $transformer;

        parent::__construct();
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
            'url'    => 'required|url|max:255',
            'icon'   => 'url',
            'folder' => 'numeric',
        ]);

        $user = $this->user;
        $folderId = $request->input('folder', function () use ($user) {
            return $user->rootFolder()->id;
        });

        $folder = Folder::findOrFail($folderId);

        if ($user->cannot('create-link', compact('folder'))) {
            return $this->setStatusCode(403)->respondWithError('You cannot save link in this folder');
        }

        $link = Link::create([
            'name'      => $request->input('name'),
            'url'       => $request->input('url'),
            'icon'      => $request->input('icon', null),
            'folder_id' => $folder->id,
            'user_id'   => $user->id
        ]);

        return $this->respond($this->transformet->transform($link));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Link $link
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Link $link)
    {

        if ($this->user->cannot('update-link', compact('link'))) {
            return $this->setStatusCode(403)
                        ->respondWithError('You can\'t update this bookmark...');
        };

        $this->validate($request, [
            'name' => 'required|max:255',
            'url'  => 'required|url|max:255',
        ]);

        $link->update($request->only(['name', 'url']));

        $linkFolder = $link->folder;

        if ($request->has('folder') && $request->input('folder') !== $linkFolder->id) {
            $destinationFolder = Folder::findOrFail($request->input('folder'));

            if($this->user->cannot('move-link', compact('link', 'destinationFolder'))) {
                return $this->setStatusCode(403)
                    ->respondWithError('You can\'t move this this bookmark...');
            }

            $link->folder_id = $destinationFolder->id;
            $link->save();

        }


        return $this->respond($link);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Link $link
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @internal param int $id
     */
    public function destroy(Link $link)
    {
        if ($this->user->cannot('delete-link', compact('link'))) {
            return $this->setStatusCode(403)
                ->respondWithError('You can\'t delete this this bookmark...');
        }

        $link->delete();

        return $this->respond(true);
    }
}
