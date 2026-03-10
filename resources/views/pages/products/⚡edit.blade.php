<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Product;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Title('Edit Product')] class extends Component
{
    use WithFileUploads;

    public Product $product;
    #[Validate('nullable')]
    #[Validate('image', message: 'Image is invalid')]
    #[Validate('max:2048', message: 'Image size is too large')]
    #[Validate('mimes:jpeg,jpg,png', message: 'Image format is invalid')]
    public $image;
    public $existingImage;
    #[Validate('required', message: 'Product name is required')]
    #[Validate('min:3', message: 'Product name must be at least 3 characters')]
    public $name;
    #[Validate('required', message: 'Price is required')]
    public $price;
    #[Validate('required', message: 'Stock is required')]
    public $stock;
    #[Validate('required', message: 'Status is required')]
    #[Validate('in:0,1', message: 'Status is invalid')]
    public $status;
    #[Validate('nullable')]
    #[Validate('min:3', message: 'Description must be at least 3 characters')]
    public $description;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->existingImage = $product->image;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->status = (string) $product->status;
        $this->description = $product->description;
    }

    public function update()
    {
        // validate
        $validated = $this->validate();

        // Format price: hilangkan titik/pemisah ribuan agar menjadi angka murni
        $validated['price'] = str_replace('.', '', $this->price);

        // image
        if ($this->image && !is_string($this->image)) {
            // Hapus gambar lama dari storage jika ada
            if ($this->product->image) {
                Storage::disk('public')->delete($this->product->image);
            }

            // Simpan gambar baru ke folder 'products' di disk 'public'
            $validated['image'] = $this->image->store('images/products', 'public');
        } else {
            // Jika tidak ada upload baru, gunakan gambar yang sudah ada
            $validated['image'] = $this->product->image;
        }
        // update product
        $this->product->update($validated);
        // notify user
        session()->flash('notify', [
            'content' => 'Product updated successfully',
            'type' => 'success',
        ]);
        // redirect to index
        return $this->redirect(route('products.index'), navigate: true);
    }
};
?>

<div class="space-y-4">
    <!-- Header -->
    <div class="lg:flex lg:items-center lg:justify-between">
        <h1 class="text-2xl font-bold tracking-tight mb-4 lg:mb-0">Edit Product</h1>
    </div>
    <!-- Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form wire:submit="update">
                <x-ui.fieldset>
                    <x-ui.field required>
                        <x-ui.label>Product Name</x-ui.label>
                        <x-ui.input wire:model="name" placeholder="Cat Choise Kitten" />
                        <x-ui.error name="name" />
                    </x-ui.field>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
                        <x-ui.field required>
                            <x-ui.label>Price</x-ui.label>
                            <x-ui.input x-mask:dynamic="$money($input, ',', '.', 0)" wire:model="price"
                                placeholder="10.000" />
                            <x-ui.error name="price" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Thumbnail</x-ui.label>
                            <x-ui.input type="file" wire:model="image" />
                            <x-ui.error name="image" />
                        </x-ui.field>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <x-ui.field required>
                            <x-ui.label>Stock</x-ui.label>
                            <x-ui.input type="number" wire:model="stock" placeholder="10" />
                            <x-ui.error name="stock" />
                        </x-ui.field>

                        <x-ui.field required>
                            <x-ui.label>Status</x-ui.label>
                            <x-ui.select invalid="{{ $errors->has('status') ? '1' : '0' }}" wire:model="status">
                                <x-ui.select.option value="1">Publish</x-ui.select.option>
                                <x-ui.select.option value="0">Draft</x-ui.select.option>
                            </x-ui.select>
                            <x-ui.error name="status" />
                        </x-ui.field>
                    </div>

                    <x-ui.field>
                        <x-ui.label>Description</x-ui.label>
                        <x-ui.textarea wire:model="description" placeholder="Enter your description..." />
                    </x-ui.field>

                    <div class="flex justify-end space-x-3 mt-8">
                        <x-ui.button href="{{ route('products.index') }}" wire:navigate variant="secondary"
                            class="w-full md:w-auto">Back
                        </x-ui.button>
                        <x-ui.button type="submit" class="w-full md:w-auto">Update Product</x-ui.button>
                    </div>
                </x-ui.fieldset>
            </form>
        </div>
        <!-- Preview Thumbnail -->
        <div class="space-y-4">
            <x-ui.label>Image Preview</x-ui.label>
            <!-- Container untuk preview gambar -->
            <div
                class="relative flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 min-h-64">

                <div class="flex flex-col items-center justify-center w-full p-4">
                    @if ($image && !is_string($image))
                    <!-- Preview jika sedang upload file baru -->
                    <img src="{{ $image->temporaryUrl() }}"
                        class="rounded-lg object-cover w-full h-auto max-h-64 shadow-sm">
                    <button type="button" wire:click="$set('image', null)"
                        class="mt-2 text-xs text-red-600 hover:underline">Batal Upload</button>

                    @elseif ($existingImage)
                    <!-- Tampilkan gambar lama jika belum ada upload baru -->
                    <img src="{{ asset('storage/' . $existingImage) }}"
                        class="rounded-lg object-cover w-full h-auto max-h-64 shadow-sm">
                    <p class="mt-2 text-xs text-gray-500">Gambar Saat Ini</p>

                    @else
                    <!-- Placeholder jika kosong sama sekali -->
                    <div class="text-center text-gray-400">
                        <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-sm">Belum ada foto</p>
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