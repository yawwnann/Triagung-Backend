<x-filament::layouts.app>
    <x-filament::card>
        <form wire:submit.prevent="authenticate">
            <x-filament::input label="Email" wire:model.defer="email" type="email" required autofocus />
            <x-filament::input label="Password" wire:model.defer="password" type="password" required />
            <x-filament::checkbox label="Remember me" wire:model.defer="remember" />
            <x-filament::button type="submit" class="w-full">Login</x-filament::button>
        </form>
    </x-filament::card>
</x-filament::layouts.app>