<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'patronymic',
        'status',
        'phone_number',
        'email',
        'login',
        'password',
    ];


    public function getFullNameAttribute()
    {
        return trim("{$this->last_name} {$this->first_name} {$this->patronymic}");
    }

    public function isAdmin()
    {
        return $this->status === 'admin';
    }

    public function isParent()
    {
        return $this->status === 'parent';
    }

    public function isEducator()
    {
        return $this->status === 'educator';
    }


    public function children()
    {
        return $this->hasMany(Child::class, 'parent_id');
    }

   // App/Models/User.php

public function groups()
{
    return $this->belongsToMany(Group::class, 'groups_educator', 'educator_id', 'group_id');
}

    public function groupsAsEducator()
    {
        return $this->belongsToMany(Group::class, 'groups_educator', 'educator_id', 'group_id')->withTimestamps();
    }


    public function chatsAsParent()
    {
        return $this->hasMany(Chat::class, 'parent_id');
    }

    public function chatsAsParticipant()
    {
        return $this->hasMany(Chat::class, 'participant_id');
    }

    public function allChats()
    {
        return Chat::where('parent_id', $this->id)
            ->orWhere('participant_id', $this->id)
            ->with(['parent', 'participant', 'lastMessage'])
            ->get()
            ->sortByDesc(function($chat) {
                return $chat->lastMessage ? $chat->lastMessage->created_at : $chat->created_at;
            });
    }

    public function getAdminChat()
    {
        return Chat::where('type', 'parent_admin')
            ->where('parent_id', $this->id) // ← parent_id вместо participant_id
            ->with([
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                }
            ])
            ->first();
    }

    public function getAdminChatEducator()
    {
        return Chat::where('type', 'educator_admin')
            ->where('participant_id', $this->id)
            ->with(['messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->first();
    }

    public function educatorChats()
    {
        return $this->hasMany(Chat::class, 'parent_id')
            ->where('type', 'parent_educator')
            ->with(['participant', 'lastMessage']); 
    }

    public function trustedPeople()
    {
        return $this->hasMany(TrustedPerson::class, 'parent_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
