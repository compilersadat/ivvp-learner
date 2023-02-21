<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    protected $guarded =['id'];
    protected $table = "personal_access_tokens";
}
