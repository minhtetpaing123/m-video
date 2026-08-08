<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Notification လက်ခံရရှိသူ
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Notification ပြုလုပ်သူ (Like/Comment လာပေးသူ)
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // သက်ဆိုင်ရာ Post
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
