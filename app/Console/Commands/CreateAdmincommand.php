<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmincommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Creates Admin User';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // dd($this->getArguments());
        $name = $this->ask('What is Admin name');
        $email = $this->ask('What is Admin email');
        $password = $this->ask('What is Admin password');
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->all());
        }
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'email_verified_at' => now(),
            'otp_code' => rand(100000, 999999),
        ]);
        $this->info('Admin' . $name  . 'Created Successfully! ');
    }
}
