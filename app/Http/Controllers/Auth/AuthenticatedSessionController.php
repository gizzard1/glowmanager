<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use App\Models\log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        try{
            $ip = $this->obtenerIP();
            $log = log::create([
                'user_id' => Auth::user()->id,
                'ip' => $ip,
                'out' => false,
            ]);
            $log->save();
        
        }catch(\Throwable $th){
            dd($th);
        }

        return redirect()->route('agenda');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $ip = $this->obtenerIP();
        $log = log::create([
            'user_id' => Auth::user()->id,
            'ip' => $ip,
            'out' => true,
        ]);
        $log->save();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        return redirect('login');
    }

    private function obtenerIP() {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_VIA']) && filter_var($_SERVER['HTTP_VIA'], FILTER_VALIDATE_IP)) {
            return $_SERVER['HTTP_VIA'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
 