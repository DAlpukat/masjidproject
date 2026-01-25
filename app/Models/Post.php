<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image', 'content', 'page_id'];

    // Relasi: Post ini milik Page (Tab Info) apa?
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}