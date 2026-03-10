<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Store;
use Livewire\WithFileUploads;

new #[Title('Create Store')] class extends Component
{
    use WithFileUploads;

    #[Validate('nullable')]
    #[Validate('image', message: 'Image is invalid')]
    #[Validate('max:2048', message: 'Image size is too large')]
    #[Validate('mimes:jpeg,jpg,png', message: 'Image format is invalid')]
    public $image;
    #[Validate('required', message: 'Store name is required')]
    #[Validate('min:3', message: 'Store name must be at least 3 characters')]
    public $name;
    #[Validate('required', message: 'Platform is required')]
    public $platform = 'shopee';
    #[Validate('nullable')]
    public $admin_fee;
    #[Validate('nullable')]
    public $extra_promo_fee;
    #[Validate('nullable')]
    public $handling_fee;

    public function create()
    {
        // Validate
        $validated = $this->validate();
        // user_id
        // $validated['user_id'] = 1;
        // image
        if ($this->image) {
            $validated['image'] = $this->image->store('images/stores', 'public');
        }
        // create store
        Store::create($validated);
        // notify store
        session()->flash('notify', [
            'content' => 'Store created successfully',
            'type' => 'success',
        ]);
        // redirect to index
        return $this->redirect(route('stores.index'), navigate: true);
    }
};
?>

<div class="space-y-4">
    <!-- Header -->
    <div class="lg:flex lg:items-center lg:justify-between">
        <h1 class="text-2xl font-bold tracking-tight mb-4 lg:mb-0">Create Store</h1>
    </div>
    <!-- Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form wire:submit="create">
                <x-ui.fieldset>
                    <x-ui.field required>
                        <x-ui.label>Store Name</x-ui.label>
                        <x-ui.input wire:model="name" placeholder="My Store" />
                        <x-ui.error name="name" />
                    </x-ui.field>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
                        <x-ui.field required>
                            <x-ui.label>Platform</x-ui.label>
                            <x-ui.select invalid="{{ $errors->has('platform') ? '1' : '0' }}" wire:model="platform">
                                <x-ui.select.option value="shopee">Shopee</x-ui.select.option>
                                <x-ui.select.option value="lazada">Lazada</x-ui.select.option>
                                <x-ui.select.option value="tiktok">Tiktok Shop</x-ui.select.option>
                            </x-ui.select>
                            <x-ui.error name="platform" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Logo Store</x-ui.label>
                            <x-ui.input type="file" wire:model="image" />
                            <x-ui.error name="image" />
                        </x-ui.field>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                        <x-ui.field>
                            <x-ui.label>Admin Fee %</x-ui.label>
                            <x-ui.input type="number" wire:model="admin_fee" placeholder="10" />
                            <x-ui.error name="admin_fee" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Extra Promo Fee %</x-ui.label>
                            <x-ui.input type="number" wire:model="extra_promo_fee" placeholder="9" />
                            <x-ui.error name="extra_promo_fee" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Handling Fee (rp)</x-ui.label>
                            <x-ui.input type="number" wire:model="handling_fee" placeholder="1250" />
                            <x-ui.error name="handling_fee" />
                        </x-ui.field>
                    </div>

                    <div class="flex justify-end space-x-3 mt-8">
                        <x-ui.button href="{{ route('stores.index') }}" wire:navigate variant="secondary"
                            class="w-full md:w-auto">Back
                        </x-ui.button>
                        <x-ui.button type="submit" class="w-full md:w-auto">Create Store</x-ui.button>
                    </div>
                </x-ui.fieldset>
            </form>
        </div>
        <!-- Preview Thumbnail -->
        <div class="space-y-4">
            <x-ui.label>Logo Preview</x-ui.label>
            <!-- Container untuk preview logo -->
            <div
                class="relative flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 min-h-64">
                <div class="flex flex-col items-center justify-center w-full p-4">
                    @if ($image)
                    <img src="{{ $image->temporaryUrl() }}"
                        class="rounded-lg object-cover w-full h-auto max-h-64 shadow-sm">
                    <button type="button" wire:click="$set('image', null)"
                        class="mt-2 text-xs text-red-600 hover:underline">Hapus Logo</button>
                    @else
                    <div class="text-center text-gray-400">
                        <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm">Belum ada logo</p>
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