<?php

namespace Linku\Linku\Folders;

use Illuminate\Database\Eloquent\Collection;
use Linku\Models\Folder;
use Linku\Models\Link;
use Linku\Models\Share;

class FolderRepo
{

    public function create()
    {

    }

    private function deleteFoldersLinks($folders = [])
    {
        Link::whereIn('folder_id', $folders)->delete();
    }

    private function deleteFoldersShares($folders = [])
    {
        Share::where('shareable_type', Folder::class)
            ->whereIn('shareable_id', $folders)
            ->delete();
    }

    public function deleteAll(Folder $folder, $withShares = false)
    {
        /** @var Collection $folders */
        $folders = $folder->descendants()->get()->toFlatTree();
        $folders->push($folder);
        $ids = $folders->pluck('id');

        $this->deleteFoldersLinks($ids->all());

        if($withShares) {
            $this->deleteFoldersShares($ids);
        }

        foreach($folders as $folder) {
            $folder->delete();
        }

        return true;
    }

    public function deleteAllWithShares(Folder $folder)
    {
        return $this->deleteAll($folder, true);
    }

}