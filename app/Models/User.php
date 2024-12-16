<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;  // Import the FilamentUser contract
use Filament\Panel; // Import the Panel class

class User extends Authenticatable implements FilamentUser // Implement the FilamentUser contract
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'username',
        'name',
    ];

    // Implement the canAccessPanel method with the correct signature
    public function canAccessPanel(Panel $panel): bool
    {
        // Here you can define logic based on the user's role
        // For example, only users with the 'admin' role can access the Filament panel
        return $this->role === 'admin';  // Adjust according to your roles/permissions setup
    }

    public function getUserName(): string
    { die("hkjhkjhkj");
        dd($this->first_name, $this->last_name); // This will dump the values of first_name and last_name
        return trim(($this->first_name ?? 'Unknown') . ' ' . ($this->last_name ?? 'User'));
    }
    

    public function getFilamentName(): string
    {
        return $this->getUserName();
    }

    // Other methods...


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
     * The attributes that should be cast.
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

    /**
     * The user's blogs.
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * The user's stories.
     */
    public function stories()
    {
        return $this->hasMany(Story::class, 'author_id');
    }

    /**
     * The user's comments.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'author_id');
    }

    /**
     * Set the user's password and hash it.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
}
