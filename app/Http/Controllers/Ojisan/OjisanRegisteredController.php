<?php

namespace App\Http\Controllers\Ojisan;

use App\Http\Controllers\Controller;
use App\Models\Ojisan;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class OjisanRegisteredController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('ojisan.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Ojisan::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $ojisan = Ojisan::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($ojisan));

        Auth::guard('ojisan')->login($ojisan);

        return redirect(RouteServiceProvider::HOME);
    }
}
