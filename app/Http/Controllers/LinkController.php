<?php

namespace Linku\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Linku\Http\Requests;
use Linku\Linku\Folders\Hierarchy;
use Linku\Models\Link;
use Linku\Models\User;

class LinkController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        $folders = Hierarchy::getUserFoldersTree();

        return view('link.create', ['folders' => $folders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $this->validate($request, [
            'name'   => 'required|max:255',
            'url'    => 'required|max:255',
            'folder' => 'numeric',
        ]);

        $link = new Link([
            'name'    => $request->input('name'),
            'url'     => $request->input('url'),
        ]);

        $link->user_id = $user->id;

        if ($request->has('folder')) {
            $folder = $user->folders()->where('id', $request->input('folder'))->firstOrFail();
            $link->folder_id = $folder->id;
        }

        $link->save();

        return redirect()->back()->with('massage', 'Link created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Link $link)
    {
        $user = Auth::user();

        if($link->user_id !== $user->id) {
            return redirect()->back()->with('message', ['error', 'Link not found']);
        }

        $folders = Hierarchy::getUserFoldersTree();

        return view('link.edit', ['link' => $link, 'folders' => $folders]);
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
        /** @var User $user */
        $user = $request->user();

        if($link->user_id !== $user->id) {
            return redirect()->route('home');
        };

        $this->validate($request, [
            'name'   => 'required|max:255',
            'url'    => 'required|max:255',
        ]);

        $link->name = $request->input('name');
        $link->url = $request->input('url');

        if ($request->has('folder')) {
            $folder = $user->folders()->where('id', $request->input('folder'))->firstOrFail();
            $link->folder_id = $folder->id;
        } else {
            $link->folder_id = null;
        }

        $link->save();

        return redirect()->route('home')->with('massage', 'Link updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link)
    {
        if($link->user_id !== Auth::user()->id) {
            return redirect()->back()->with('message', ['error', 'Link not found']);
        }

        $link->delete();

        return redirect()->back()->with('message', 'link deleted');
    }
}
