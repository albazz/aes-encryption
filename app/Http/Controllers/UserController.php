<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'nik' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => Crypt::encrypt($request->nik),
            'phone_number' => Crypt::encrypt($request->phone_number),
            'address' => Crypt::encrypt($request->address),
        ]);

        return response()->json(['data' => $user]);
    }

    public function userEnc($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['data' => $user]);
    }

    public function userEncid($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['data' => $user->only(['id', 'email'])]);
    }

    public function userDec($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->nik = Crypt::decrypt($user->nik);
        $user->phone_number = Crypt::decrypt($user->phone_number);
        $user->address = Crypt::decrypt($user->address);

        return response()->json(['data' => $user]);
    }

    public function userDecid($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->nik = Crypt::decrypt($user->nik);
        $user->phone_number = Crypt::decrypt($user->phone_number);
        $user->address = Crypt::decrypt($user->address);

        return response()->json(['data' => $user->only(['id', 'email'])]);
    }

    public function userUpdateEnc(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'password' => 'sometimes|required|string',
            'nik' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('nik')) {
            $user->nik = Crypt::encrypt($request->nik);
        }
        if ($request->has('phone_number')) {
            $user->phone_number = Crypt::encrypt($request->phone_number);
        }
        if ($request->has('address')) {
            $user->address = Crypt::encrypt($request->address);
        }

        $user->save();

        return response()->json(['data' => $user, 'message' => 'User updated successfully']);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
