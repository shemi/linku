<?php

namespace Linku\Http\Controllers;

use Auth;
use Linku\Http\Requests;
use Illuminate\Http\Request;
use Linku\Linku\Folders\Hierarchy;
use Linku\Linku\Transformers\FolderTransformer;
use Linku\Models\Folder;
use Linku\Models\Share;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FolderTransformer $folderTransformer)
    {
        $user = Auth::user();

        $folders = Hierarchy::getUserFoldersWithLinks();

        return $folders;
    }
}
