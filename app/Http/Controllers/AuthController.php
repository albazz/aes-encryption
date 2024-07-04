<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Decrypt encrypted fields
            $encryptionKey = env('DB_ENCRYPTION_KEY');
            $user->nik = DB::selectOne("SELECT CAST(AES_DECRYPT(nik, ?) AS CHAR) AS nik FROM users WHERE id = ?", [$encryptionKey, $user->id])->nik;
            $user->phone_number = DB::selectOne("SELECT CAST(AES_DECRYPT(phone_number, ?) AS CHAR) AS phone_number FROM users WHERE id = ?", [$encryptionKey, $user->id])->phone_number;
            $user->address = DB::selectOne("SELECT CAST(AES_DECRYPT(address, ?) AS CHAR) AS address FROM users WHERE id = ?", [$encryptionKey, $user->id])->address;

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('authToken')->plainTextToken
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
