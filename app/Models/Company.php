<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    protected $fillable = [
        'name',
        'email',
        'logo_path',
        'website',
    ];

    protected $casts = [
        'created_at' => 'datetime:H:i d M Y',
        'updated_at' => 'datetime:H:i d M Y',
    ];

    protected $appends = ['logo_url'];


    public function getLogoUrlAttribute(): ?string
    {
        return '/storage/' . $this->logo_path;
    }

    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class);
    }
    
}
