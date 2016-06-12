<?php

namespace Linku\Linku\Abilities;

use Linku\Models\Folder;
use Linku\Models\Link;
use Linku\Models\Share;
use Linku\Models\User;

class LinkAbilities extends Abilities
{

    public function canCreateLink(User $user, Folder $folder)
    {

        if($user->id == $folder->user_id) {
            return true;
        }

        $share = $this->getFolderShare($user, $folder);

        if($share && $share->permissions > 4) {
            return true;
        }

        return false;
    }

    public function canUpdateLink(User $user, Link $link)
    {
        if($user->id == $link->user_id) {
            return true;
        }


        $folder = $link->folder;

        $share = $folder ? $this->getFolderShare($user, $folder) : false;

        if($share && $share->permissions > 4) {
            return true;
        }

        return false;
    }

    public function canMoveLink(User $user, Link $link, Folder $destinationFolder)
    {
        /** @var Folder $linkFolder */
        $linkFolder = $link->folder;
        $linkFolderIsShared = $linkFolder->isShared();

        $destinationIsShared = $destinationFolder->isShared();

        if($linkFolderIsShared || $destinationIsShared) {
            $linkFolderShare = $this->getFolderShare($user, $linkFolder);

            if($linkFolderIsShared && (! $linkFolderShare || $linkFolderShare->permissions < 4)) {
                return false;
            }

            $destinationTopSharedFolder = $this->getTopSharedFolder($destinationFolder, $user);
            $linkTopSharedFolder = $this->getTopSharedFolder($linkFolder, $user);

            if(!$destinationTopSharedFolder || $destinationTopSharedFolder->id != $linkTopSharedFolder->id) {
                return false;
            }

            return true;
        }

        if(
            $linkFolder->isOwnedBy($user) &&
            $destinationFolder->isOwnedBy($user) &&
            $link->user_id == $user->id
        )
        {
            return true;
        }

        return false;
    }

    public function canDeleteLink(User $user, Link $link)
    {
        if($user->id == $link->user_id) {
            return true;
        }

        $folder = $link->folder;
        $share = $folder ? $this->getFolderShare($user, $folder) : false;

        if($share && $share->permissions >= 5) {
            return true;
        }

        return false;
    }

}