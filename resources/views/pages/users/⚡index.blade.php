<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Computed;

new #[Title('Users Management')] class extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $userIdBeingDeleted = null; // Simpan ID sementara

    public function updatingSearch($value)
    {
        $this->resetPage();
    }

    #[Computed]
    public function users()
    {
        return User::where('name', 'like', "%{$this->search}%")->paginate(10);
    }

    public function paginationView()
    {
        return 'custom-pagination-links-view';
    }

    public function confirmDelete($id)
    {
        $this->userIdBeingDeleted = $id;
        // Di sini Anda bisa memicu buka modal via JS jika komponen UI Anda memerlukannya
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->userIdBeingDeleted);
        $user->delete();

        $this->userIdBeingDeleted = null;

        $this->dispatch('notify',
            type: 'success',
            content:'User deleted successfully',
            duration: 2000
        ); 
    }

};
?>

<div class="space-y-4">
    <!-- Header -->
    <div class="lg:flex lg:items-center lg:justify-between">
        <h1 class="text-2xl font-bold tracking-tight mb-4 lg:mb-0">Users Management</h1>
        <x-ui.button wire:navigate href="{{ route('users.create') }}" class="w-full md:w-auto">
            Create User
        </x-ui.button>
    </div>

    <!-- Search -->
    <div class="max-w-md">
        <x-ui.input wire:model.live="search" placeholder="Search..." leftIcon="magnifying-glass" clearable />
    </div>

    <!-- Table -->
    <div
        class="overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-zinc-800/50 border-b border-gray-200 dark:border-zinc-800">
                        <th
                            class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">
                            User</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">
                            Role</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">
                            Status</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">
                            Joined</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">
                            Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse ($this->users as $user)
                    <tr wire:key="{{ $user->id }}"
                        class="group hover:bg-gray-50 dark:hover:bg-zinc-800/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=Alex+Morgan&background=6366f1&color=fff"
                                    class="size-9 rounded-full" alt="">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white capitalize">
                                        {{$user->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-zinc-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-zinc-300 uppercase font-semibold">
                            {{-- Administrator --}}
                            {{ $user->role }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($user->status == 1)
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400">
                                <span class="size-1.5 rounded-full bg-green-600 dark:bg-green-400"></span>
                                Active
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-500/10 dark:text-zinc-400">
                                <span class="size-1.5 rounded-full bg-zinc-600 dark:bg-zinc-400"></span>
                                Inactive
                            </span>
                            @endif
                        </td>
                        <!-- Oct 12, 2023 -->
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-zinc-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <x-ui.button href="{{ route('users.edit', $user->id) }}" variant="soft" size="xs"
                                    wire:navigate
                                    class="p-0 m-0 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </x-ui.button>
                                <x-ui.modal.trigger id="delete-user">
                                    <x-ui.button wire:click="confirmDelete({{ $user->id }})" variant="soft" size="xs"
                                        class="p-0 m-0 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </x-ui.button>
                                </x-ui.modal.trigger>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="group hover:bg-gray-50 dark:hover:bg-zinc-800/40 transition-colors">
                        <td class="p-8" colspan="5">
                            <div class="flex items-center justify-center gap-3">
                                <span class="text-sm text-gray-500 dark:text-zinc-400">No users found.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $this->users->links('custom-pagination-links') }}
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <x-ui.modal id="delete-user" heading="Delete User">
        <div class="space-y-4 text-center">
            <div class="flex gap-2 justify-center items-center">
                <x-ui.icon name="trash" class="size-12 text-red-500" />
            </div>
            <p class="text-sm text-gray-500 dark:text-zinc-400">
                Are you sure you want to delete this user?
                <br>
                This action cannot be undone.
            </p>
            <div class="flex justify-center space-x-3 mt-8">
                <x-ui.button x-on:click="$data.close();" variant="ghost">
                    Cancel
                </x-ui.button>
                <x-ui.button wire:click="deleteUser" x-on:click="$data.close();" variant="danger">
                    Delete User
                </x-ui.button>
            </div>
        </div>
    </x-ui.modal>
</div>