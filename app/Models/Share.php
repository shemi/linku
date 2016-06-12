<?php

namespace Linku\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Linku\Models\Share
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $by_user_id
 * @property integer $shareable_id
 * @property integer $folder_parent_id
 * @property string $shareable_type
 * @property integer $permissions
 * @property User $user
 * @property User $byUser
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\Share whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\Share whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\Share whereShareableId($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\Share whereShareableType($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\Share wherePermissions($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\Share whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\Share whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Share extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'by_user_id',
        'permissions',
        'folder_parent_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['updated_at', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function byUser()
    {
        return $this->belongsTo(User::class, 'by_user_id');
    }

    public function shareable()
    {
        return $this->morphTo();
    }

}
