<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            System Activation Status
        </x-slot>

        <div class="flex items-center space-x-4">
            @if(str_contains($this->activationStatus, 'Activated!'))
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <x-filament::icon
                        icon="heroicon-o-check-circle"
                        class="w-8 h-8 text-green-600 dark:text-green-400"
                    />
                </div>
                <div>
                    <h3 class="text-lg font-medium text-green-700 dark:text-green-300">
                        System Activated
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $this->activationStatus }}
                    </p>
                </div>
            @else
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <x-filament::icon
                        icon="heroicon-o-exclamation-triangle"
                        class="w-8 h-8 text-yellow-600 dark:text-yellow-400"
                    />
                </div>
                <div>
                    <h3 class="text-lg font-medium text-yellow-700 dark:text-yellow-300">
                        System Not Activated
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Please enter your purchase code below to activate your system.
                    </p>
                </div>
            @endif
        </div>
    </x-filament::section>

    <form wire:submit="activate">
        {{ $this->form }}

        <div class="mt-4 flex justify-end">
            <x-filament::button
                type="submit"
                size="lg"
            >
                Activate System
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
