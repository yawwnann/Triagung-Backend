<x-filament::layouts.app>
    <x-filament::card>
        <form wire:submit.prevent="authenticate">
            <x-filament::input label="Email" wire:model.defer="email" type="email" required autofocus />
            <x-filament::input label="Password" wire:model.defer="password" type="password" required />
            <label class="flex items-center space-x-2">
                <input type="checkbox" wire:model.defer="remember" />
                <span>Remember me</span>
            </label>
            <x-filament::button type="submit" class="w-full">Login</x-filament::button>
        </form>
    </x-filament::card>
</x-filament::layouts.app>