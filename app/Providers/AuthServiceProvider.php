<?php

namespace Linku\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Linku\Model' => 'Linku\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('share-folder', '\Linku\Linku\Abilities\ShareAbilities@canShareFolder');

        $gate->define('create-link', '\Linku\Linku\Abilities\LinkAbilities@canCreateLink');
        $gate->define('update-link', '\Linku\Linku\Abilities\LinkAbilities@canUpdateLink');
        $gate->define('move-link', '\Linku\Linku\Abilities\LinkAbilities@canMoveLink');
        $gate->define('delete-link', '\Linku\Linku\Abilities\LinkAbilities@canDeleteLink');

        $gate->define('create-folder', '\Linku\Linku\Abilities\FolderAbilities@canCreateFolder');
        $gate->define('update-folder', '\Linku\Linku\Abilities\FolderAbilities@canUpdateFolder');
        $gate->define('move-folder', '\Linku\Linku\Abilities\FolderAbilities@canMoveFolder');
        $gate->define('delete-folder', '\Linku\Linku\Abilities\FolderAbilities@canDeleteFolder');
        $gate->define('delete-shared-folder', '\Linku\Linku\Abilities\FolderAbilities@canDeleteSharedFolder');
    }
}
