<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Account Verification Requests
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4">
        <livewire:admin.verifications.verification-index />
    </div>
</x-app-layout>