<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at',
        'reply_to_id', // 🔥 Reply လုပ်ထားသော မူရင်း စာတို၏ ID
        'is_edited',   // 🔥 Edit လုပ်ထားခြင်း ရှိ/မရှိ
        'deleted_for_everyone', // 🔥 Unsend ပြုလုပ်ထားခြင်း ရှိ/မရှိ
        'deleted_for_sender',   // 🔥 မိမိဘက်မှ ဖျက်ထားခြင်း ရှိ/မရှိ
        'deleted_for_receiver', // 🔥 တစ်ဖက်လူဘက်မှ ဖျက်ထားခြင်း ရှိ/မရှိ
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_edited' => 'boolean', // 🔥 Boolean အဖြစ် ပြောင်းရန်
        'deleted_for_everyone' => 'boolean',
        'deleted_for_sender' => 'boolean',
        'deleted_for_receiver' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // 🔥 Reply လုပ်ထားသော မူရင်း စာတိုကို ယူရန် Relationship
    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    // 🔥 Message Reactions Relationship
    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
