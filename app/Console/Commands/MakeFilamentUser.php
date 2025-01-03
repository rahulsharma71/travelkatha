<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MakeFilamentUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-filament-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Filament admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('name');
        $email = $this->ask('Email Address');
        $password = $this->secret('Password');
      

        $role = $this->choice('Role', ['admin', 'user'], 0); // Default to 'admin'

        try {
            $user = User::create([
                'name' => $name,
              
                'email' => $email,
                'password' => Hash::make($password),
                

                'role' => $role,
            ]);

            $this->info("User {$user->first_name} {$user->last_name} created successfully.");
        } catch (\Exception $e) {
            $this->error("Error creating user: " . $e->getMessage());
        }
    }
}
