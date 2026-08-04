<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasswordResetLinkController extends Controller
{
  public function create(): View
  {
    return view('auth.forgot-password');
  }

  public function store(Request $request): RedirectResponse
  {
    $request->validate(['email' => ['required', 'email']]);

    return back()->with('status', 'Password reset link feature will be connected to Laravel Breeze in the next iteration.');
  }
}
