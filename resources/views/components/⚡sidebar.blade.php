<?php

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\User;

new class extends Component
{
    public $usersCount = 0;

    #[On('refreshUserCount')]
    public function refreshUserCount()
    {
        $this->usersCount = User::count();
    }

    public function mount()
    {
        $this->usersCount = User::count();
    }
};
?>

<div>
    <x-ui.sidebar class="dark:bg-zinc-950 dark:border-zinc-800 border-r transition-colors duration-300">
        <x-slot:brand>
            <x-ui.brand name="INVENTORY" href="/" wire:navigate class="dark:text-white">
                <x-slot:logo>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                        class="size-6 text-blue-600 dark:text-blue-500">
                        <rect x="15" y="10" width="80" height="15" fill="currentColor" rx="5" ry="0" />
                        <rect x="15" y="30" width="60" height="15" fill="currentColor" />
                        <rect x="15" y="50" width="30" height="15" fill="currentColor" />
                        <rect x="15" y="55" width="10" height="30" fill="currentColor" />
                    </svg>
                </x-slot:logo>
            </x-ui.brand>
        </x-slot:brand>

        <x-ui.navlist>
            <x-ui.navlist.group label="Main" class="dark:text-zinc-500 uppercase text-[10px] tracking-widest font-bold">
                <x-ui.navlist.item label="Dashboard" icon="home" href="/dashboard"
                    :active="request()->routeIs('dashboard.index')" wire:navigate
                    class="dark:hover:bg-zinc-500 dark:text-zinc-300 dark:active:text-white" />
                <x-ui.navlist.item label="Analytics" icon="chart-bar" href="/analytics"
                    class="dark:hover:bg-zinc-900 dark:text-zinc-300" />
            </x-ui.navlist.group>

            <x-ui.navlist.group label="Management" collapsable
                class="dark:text-zinc-500 uppercase text-[10px] tracking-widest font-bold">
                <x-ui.navlist.item label="Users" icon="users" href="/users" badge="{{ $this->usersCount }}"
                    :active="request()->routeIs('users*')" wire:navigate
                    class="dark:hover:bg-zinc-900 dark:text-zinc-300" />
                <x-ui.navlist.item label="Products" icon="cube" href="{{ route('products.index') }}" wire:navigate
                    :active="request()->routeIs('products*')" class="dark:hover:bg-zinc-900 dark:text-zinc-300" />
                <x-ui.navlist.item label="Stores" icon="building-storefront" href="{{ route('stores.index') }}"
                    wire:navigate :active="request()->routeIs('stores*')"
                    class="dark:hover:bg-zinc-900 dark:text-zinc-300" />
            </x-ui.navlist.group>
        </x-ui.navlist>

        <x-ui.sidebar.push />

        <div class="px-2 pb-4">
            <x-ui.dropdown>
                <x-slot:button class="w-full">
                    <div
                        class="flex items-center gap-3 p-2 rounded-lg dark:hover:bg-zinc-900 dark:text-zinc-300 transition-all border border-transparent dark:hover:border-zinc-800">
                        <x-ui.avatar size="sm"
                            src="https://ui-avatars.com/api/?name=Alex+Morgan&background=6366f1&color=fff" circle
                            alt="Profile Picture" />
                        <div class="flex-1 text-left overflow-hidden">
                            <p class="text-sm font-medium dark:text-white truncate">Profile Settings</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-50" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </x-slot:button>

                <x-slot:menu class="w-56! dark:bg-zinc-900 dark:border-zinc-800 shadow-2xl">
                    <x-ui.dropdown.item href="{{ route('settings.index') }}" wire:navigate icon="adjustments-horizontal"
                        class="dark:text-zinc-300 dark:hover:bg-zinc-800">
                        Preference
                    </x-ui.dropdown.item>
                </x-slot:menu>
            </x-ui.dropdown>
        </div>
    </x-ui.sidebar>
</div>