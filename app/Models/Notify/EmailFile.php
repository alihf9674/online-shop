<?php

namespace App\Models\Notify;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'public_mail_files';

    protected $fillable = ['public_mail_id', 'public_path', 'file_size', 'file_type', 'status'];

    public function email(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Email::class, 'public_mail_id');
    }
}
