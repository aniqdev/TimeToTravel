<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function webLoginView(Request $request)
    {
        return view("auth.login");
    }

    public function webLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'min:6'],
            // 'password' => ['required', 'min:8', 'regex:/[a-zA-Z]/', 'regex:/[0-9]/'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return redirect("login")->withErrors(['email' => trans('auth.failed')]);
    }

    public function webRegisterView(Request $request)
    {
        return view("auth.register");
    }

    public function webRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', 'min:6'],
            // 'password' => ['required', 'confirmed', 'min:8', 'regex:/[a-zA-Z]/', 'regex:/[0-9]/'],
        ]);

        $user_data = request()->only('name', 'surname', 'email', 'password', 'avatar', 'description');
        $user_id = User::create($user_data);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->storePublicly('avatars', 'public');
            $avatar = 'storage/' . $path;
            User::updateAvatar($user_id, $avatar);
        }

        $request->session()->regenerate();

        return redirect("/login");
    }

    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::getByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json(['token' => $user->createToken($request->device_name)->plainTextToken]);
    }

    public function apiRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()],
            'device_name' => 'required'
        ]);

        $data = request()->only('name', 'surname', 'email', 'password', 'avatar', 'description');
        $id = User::create($data);
        $user = User::find($id);

        return response()->json(['token' => $user->createToken($request->device_name)->plainTextToken]);
    }

    public function webSignOut()
    {
        Session::flush();
        Auth::logout();

        return redirect('/login');
    }

    public function settings(Request $request)
    {
        return view("auth.settings", [
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user = Auth::user();
        $found_user = User::getByEmail($request->input('email'));

        if ($found_user && ($found_user['id'] != $user['id'])) {
            return redirect("settings");
        }

        $user_data = $request->only('name', 'surname', 'email', 'description');
        $user_data['id'] = $user['id'];

        if ($request->input('password') != "") {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()],
            ]);
    
            $user_data['password'] = $request->input('password');
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->storePublicly('avatars', 'public');
            $user_data['avatar'] = 'storage/' . $path;
        }

        if ($request->socials) {
            $user_data['socials'] = json_encode($request->socials);
        }

        // dd($user_data);

        $user = User::updateById($user_data);

        return redirect()->back()->with('status', 'Информация обновлена!');
    }
}
