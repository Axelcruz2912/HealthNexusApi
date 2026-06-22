<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $fillable = ['name', 'icon', 'color', 'description', 'permissions', 'restricted_medications', 'ai_config', 'is_active'];
    
    protected $casts = [
        'permissions' => 'array',
        'restricted_medications' => 'array',
        'ai_config' => 'array',
    ];
    
    public function doctors()
    {
        return $this->hasMany(User::class);
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
