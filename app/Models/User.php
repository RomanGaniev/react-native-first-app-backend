<?php

namespace App\Models;

use App\Models\Api\v1\Chat\Chat;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Api\v1\Post;
use App\Models\Api\v1\Comment;
use App\Models\Api\v1\UserMessage;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(UserMessage::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(UserMessage::class, 'recipient_id');
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class);
    }

    public function canJoinChat($chatId)
    {
        if ($this->chats->find($chatId)) {
            return true;
        } else {
            return false;
        }
    }


    //======================== functions to get friends attribute =========================

    // friendship that this user started
	protected function friendsOfThisUser()
	{
		return $this->belongsToMany(User::class, 'friendships', 'first_user', 'second_user')
		->withPivot('status')
		->wherePivot('status', 'confirmed');
	}

	// friendship that this user was asked for
	protected function thisUserFriendOf()
	{
		return $this->belongsToMany(User::class, 'friendships', 'second_user', 'first_user')
		->withPivot('status')
		->wherePivot('status', 'confirmed');
	}

	// accessor allowing you call $user->friends
	public function getFriendsAttribute()
	{
		if ( ! array_key_exists('friends', $this->relations)) $this->loadFriends();
		return $this->getRelation('friends');
	}

	protected function loadFriends()
	{
		if ( ! array_key_exists('friends', $this->relations))
		{
		$friends = $this->mergeFriends();
		$this->setRelation('friends', $friends);
	}
	}

	protected function mergeFriends()
	{
		if($temp = $this->friendsOfThisUser)
		return $temp->merge($this->thisUserFriendOf);
		else
		return $this->thisUserFriendOf;
	}
    //======================== end functions to get friends attribute =========================



    //====================== functions to get blocked_friends attribute ============================

	// friendship that this user started but now blocked
	protected function friendsOfThisUserBlocked()
	{
		return $this->belongsToMany(User::class, 'friendships', 'first_user', 'second_user')
					->withPivot('status', 'acted_user')
					->wherePivot('status', 'blocked')
					->wherePivot('acted_user', 'first_user');
	}

	// friendship that this user was asked for but now blocked
	protected function thisUserFriendOfBlocked()
	{
		return $this->belongsToMany(User::class, 'friendships', 'second_user', 'first_user')
					->withPivot('status', 'acted_user')
					->wherePivot('status', 'blocked')
					->wherePivot('acted_user', 'second_user');
	}

	// accessor allowing you call $user->blocked_friends
	public function getBlockedFriendsAttribute()
	{
		if ( ! array_key_exists('blocked_friends', $this->relations)) $this->loadBlockedFriends();
			return $this->getRelation('blocked_friends');
	}

	protected function loadBlockedFriends()
	{
		if ( ! array_key_exists('blocked_friends', $this->relations))
		{
			$friends = $this->mergeBlockedFriends();
			$this->setRelation('blocked_friends', $friends);
		}
	}

	protected function mergeBlockedFriends()
	{
		if($temp = $this->friendsOfThisUserBlocked)
			return $temp->merge($this->thisUserFriendOfBlocked);
		else
			return $this->thisUserFriendOfBlocked;
	}
    // ======================================= end functions to get block_friends attribute =========

    public function friend_requests()
    {
        return $this->hasMany(Friendship::class, 'second_user')
                    ->where('status', 'pending');
    }
}
