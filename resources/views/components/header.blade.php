<x-ui.layout.header>
  <x-ui.sidebar.toggle class="md:hidden" />
  {{-- lg:flex --}}
  <x-ui.navbar class="flex-1 hidden">
    <x-ui.navbar.item icon="home" label="Home" :href="('/demos/sidebar/home')" />
    <x-ui.navbar.item icon="cog-6-tooth" label="Settings" badge="3" badge:color="orange" badge:variant="outline"
      :href="('/demos/sidebar/settings')" />
    <x-ui.dropdown>
      <x-slot:button>
        <x-ui.navbar.item icon="shopping-bag" icon:variant="min" label="Store" />
      </x-slot:button>

      <x-slot:menu>
        <x-ui.dropdown.item icon="shopping-bag" :href="('/demos/sidebar/shop/products')">
          Products
        </x-ui.dropdown.item>
        <x-ui.dropdown.item icon="receipt-percent" :href="('/demos/sidebar/shop/orders')">
          Orders
        </x-ui.dropdown.item>
        <x-ui.dropdown.item icon="users" :href="('/demos/sidebar/shop/customers')">
          Customers
        </x-ui.dropdown.item>
        <x-ui.dropdown.item icon="ticket" :href="('/demos/sidebar/shop/discounts')">
          Discounts
        </x-ui.dropdown.item>
      </x-slot:menu>
    </x-ui.dropdown>
  </x-ui.navbar>

  <div class="flex ml-auto gap-x-3 items-center">
    <x-ui.dropdown position="bottom-end">
      <x-slot:button class="justify-center">
        <x-ui.avatar size="sm" src="https://ui-avatars.com/api/?name=Alex+Morgan&background=6366f1&color=fff" circle
          alt="Profile Picture" />
      </x-slot:button>

      <x-slot:menu class="w-56">
        <x-ui.dropdown.group label="signed in as">
          <x-ui.dropdown.item>
            bo3aza@gmail.com
          </x-ui.dropdown.item>
        </x-ui.dropdown.group>

        <x-ui.dropdown.separator />

        <x-ui.dropdown.item href="#" wire:navigate.live>
          Account
        </x-ui.dropdown.item>

        <x-ui.dropdown.separator />

        <form action="#" method="post" class="contents">
          @csrf
          <x-ui.dropdown.item as="button" type="submit">
            Sign Out
          </x-ui.dropdown.item>
        </form>

      </x-slot:menu>
    </x-ui.dropdown>

    <x-ui.theme-switcher variant="inline" />
  </div>
</x-ui.layout.header>