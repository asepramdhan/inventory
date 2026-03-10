<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\User;
use Livewire\WithFileUploads;

new #[Title('Create User')] class extends Component
{
    use WithFileUploads;

    #[Validate('nullable')]
    #[Validate('image', message: 'Avatar is invalid')]
    #[Validate('max:2048', message: 'Avatar size is too large')]
    #[Validate('mimes:jpeg,jpg,png', message: 'Avatar format is invalid')]
    public $avatar;
    #[Validate('required', message: 'Full name is required')]
    #[Validate('min:3', message: 'Full name must be at least 3 characters')]
    public $name;
    #[Validate('required', message: 'Email is required')]
    #[Validate('email', message: 'Email is invalid')]
    #[Validate('unique:users,email', message: 'Email already exists')]
    public $email;
    #[Validate('required', message: 'Password is required')]
    #[Validate('min:8', message: 'Password must be at least 8 characters')]
    public $password;
    #[Validate('required', message: 'Password confirmation is required')]
    #[Validate('same:password', message: 'Password confirmation does not match')]
    public $password_confirmation;
    #[Validate('unique:users,phone', message: 'Phone number already exists')]
    #[Validate('min:10', message: 'Phone number must be at least 10 characters')]
    public $phone;
    #[Validate('in:admin,user', message: 'Role is invalid')]
    public $role = 'user';
    public function create()
    {
        // validate
        $validated = $this->validate();

        // avatar
        if ($this->avatar) {
            $validated['avatar'] = $this->avatar->store('images/avatars', 'public');
        }
        
        // hash password
        $validated['password'] = Hash::make($validated['password']);
        // create user
        User::create($validated);
        // notify user
        session()->flash('notify', [
            'content' => 'User created successfully',
            'type' => 'success',
        ]);
        // redirect to index
        return $this->redirect(route('users.index'), navigate: true);
    }
};
?>

<div class="space-y-4">
    <!-- Header -->
    <div class="lg:flex lg:items-center lg:justify-between">
        <h1 class="text-2xl font-bold tracking-tight mb-4 lg:mb-0">Create User</h1>
    </div>
    <!-- Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form wire:submit="create">
                <x-ui.fieldset>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.field required>
                            <x-ui.label>Full Name</x-ui.label>
                            <x-ui.input wire:model="name" placeholder="John Doe" />
                            <x-ui.error name="name" />
                        </x-ui.field>

                        <x-ui.field required>
                            <x-ui.label>Email Address</x-ui.label>
                            <x-ui.input type="email" wire:model="email" placeholder="john@example.com" />
                            <x-ui.error name="email" />
                        </x-ui.field>
                    </div>

                    <x-ui.field>
                        <x-ui.label>Avatar</x-ui.label>
                        <x-ui.input type="file" wire:model="avatar" />
                        <x-ui.error name="avatar" />
                    </x-ui.field>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                        <x-ui.field required>
                            <x-ui.label>Password</x-ui.label>
                            <x-ui.input type="password" wire:model="password" placeholder="••••••••" />
                            <x-ui.error name="password" />
                        </x-ui.field>

                        <x-ui.field required>
                            <x-ui.label>Confirm Password</x-ui.label>
                            <x-ui.input type="password" wire:model="password_confirmation" placeholder="••••••••" />
                            <x-ui.error name="password_confirmation" />
                        </x-ui.field>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <x-ui.field>
                            <x-ui.label>Phone Number</x-ui.label>
                            <x-ui.input
                                x-mask:dynamic="$input.startsWith('+62') ? '999 9999 99999' : '999 9999 99999 99999'"
                                type="tel" wire:model="phone" placeholder="+628 1234 5678" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Role</x-ui.label>
                            <x-ui.select wire:model="role">
                                <x-ui.select.option value="admin">Admin</x-ui.select.option>
                                <x-ui.select.option value="user">User</x-ui.select.option>
                            </x-ui.select>
                        </x-ui.field>
                    </div>

                    <div class="flex justify-end space-x-3 mt-8">
                        <x-ui.button href="{{ route('users.index') }}" wire:navigate variant="secondary"
                            class="w-full md:w-auto">Back
                        </x-ui.button>
                        <x-ui.button type="submit" class="w-full md:w-auto">Create User</x-ui.button>
                    </div>
                </x-ui.fieldset>
            </form>
        </div>
        <!-- Preview Avatar -->
        <div class="space-y-4">
            <x-ui.label>Avatar Preview</x-ui.label>
            <!-- Container untuk preview gambar -->
            <div
                class="relative flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 min-h-64">
                <div class="flex flex-col items-center justify-center w-full p-4">
                    @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}"
                        class="rounded-lg object-cover w-full h-auto max-h-64 shadow-sm">
                    <button type="button" wire:click="$set('avatar', null)"
                        class="mt-2 text-xs text-red-600 hover:underline">Hapus Avatar</button>
                    @else
                    <div class="text-center text-gray-400">
                        <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm">Belum ada avatar</p>
                    </div>
                    @endif
                </div>

                <!-- Loading Indicator -->
                <div wire:loading wire:target="image" class="absolute inset-0 z-50 bg-white/80 backdrop-blur-sm">

                    <!-- Inner div untuk alignment tengah sempurna -->
                    <div class="flex flex-col items-center justify-center w-full h-full">
                        <svg class="animate-spin h-8 w-8 text-blue-600 mb-2" xmlns="http://www.w3.org" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Uploading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>