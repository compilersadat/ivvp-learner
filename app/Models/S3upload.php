<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class S3upload extends Model
{
    protected $guarded =['id'];
    protected $table = "s3uploads";

}
