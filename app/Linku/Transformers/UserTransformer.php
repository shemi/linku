<?php

namespace Linku\Linku\Transformers;

use Illuminate\Http\Request;
use Linku\Models\User;

class UserTransformer extends Transformer
{

    /**
     * @param User $user
     * @return array
     */
    public function transform($user)
    {
        $hashEmail = md5($user->email);
        $avatar = "https://www.gravatar.com/avatar/{$hashEmail}?s=80&d=mm";

        return [
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'avatar' => $avatar,
        ];
    }

    public function transformRequest(Request $request)
    {
        // TODO: Implement transformRequest() method.
    }
}