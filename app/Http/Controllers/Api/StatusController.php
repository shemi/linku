<?php

namespace Linku\Http\Controllers\Api;


use Auth;

class StatusController extends ApiController
{

    public function index()
    {
        $user = Auth::guard('api')->user();

        return $this->respond([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'id' => $user->id
            ],
            'massages' => [],
        ]);
    }

}