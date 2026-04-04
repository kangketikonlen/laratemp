<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\ActivityLogs\LogsUserActivity;
use App\Support\Auth\AuthPageData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    use LogsUserActivity;

    public function login(): View
    {
        return view('auth.login', [
            'page' => AuthPageData::resolve(),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();

        $this->logUserActivity(
            activity: 'User logged out',
            category: 'security',
            status: 'info',
            user: $user,
            request: $request,
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
