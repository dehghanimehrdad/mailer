<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Mail extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['to','body','subject'];

    protected $dates = ['sent_at'];
}
