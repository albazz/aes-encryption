<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;


class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $encryptionKey = env('DB_ENCRYPTION_KEY');

        // Encrypt data
        $encryptedNik = DB::raw("AES_ENCRYPT('1234567890', '$encryptionKey')");
        $encryptedPhoneNumber = DB::raw("AES_ENCRYPT('081708120815', '$encryptionKey')");
        $encryptedAddress = DB::raw("AES_ENCRYPT('Komplek Taman Palem Lestari Ruko Galaxy Blok N No. 27 Cengkareng Barat, Kota Jakarta Barat 11730', '$encryptionKey')");

        User::create([
            'name' => 'Admin',
            'email' => 'admin@sample.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'nik' => $encryptedNik,
            'phone_number' => $encryptedPhoneNumber,
            'address' => $encryptedAddress,
            'remember_token' => Str::random(10),
        ]);
    }
}
