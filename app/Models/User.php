<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $primaryKey = "username";

    use HasFactory, Notifiable;

    const LEVEL_ADMIN = "admin";
    const LEVEL_PEGAWAI = "pegawai";

    const LEVELS = [
        self::LEVEL_ADMIN,
        self::LEVEL_PEGAWAI,
    ];

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];
}
