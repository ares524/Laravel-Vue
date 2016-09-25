<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'address' => 'required|max:255',
            'age' => 'required|max:255',
            'weight' => 'required|max:255',
            'height' => 'required|max:255',
            'hair_colour' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $request)
    {
        $date = date("Y-m-d", strtotime($request->input('age')));
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'address' => 'required|max:255',
            'age' => 'required|max:255',
            'weight' => 'required|max:255',
            'height' => 'required|max:255',
            'hair_colour' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        $dds = User::create([
            'name' => ucwords($request->input('name')),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'age' => $date,
            'weight' => $request->input('weight'),
            'height' => $request->input('height'),
            'hair_colour' => $request->input('hair_colour'),
            'password' => bcrypt($request->input('password'))
        ]);
        Session::flash('success', 'You have been successfully Register!');
//        dd($dds);
        return redirect('auth/login');
    }
}