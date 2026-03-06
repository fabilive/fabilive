<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            System Cache Management
        </x-slot>

        <p class="text-sm text-gray-600 dark:text-gray-400">
            Clicking the button below will clear the application cache, configuration cache, route cache, and compiled views. 
            This is useful if you have made changes to the system and they are not reflecting yet.
        </p>

        <x-slot name="footer">
            <div class="flex justify-end">
                <x-filament::button
                    wire:click="clearCache"
                    color="danger"
                    icon="heroicon-o-trash"
                >
                    Clear All Cache
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::section>
</x-filament-panels::page>
