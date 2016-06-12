<?php

namespace Linku\Http\Controllers\Auth;

use Linku\Models\Folder;
use Linku\Models\Invite;
use Linku\Models\User;
use Validator;
use Linku\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $redirectPath = '/';

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @param null $invite
     * @return \Illuminate\Http\Response
     */
    public function getRegister($invite = null)
    {
        if($invite) {
            $invite = Invite::where('invite_token', $invite)->first();
        }

        return $this->showRegistrationForm($invite);
    }

    /**
     * Show the application registration form.
     *
     * @param null $invite
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm($invite = null)
    {
        if(is_string($invite)) {
            $invite = Invite::where('invite_token', $invite)->first();
        }

        return view('auth.register')->with(compact('invite'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->setApiToken();
        $user->save();

        $folder = new Folder(['name' => 'Root']);
        $user->folders()->save($folder);

        if($data['invite_token']) {
            $invite = Invite::where('invite_token', $data['invite_token'])->first();

            if($invite) {
                $this->parseInvitation($invite, $user, $folder);
            }
        }

        return $user;
    }

    protected function parseInvitation(Invite $invite, User $user, Folder $rootFolder)
    {
        $shareables = $invite->shareable;
        $byUser = $invite->byUser;

        foreach($shareables as $shareable) {
            $sharedObject = $shareable['shareable_type'];
            $sharedObject = $sharedObject::find($shareable['shareable_id']);

            if($sharedObject) {
                $sharedObject->shares()->create([
                    'user_id' => $user->id,
                    'by_user_id' => $byUser->id,
                    'permissions' => 7,
                    'folder_parent_id' => $rootFolder->id
                ]);
            }

        }

    }

}
