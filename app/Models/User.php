<?php

namespace Linku\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Linku\Models\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Kalnoy\Nestedset\Collection|\Linku\Models\Folder[] $folders
 * @property-read \Illuminate\Database\Eloquent\Collection|\Linku\Models\Link[] $links
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Linku\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function rootFolder()
    {
        return $this->folders()->whereNull('parent_id')->first();
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'by_user_id', 'id');
    }

    public function shared()
    {
        return $this->hasMany(Share::class, 'user_id', 'id');
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function sharedThis($model)
    {
        return (bool) $this->shares()
                            ->where('shareable_type', get_class($model))
                            ->where('shareable_id', $model->id)
                            ->count();
    }

    public function setApiToken($save = false)
    {
        $this->api_token = static::makeApiToken();

        while($this->apiTokenExist($this->api_token)) {
            $this->api_token = static::makeApiToken();
        }

        if($save) {
            $this->save();
        }

        return $this;
    }

    public static function makeApiToken()
    {
        return uniqid(str_random(60));
    }

    public function refreshApiToken()
    {
        return $this->setApiToken(true);
    }

    protected function apiTokenExist($token)
    {
        return (bool) static::where('api_token', $token)->count();
    }

}
