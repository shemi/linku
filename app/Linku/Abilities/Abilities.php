<?php

namespace Linku\Linku\Abilities;

use Linku\Models\Folder;
use Linku\Models\Share;
use Linku\Models\User;
use Log;

abstract class Abilities
{

    /**
     * @param User $user
     * @param Folder $folder
     * @param bool $withBy
     * @return Share
     */
    protected function getFolderShare(User $user, Folder $folder, $withBy = true)
    {

        if($withBy && $user->sharedThis($folder)) {
            return $this->createOwnerShare($user);
        }

        if($share = $this->getUserShare($folder->shares, $user)) {
            return $share;
        }

        $parents = $folder->getAncestors();
        $parents = $parents->reject(function($value, $key) {
            return $value->parent_id == null;
        });

        if($parents->count() == 0) {
            return false;
        }

        $parents->load('shares');

        foreach($parents as $folder) {

            if($withBy && $folder->user_id == $user->id) {
                return $this->createOwnerShare($user);
            }

            if($share = $this->getUserShare($folder->shares, $user)) {
                return $share;
            }
        }

        return false;
    }

    /**
     * @param Folder $folder
     * @param User $user
     * @return bool|Folder
     */
    protected function getTopSharedFolder(Folder $folder, User $user)
    {

        if($this->getUserShare($folder->shares, $user) || $folder->getTopSharedBy($user)) {
            return $folder;
        }

        $parents = $folder->getAncestors();
        $parents = $parents->reject(function($value, $key) {
            return $value->parent_id == null;
        });

        if($parents->count() == 0) {
            return false;
        }

        $parents->load('shares');

        /** @var Folder $folder */
        foreach($parents as $folder) {

            if($folder->getTopSharedBy($user)) {
                return $folder;
            }

            if($this->getUserShare($folder->shares, $user)) {
                return $folder;
            }

        }

        return false;
    }

    protected function getUserShare($shares, User $user)
    {
        /** @var Share $share */
        foreach($shares as $share) {
            if($share->user_id == $user->id) {
                return $share;
            }
        }

        return false;
    }

    protected function createOwnerShare(User $user)
    {
        return new Share([
            'permissions' => 7,
            'user_id' => $user->id,
            'by_user_id' => $user->id
        ]);
    }

}