<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountVerification;
use Illuminate\Contracts\View\View;

class AccountVerificationController extends Controller
{
    public function index(): View
    {
        return view('admin.verifications.index');
    }

    public function create()
    {
        $verification = auth()->guard()->user()->accountVerification;

        $message = match ($verification?->status) {
            'approved' => [
                'type' => 'error',
                'text' => 'Your account has already been verified.',
            ],

            'pending' => [
                'type' => 'info',
                'text' => 'Your account verification is still pending.',
            ],

            'rejected' => [
                'type' => 'error',
                'text' => 'Your account verification was rejected.',
            ],

            default => null,
        };

        return view('verifications.create', compact('message'));
    }

    public function show(AccountVerification $verification): View
    {
        return view('admin.verifications.show', compact('verification'));
    }

    public function status(): View
    {
        return view('verifications.status');
    }
}