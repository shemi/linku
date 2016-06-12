<?php

namespace Linku\Http\Controllers\Api\Auth;

use Auth;
use Illuminate\Http\Request;
use Linku\Http\Controllers\Api\ApiController;
use Linku\Models\Folder;
use Linku\Models\User;
use Validator;
use Linku\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends ApiController
{

    use ThrottlesLogins;


    public function __construct()
    {
//        $this->middleware('guest:api', ['except' => 'logout']);

        parent::__construct();
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);


        if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->setStatusCode(403)
                        ->respondWithError('Too many login attempts! try again in one hour.');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::validate($credentials)) {
            $this->clearLoginAttempts($request);

            $user = Auth::getLastAttempted();

            return $this->respond([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->api_token
            ]);
        }

        if (!$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->respondNotAuthorized('Invalid email or password combination, please re-enter.');
    }

    public function postRegister(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'name'     => 'required',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->password = bcrypt($request->input('password'));
        $user->email = $request->input('email');
        $user->api_token = $user->makeApiToken();


        if(! $user->save()) {
            return $this->setStatusCode(500)
                ->respondWithError('cant save user');
        }

        $rootFolder = Folder::create([
            'name' => 'Root',
            'user_id' => $user->id
        ]);

        return $this->respond([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->api_token
        ]);

    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return 'email';
    }

}
