<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb
            :items="[
                [
                    'label' => 'Account Verifications',
                    'url' => route('admin.verifications.index')
                ],
            ]"
        />
    </x-slot>

    <livewire:admin.verifications.verification-index />

</x-app-layout>