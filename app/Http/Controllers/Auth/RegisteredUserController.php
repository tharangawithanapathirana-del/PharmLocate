<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Customer;
use App\Models\Pharmacy;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
  public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'phone'    => ['nullable', 'string', 'max:20'],
        'address'  => ['nullable', 'string', 'max:500'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'phone'    => $request->phone,
        'address'  => $request->address,
        'role'     => $request->role ?? 'customer',
        'password' => Hash::make($request->password),
    ]);

    if ($user->role === 'pharmacy') {
        Pharmacy::create([
    'user_id'   => $user->id,
    'name'      => $user->name,
    'email'     => $user->email,
    'phone'     => $user->phone,
    'address'   => $user->address,
    'latitude'  => $request->latitude,
    'longitude' => $request->longitude,
    'status'    => 'pending',
]);
    } else {
        Customer::create([
            'user_id' => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'phone'   => $user->phone,
            'address' => $user->address,
        ]);
    }

    event(new Registered($user));
    Auth::login($user);

    if ($user->role === 'pharmacy') {
        return redirect('/pharmacy-dashboard');
    }

    return redirect('/customer-dashboard');
}
}
