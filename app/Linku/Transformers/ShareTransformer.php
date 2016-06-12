<?php

namespace Linku\Linku\Transformers;

use Illuminate\Http\Request;
use Linku\Models\Share;

class ShareTransformer extends Transformer
{

    /**
     * @param Share $share
     * @return array
     */
    public function transform($share)
    {
        $userTransformer = new UserTransformer();

        return [
            'user'        => $userTransformer->transform($share->user),
            'shareBy'     => $userTransformer->transform($share->byUser),
            'permissions' => $share->permissions,
            'createdAt'   => $this->formatDate($share->created_at),
        ];
    }

    public function transformRequest(Request $request)
    {
        // TODO: Implement transformRequest() method.
    }
}