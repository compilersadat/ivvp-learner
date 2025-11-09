<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Content extends Model
{
    protected $guarded =['id'];
    protected $table = "contents";

    public function fileUpload(): BelongsTo
    {
        return $this->belongsTo(S3upload::class, 'file_url');
    }
}
