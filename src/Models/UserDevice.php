<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    // use SoftDeletes;

    protected $table = 'user_devices';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model','brand','os','version','uuid','app_project_id','is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    public function scopeWithPushToken($query)
    {
        return $query->whereNotNull('push_token')->where('push_token', '!=', '');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function appProject()
    {
        return $this->belongsTo(\App\Models\AppProject::class);
    }
}
