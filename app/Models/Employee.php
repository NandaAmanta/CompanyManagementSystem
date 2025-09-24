<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'company_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:H:i d M Y',
        'updated_at' => 'datetime:H:i d M Y',
    ];

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
