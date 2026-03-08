<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\User;

new #[Title('Create User')] class extends Component
{
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
    <div class="max-w-2xl">
        <form wire:submit="create">
            <x-ui.fieldset>
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
</div>