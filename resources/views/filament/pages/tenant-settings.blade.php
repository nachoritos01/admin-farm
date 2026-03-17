<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::section>
            <x-slot name="heading">{{ __('farm.business_details') }}</x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.business_name') }}</label>
                    <input type="text" wire:model="businessName"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.slogan') }}</label>
                    <input type="text" wire:model="slogan"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('Phone') }}</label>
                    <input type="text" wire:model="contactPhone"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('Email') }}</label>
                    <input type="email" wire:model="email"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('Address') }}</label>
                    <input type="text" wire:model="address"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
            </div>
        </x-filament::section>

        <x-filament::section class="mt-6">
            <x-slot name="heading">{{ __('farm.business_logo') }}</x-slot>

            @if ($this->canUploadLogo())
                <div class="space-y-4">
                    @if ($existingLogoPath)
                        <div class="flex items-center gap-4">
                            <img src="{{ Storage::disk('public')->url($existingLogoPath) }}"
                                 alt="{{ __('farm.current_logo') }}"
                                 class="h-16 max-w-[200px] object-contain rounded border border-gray-200 dark:border-gray-600">
                            <button type="button" wire:click="removeLogo"
                                class="text-sm text-red-600 hover:text-red-800 dark:text-red-400">
                                {{ __('farm.remove_logo') }}
                            </button>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                            {{ $existingLogoPath ? __('farm.change_logo') : __('farm.upload_logo') }}
                        </label>
                        <input type="file" wire:model="logo" accept="image/png,image/jpeg"
                            class="block w-full text-sm text-gray-500 dark:text-white
                                   file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                   file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700
                                   hover:file:bg-primary-100 dark:file:bg-gray-600 dark:file:text-gray-200">
                        <p class="mt-1 text-xs text-gray-500 dark:text-white">{{ __('farm.logo_hint') }}</p>
                    </div>

                    @if ($logo && !$errors->has('logo'))
                        <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                            <x-heroicon-o-check-circle class="w-4 h-4" />
                            {{ __('farm.logo_ready') }}
                        </div>
                    @endif
                </div>
            @else
                <div class="rounded-lg bg-amber-50 dark:bg-amber-900/20 p-4 text-sm text-amber-800 dark:text-amber-200">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-lock-closed class="w-5 h-5 flex-shrink-0" />
                        <span><strong>{{ __('farm.upgrade_branding') }}</strong> {{ __('farm.logo_plan_requirement') }}</span>
                    </div>
                </div>
            @endif
        </x-filament::section>

        <x-filament::section class="mt-6">
            <x-slot name="heading">{{ __('farm.farm_configuration') }}</x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.daily_egg_goal') }}</label>
                    <input type="number" wire:model="dailyEggGoal" min="0"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="{{ __('farm.placeholder_egg_goal') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.monthly_income_goal') }}</label>
                    <input type="number" wire:model="monthlyIncomeGoal" min="0" step="0.01"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="{{ __('farm.placeholder_income_goal') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.tray_price') }}</label>
                    <input type="number" wire:model="trayPrice" min="0" step="0.01"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="{{ __('farm.placeholder_tray_price') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.kilogram_price') }}</label>
                    <input type="number" wire:model="kgPrice" min="0" step="0.01"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="{{ __('farm.placeholder_kg_price') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.standard_shipping_cost') }}</label>
                    <input type="number" wire:model="standardShippingCost" min="0" step="0.01"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="{{ __('farm.placeholder_shipping_cost') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.responsible_name') }}</label>
                    <input type="text" wire:model="responsibleName"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="{{ __('farm.placeholder_responsible') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ __('farm.municipality') }}</label>
                    <input type="text" wire:model="municipality"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="{{ __('farm.placeholder_municipality') }}">
                </div>
            </div>
        </x-filament::section>

        <x-filament::section class="mt-6">
            <x-slot name="heading">{{ __('farm.egg_price_matrix') }}</x-slot>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('farm.egg_price_matrix_hint') }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach (\App\Enums\EggSize::cases() as $size)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">{{ $size->label() }}</label>
                        <input type="number" wire:model="eggSizePrices.{{ $size->value }}" min="0" step="0.01"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="{{ __('farm.price_per_unit') }}">
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <div class="mt-6 flex justify-end">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                {{ __('farm.save_settings') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
