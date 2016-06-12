<?php

namespace Linku\Linku\Folders;

use Auth;
use Illuminate\Support\Collection;
use Linku\Models\Folder;
use Linku\Models\Share;
use Linku\Models\User;

class Hierarchy
{

    /**
     * @param User|null $user
     * @return mixed
     */
    public static function getUserFoldersTree(User $user = null)
    {
        $user = $user ?: Auth::user();

        return $user->folders()->get()->toTree();
    }

    public static function getUserFoldersWithLinks(User $user = null)
    {
        $user = $user ?: Auth::user();

        $userRootFolder = $user->rootFolder();


        $folders = $userRootFolder
            ->descendants()
            ->get();

        $folders->push($userRootFolder);

        $shares = Share::where('user_id', $user->id)->get();
        $ids = $shares->pluck('shareable_id');

        $shareFolders = Folder::whereIn('id', $ids->toArray())->get();

        if($shareFolders) {
            $shareFolders->load('descendants');

            foreach ($shareFolders as $folder) {
                $share = $shares->where('shareable_id', $folder->id)->first();
                $parentId = $share->folder_parent_id;

                $folder->setParentId($parentId);

                $folders->push($folder);

                foreach ($folder->descendants as $cFolder) {
                    $cFolder->share = true;
                    $folders->push($cFolder);
                }
            }
        }

        $folders->load(['links', 'shares' => function($query) {
            $query->with('byUser', 'user');
        }]);

        return $folders;
    }

    public static function getFolderChildren(Folder $folder)
    {

    }

    public static function createSelectOptions(Collection $folders, $selected = null)
    {
        $return = "";

        $traverse = function ($folders, $prefix = '-') use (&$traverse, &$return, $selected) {
            foreach ($folders as $folder) {
                $folderName = $folder->name;
                $selectedAttr = $selected == $folder->id ? ' selected' : '';
                $folderName = "{$prefix} $folderName";

                $return .= "<option value='{$folder->id}'$selectedAttr>{$folderName}</option>";
                $traverse($folder->children, $prefix . '-');
            }
        };

        $traverse($folders);

        return $return;
    }

}