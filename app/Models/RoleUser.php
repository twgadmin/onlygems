<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;
    protected $table = "role_user";

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'role_id', 'user_id', 'deleted_at', 'created_at', 'updated_at'
    ];

}
