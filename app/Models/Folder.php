<?php

namespace Linku\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Folder extends Model
{
    use NodeTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'updated_at', 'created_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function share(User $user)
    {
        return Share::where('user_id', $user->id)
                    ->where('folder_id', $this->id);
    }

    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    public function isShared()
    {
        return (boolean) $this->shares()->count();
    }

    public function isOwnedBy(User $user)
    {
        return $this->user_id == $user->id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isSharedBy(User $user)
    {
        $folders = $this->ancestors()->pluck('id');
        $folders->push($this->id);

        return (bool) Share::where('shareable_type', get_class($this))
            ->where('by_user_id', $user->id)
            ->whereIn('shareable_id', $folders)
            ->count();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isSharedWith(User $user)
    {
        $folders = $this->ancestors()->pluck('id');
        $folders->push($this->id);

        return (bool) Share::where('shareable_type', get_class($this))
                        ->where('user_id', $user->id)
                        ->whereIn('shareable_id', $folders)
                        ->count();
    }

    /**
     * @param User $user
     * @return Share|null
     */
    public function getTopShareWith(User $user)
    {
        return Share::where('shareable_type', get_class($this))
            ->where('shareable_id', $this->id)
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * @param User $user
     * @return Share|null|static
     */
    public function getTopSharedBy(User $user)
    {
        return Share::where('shareable_type', get_class($this))
            ->where('shareable_id', $this->id)
            ->where('by_user_id', $user->id)
            ->first();
    }

    public function hasChildrenNotOwnedBy(User $user)
    {
        return (boolean) $this->children()
            ->where('user_id', '!=', $user->id)
            ->count();
    }

}
