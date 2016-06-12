<?php

namespace Linku\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Linku\Http\Requests;
use Linku\Linku\Folders\FolderRepo;
use Linku\Linku\Folders\Hierarchy as FoldersHierarchy;
use Linku\Models\Folder;

class FolderController extends Controller
{

    /**
     * @var FoldersHierarchy
     */
    private $hierarchy;

    private $folderRepo;

    public function __construct(FoldersHierarchy $hierarchy, FolderRepo $folderRepo)
    {
        $this->middleware('auth');

        $this->hierarchy = $hierarchy;
        $this->folderRepo = $folderRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $folders = FoldersHierarchy::getUserFoldersTree();
        return view('folders.create', ['folders' => $folders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'name'   => 'required|max:255',
            'parent' => 'numeric',
        ]);

        /** @var Folder $folder */
        $folder = $user->folders()->create(['name' => $request->input('name')]);

        if($request->has('parent')) {
            $parent = $user->folders()->where('id', $request->input('parent'))->firstOrFail();
            $folder->parent_id = $parent->id;
            $folder->save();
        }

        return redirect()->back()->with('massage', 'Folder created');
    }

    /**
     * Display the specified resource.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Folder $folder)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Folder $folder)
    {
        $folders = FoldersHierarchy::getUserFoldersTree();

        return view('folders.edit', ['folders' => $folders, 'folder' => $folder]);
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
        $user = Auth::user();

        if($folder->user_id !== $user->id) {
            return redirect()->route('home');
        };

        $this->validate($request, [
            'name'   => 'required|max:255',
            'parent' => 'numeric',
        ]);

        $folder->name = $request->input('name');

        if($request->has('parent') && $request->input('parent') != $folder->parent_id) {
            $parent = $user->folders()->where('id', $request->input('parent'))->firstOrFail();
            $folder->parent_id = $parent->id;
        } elseif(! $request->has('parent')) {
            $folder->parent_id = null;
        }

        $folder->save();

        return redirect()->route('home')->with('massage', 'Folder edited');
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
        $user = Auth::user();

        if($folder->user_id !== $user->id) {
            return redirect()->route('home');
        };

        $this->folderRepo->deleteAll($folder);

        return redirect()->route('home')->with('massage', 'Folder deleted');
    }
}
