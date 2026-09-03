<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountVerification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
                'type' => 'error',
                'text' => 'Your account verification is still pending.',
            ],

            'rejected' => [
                'type' => 'error',
                'text' => 'Your account verification was rejected.',
            ],

            default => null,
        };

        if($verification?->status === 'approved') {
            return redirect()->route('home')
                ->with('success', 'Your account is verified.');
        }

        return view('verifications.create', compact('message'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'extension_name' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['required', 'date'],

            'block' => ['required', 'string', 'max:255'],
            'lot' => ['required', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'subdivision' => ['required', 'string', 'max:255'],
        ]);

        AccountVerification::updateOrCreate(
            [
                'user_id' => auth()->guard()->id(),
            ],
            [
                ...$validated,
                'status' => 'pending',
                'rejection_reason' => null,
                'reviewed_by' => null,
            ]
        );

        return redirect()->route('verifications.status')
            ->with('success', 'Your account verification request has been submitted successfully.');
    }

    public function show(AccountVerification $verification): View
    {
        return view('admin.verifications.show', compact('verification'));
    }

    public function status()
    {
        $verification = auth()->guard()->user()->accountVerification;

        if($verification?->status === 'approved') {
            return redirect()->route('home')
                ->with('success', 'Your account is verified.');
        }

        return view('verifications.status');
    }
}