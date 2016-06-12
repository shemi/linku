<?php

namespace Linku\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Invite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invite_by',
        'email',
        'invite_token',
        'send_times',
        'shareable',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['updated_at', 'created_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'shareable'  => 'array',
        'send_times' => 'integer',
    ];

    public function byUser()
    {
        return $this->belongsTo(User::class, 'invite_by');
    }

}
