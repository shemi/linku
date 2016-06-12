<?php

namespace Linku\Linku\Abilities;

use Linku\Models\Folder;
use Linku\Models\Share;
use Linku\Models\User;

class ShareAbilities extends Abilities
{

    public function canShareFolder(User $user, Folder $folder, $email)
    {

        if($user->email == $email || $folder->parent_id == null) {
            return false;
        }

        if($user->id == $folder->user_id) {
            return true;
        }

        $share = $this->getFolderShare($user, $folder);

        if($share && $share->permissions > 5) {
            return true;
        }

        return false;
    }

}