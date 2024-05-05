<?php

namespace App\Models\User;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserMdls extends Authenticatable implements JWTSubject
{
    protected $table = 'users_tbl';
    use HasUuids;

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'email',
        'password',
        'nama',
        'account_type'
    ];

    protected function getAllNonAdminUsers()
    {
        return DB::table($this->table)
        ->select('id','email','nama', 'created_at', 'updated_at')
        ->where('account_type', '=', 'user')->get();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
