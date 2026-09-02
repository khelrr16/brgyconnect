<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Verification Request
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4">
        <livewire:admin.verifications.verification-show :verification="$verification" />
    </div>
</x-app-layout>