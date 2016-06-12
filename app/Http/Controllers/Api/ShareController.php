<?php

namespace Linku\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;

use Linku\Events\OnInviteCreated;
use Linku\Linku\Transformers\ShareTransformer;
use Linku\Models\Folder;
use Linku\Models\Invite;
use Linku\Models\Link;
use Linku\Models\Share;
use Linku\Models\User;

class ShareController extends ApiController
{

    /**
     * @var User
     */
    protected $user;

    protected $transformer;

    public function __construct(ShareTransformer $transformer)
    {
        $this->user = Auth::guard('api')->user();
        $this->transformer = $transformer;

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
        $user = $this->user;
        $folder = Folder::findOrFail($request->input('folderId'));
        $email = $request->input('email');

        if ($user->cannot('share-folder', compact('folder', 'email'))) {
            return $this->setStatusCode(403)
                ->respondWithError('You can\'t share this folder..');
        }

        $share = $this->shareThis($folder, $email, $user);

        return $share ? $this->respond($this->transformer->transform($share)) : $this->respond('invite');
    }

    /**
     * @param Folder|Link $shareable
     * @param String $with
     * @param User $by
     *
     * @return Bool|Share
     */
    protected function shareThis($shareable, $with, User $by)
    {
        $withUser = User::where('email', $with)->first();

        if ($withUser) {
            $parentFolder = $withUser->rootFolder();

            return $shareable->shares()->create([
                'user_id'          => $withUser->id,
                'by_user_id'       => $by->id,
                'permissions'      => 7,
                'folder_parent_id' => $parentFolder->id,
            ]);
        }

        return false;
    }

    public function inviteAndShare(Request $request)
    {
        logger('share work');

        $user = $this->user;
        $folder = Folder::findOrFail($request->input('folderId'));
        $email = $request->input('email');

        if ($user->cannot('share-folder', compact('folder', 'email'))) {
            return $this->setStatusCode(403)
                ->respondWithError('You can\'t share this folder..');
        }

        $withUserExist = User::where('email', $email)->first();

        if ($withUserExist) {
            $share = $this->shareThis($folder, $email, $user);

            return $this->respond($this->transformer->transform($share));
        }

        $invite = Invite::where('email', $email)->first() ?:
            Invite::create([
                'invite_by'    => $user->id,
                'email'        => $email,
                'shareable'    => [],
                'invite_token' => str_random(78),
                'send_times'   => 0,
            ]);

        $shares = collect($invite->shareable);
        $shares->push(['shareable_type' => get_class($folder), 'shareable_id' => $folder->id]);

        $shares = $shares->unique(function ($item) {
            return $item['shareable_type'].$item['shareable_id'];
        });

        $invite->shareable = $shares->values()->toArray();

        $invite->save();

        event(new OnInviteCreated($invite));

        return $this->respond(true);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
