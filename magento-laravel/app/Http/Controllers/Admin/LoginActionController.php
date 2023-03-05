<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginActionController extends Controller
{
    public function __invoke(Request $request)
    {
        $hashedPassword = \Illuminate\Support\Facades\Hash::make($request->post('password'));
        $username = $request->post('username');
        // $user = User::where('name', $username)->get()->first();
        $credentials = [
            'name' => $username,
            'password' => $request->post('password')
        ];

        if (!Auth::validate($credentials)) {
            return redirect()->to('login')->withErrors(trans('auth.failed'));
        }
    
        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Auth::login($user);

        return $this->authenticated($request, $user);
    }

    protected function authenticated($request, $user) 
    {
        return redirect()->intended();
    }
}
