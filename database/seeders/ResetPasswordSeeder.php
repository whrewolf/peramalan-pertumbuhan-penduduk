<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();
        
        if ($user) {
            $user->password = Hash::make('password123'); // Ganti dengan password yang diinginkan
            $user->save();
            
            echo "Password untuk {$user->email} berhasil direset!\n";
            echo "Email: {$user->email}\n";
            echo "Password baru: password123\n";
        } else {
            echo "User dengan email admin@gmail.com tidak ditemukan.\n";
        }
    }
}