<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'layanan_id',
        'sekolah_id',
        'ptk_id',
        'peserta_id',
        'pengawas_id',
        'username',
        'email',
        'password',
        'address',
        'phone_number',
        'whatsapp_number',
        'avatar',
        'google_id',
        'last_login',
        'role',
        'type',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            // if (session('sekolah_id'))
            //     $builder->where('users.sekolah_id', session('sekolah_id'));
        });
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($avatar) => $avatar && file_exists('/storage/app/sekolah/' . $this->sekolah_id . "/users/" . $avatar) ? asset('/storage/app/sekolah/' . $this->sekolah_id . "/users/" . $avatar) : null,
        );
    }

    function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    function ptk()
    {
        return $this->hasMany(Ptk::class);
    }

    function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    function pengawas()
    {
        return $this->belongsTo(Pengawas::class);
    }

    function proktor()
    {
        return $this->belongsTo(Ruang::class, 'id', 'id');
    }

    function selectFormInput($where = null)
    {
        $query = $this->orderBy('id', 'ASC');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[null] = '.:: User ::.';
        } else {
            $data = '<option value="">.:: User ::.</option>';
        }

        foreach ($query->get() as $ref) {
            $id = $ref->id;
            $val = $ref->name;

            if (request()->ajax()) {
                $data[$id] =  $val;
            } else {
                $data .= '<option value="' . $id . '">' . $val . '</option>';
            }
        }
        return $data;
    }
}
