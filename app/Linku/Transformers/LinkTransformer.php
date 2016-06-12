<?php

namespace Linku\Linku\Transformers;

use Illuminate\Http\Request;
use Linku\Models\Link;

class LinkTransformer extends Transformer
{

    /**
     * @param Link $link
     * @return array
     */
    public function transform($link)
    {
        return [
            'id'     => $link->id,
            'name'   => $link->name,
            'url'    => $link->url,
            'icon'   => $link->icon,
            'folder' => $link->folder_id,
        ];
    }

    public function transformRequest(Request $request)
    {
        // TODO: Implement transformRequest() method.
    }
}