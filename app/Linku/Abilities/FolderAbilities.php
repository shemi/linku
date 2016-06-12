<?php

namespace Linku\Linku\Abilities;

use Linku\Models\Folder;
use Linku\Models\Share;
use Linku\Models\User;
use Log;

class FolderAbilities extends Abilities
{

    public function canCreateFolder(User $user, Folder $folder)
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

    public function canUpdateFolder(User $user, Folder $folder)
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

    public function canMoveFolder(User $user, Folder $folder, Folder $destinationFolder)
    {
        $topSharedFolder = $this->getTopSharedFolder($folder, $user);

        if(
            $folder->isOwnedBy($user) &&
            $destinationFolder->isOwnedBy($user) &&
            ! $folder->isSharedWith($user)
        )
        {
            Log::info('is Owned the folder and the destination and the folder is not share with');
            return true;
        }

        if(
            $folder->isOwnedBy($user) &&
            $destinationFolder->isOwnedBy($user) &&
            ! $folder->hasChildrenNotOwnedBy($user)
        )
        {
            Log::info('is Owned the folder and the destination and the folder have jst his folders');
            return true;
        }

        if
        (
            $folder->isOwnedBy($user) &&
            $destinationFolder->getTopShareWith($user) &&
            ! $folder->hasChildrenNotOwnedBy($user)
        )
        {
            Log::info('is Owned the folder and the destination shared with him ang not have forent children');
            return true;
        }

        $share = $this->getFolderShare($user, $folder);

        $destinationShare = $this->getFolderShare($user, $destinationFolder);

        if(! ($share && $share->permissions > 4)) {
            Log::info('don`t have permissions to edit the folder');
            return false;
        }


        if($topSharedFolder && ! $destinationFolder->isDescendantOf($topSharedFolder) && $topSharedFolder->id !== $destinationFolder->id) {
            Log::info('the destination folder is not Descendant of the top shared parent');
            return false;
        }

        if($topSharedFolder->id === $destinationFolder->id) {
            Log::info('the destination folder is the top shared parent');
            return true;
        }

        if($destinationShare->permissions > 4) {
            return ($share && $share->permissions > 4);
        }

        Log::info('none of the above wse hit');

        return false;
    }

    public function canDeleteFolder(User $user, Folder $folder)
    {
        logger("the user: {$user->id} the folder: {$folder->id} the folder user id: {$folder->user_id}");

        return $folder->isOwnedBy($user);
    }

    public function canDeleteSharedFolder(User $user, Folder $folder)
    {
        $share = $this->getFolderShare($user, $folder, false);

        return ($share && $share->permissions == 7);
    }

}