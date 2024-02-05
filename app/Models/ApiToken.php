<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;
    protected $table = 'personal_access_tokens';
    // protected $guarded = [];
    protected $fillable = [
        'name',
    ];

    protected static function booted()
    {
        static::addGlobalScope('personal_access_tokens', function (Builder $builder) {
            // if (!session('admin'))
            //     $builder->where('personal_access_tokens.tokenable_id', session('user_id'));
        });
    }

    function user()
    {
        return $this->belongsTo(User::class, 'tokenable_id');
    }
}
