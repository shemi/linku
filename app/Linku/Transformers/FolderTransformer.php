<?php

namespace Linku\Linku\Transformers;

use Illuminate\Http\Request;
use Linku\Models\Folder;
use Linku\Models\User;

class FolderTransformer extends Transformer
{

    protected $user;

    /**
     * @param Folder $folder
     * @return array
     */
    public function transform($folder)
    {
        $linkTransformer = new LinkTransformer();
        $shareTransformer = new ShareTransformer();
        $share = $this->getShare($folder);

        return [
            'id'        => $folder->id,
            'userId'    => $folder->user_id,
            'name'      => $folder->name,
            'parentId'  => $folder->parent_id,
            'createdAt' => $this->formatDate($folder->created_at),
            'updatedAt' => $this->formatDate($folder->apdated_at),
            'share'     => $share,
            'shares'    => $shareTransformer->transformCollection($folder->shares),
            'links'     => $linkTransformer->transformCollection($folder->links),
        ];
    }

    public function getShare(Folder $folder)
    {
        $shareTransformer = new ShareTransformer();

        if(! $folder->shares->count()) {
            return false;
        }

        if($folder->user_id == $this->user->id) {
            return ['shareBy' => 'me'];
        }

        foreach($folder->shares as $share) {
            if($share->user_id == $this->user->id) {
                return $shareTransformer->transform($share);
            }
        }

        return false;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function transformRequest(Request $request)
    {
        // TODO: Implement transformRequest() method.
    }
}