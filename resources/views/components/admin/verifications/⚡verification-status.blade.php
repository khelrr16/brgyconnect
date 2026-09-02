<?php

use Livewire\Component;
use App\Models\AccountVerification;

new class extends Component {
    public ?AccountVerification $verification = null;

    public function mount(): void
    {
        $this->verification = AccountVerification::where('user_id', auth()->guard()->id())
            ->latest()
            ->first();
    }
} ?>

<div class="bg-white shadow rounded-lg p-6">
    @if (! $verification)
        <p class="text-gray-500 text-sm">No verification request found.</p>

    @elseif ($verification->status === 'pending')
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-clock text-yellow-500 text-xl mt-1"></i>
            <div>
                <h3 class="font-semibold text-gray-800">Your request is pending review</h3>
                <p class="text-sm text-gray-600 mt-1">
                    We're checking your submitted information against our resident records.
                    This usually doesn't take long — check back soon.
                </p>
            </div>
        </div>

    @elseif ($verification->status === 'approved')
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-green-500 text-xl mt-1"></i>
            <div>
                <h3 class="font-semibold text-gray-800">Your account has been verified</h3>
                <p class="text-sm text-gray-600 mt-1">
                    You now have full access to your resident account.
                </p>
            </div>
        </div>

    @elseif ($verification->status === 'rejected')
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-circle-xmark text-red-500 text-xl mt-1"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800">Your request could not be verified</h3>

                <div class="mt-3 bg-red-50 border border-red-100 rounded-md p-3">
                    <p class="text-sm text-red-700">
                        {{ $verification->rejection_reason }}
                    </p>
                </div>

                @if (str_contains($verification->rejection_reason ?? '', 'No matching resident record found.') || str_contains($verification->rejection_reason ?? '', config('app.resident_google_form_url', '~~none~~')))
                    <a
                        href="https://docs.google.com/forms/d/e/1FAIpQLSf-8QX1oTqKpoKVjZl1z4adyZIROZTL2JoaI5DV-9CRuEq2YQ/viewform?usp=header"
                        target="_blank"
                        class="inline-flex items-center gap-1 mt-3 px-3 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
                    >
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Registration Form
                    </a>
                @endif

                <p class="text-xs text-gray-500 mt-3">
                    If you believe this is a mistake, please contact the admin office.
                </p>
            </div>
        </div>
    @endif
</div>