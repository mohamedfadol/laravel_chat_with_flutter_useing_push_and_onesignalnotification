<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\MessageSent;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    const USER_TOKEN = "userToken";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = ['username','email','password'];

   
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
      * Get all of the chats for the User
      *
      * @return HasMany
      */
      public function chats(): HasMany
      {
          return $this->hasMany(Chat::class, 'created_by');
      }
  
      public function routeNotificationForOneSignal() : array{
          return ['tags'=>['key'=>'userId','relation'=>'=', 'value'=>(string)($this->id)]];
      }
  
      public function sendNewMessageNotification(array $data) : void {
          $this->notify(new MessageSent($data));
      }
}
