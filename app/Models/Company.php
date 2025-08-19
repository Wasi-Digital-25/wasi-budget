<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['name','slug','plan','currency'];

    public function users(): HasMany { return $this->hasMany(User::class); }
    public function quotes(): HasMany { return $this->hasMany(Quote::class); }
}
