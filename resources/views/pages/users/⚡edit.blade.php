<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\User;

new #[Title('Edit User')] class extends Component
{
    public User $user;
    #[Validate('required', message: 'Full name is required')]
    #[Validate('min:3', message: 'Full name must be at least 3 characters')]
    public $name;
    #[Validate('required', message: 'Email is required')]
    #[Validate('email', message: 'Email is invalid')]
    public $email;
    #[Validate('min:10', message: 'Phone number must be at least 10 characters')]
    public $phone;
    #[Validate('in:admin,user', message: 'Role is invalid')]
    public $role = 'user';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
    }

    public function update()
    {
        // validate
        $validated = $this->validate();
        // update user
        $this->user->update($validated);
        // notify user
        session()->flash('notify', [
            'content' => 'User updated successfully',
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
        <h1 class="text-2xl font-bold tracking-tight mb-4 lg:mb-0">Edit User</h1>
    </div>
    <!-- Form -->
    <div class="max-w-2xl">
        <form wire:submit="update">
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
                    <x-ui.button type="submit" class="w-full md:w-auto">Update User</x-ui.button>
                </div>
            </x-ui.fieldset>
        </form>
    </div>
</div>