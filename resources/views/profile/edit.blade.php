<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-brand-ice leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="page max-w-4xl space-y-6">
        <div class="section-card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="section-card">
            @include('profile.partials.update-password-form')
        </div>

        <div class="section-card">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
