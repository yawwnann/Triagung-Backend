<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="authenticate">
        <div class="space-y-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold tracking-tight">
                    Trijaya Agung Admin
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    Sign in to your account
                </p>
            </div>

            {{ $this->form }}

            <x-filament::button type="submit" form="authenticate" class="w-full">
                Sign in
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page.simple>