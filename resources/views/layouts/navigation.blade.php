<nav x-data="{ open: false }" class="bg-black dark:bg-black-800 border-b border-black-100 dark:border-black-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        {{-- <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" /> --}}
                        <img src="assets/assets/img/spbs.png" alt="" width="50px">

                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    {{-- <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link> --}}
                    <x-nav-link :href="route('inicio')" :active="request()->routeIs('inicio')">
                        spbs
                    </x-nav-link>
                          
                           {{-- @auth --}}

                               @if(Session::get('user')->type=='ADMIN')
                               
                               <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                                    Productos
                                </x-nav-link>
            
                                <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.index')">
                                    Proveedores
                                </x-nav-link>
                                    
                                {{-- <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.index')">
                                    Clientes
                                </x-nav-link>  --}}
                                <x-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')">
                                    Pedidos
                                   </x-nav-link>  

                                   <x-nav-link :href="route('client.index')" :active="request()->routeIs('client.index')">
                                    Clientes
                                   </x-nav-link> 
                                   
                               @endif
                           {{-- @endauth --}}

                            {{-- @auth --}}
                               @if(Session::get('user')->type=='CLIENTE')
                                <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index')">
                                    Pedidos
                                   </x-nav-link>  
                               @endif
                           {{-- @endauth --}}


                           <a href="{{ route('cart.list') }}" class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-red-700">{{ Cart::getTotalQuantity()}}</span> 
                            </a>
                </div>
            </div>
            {{-- @dd(Session::get('user')->name) --}}
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-black dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            @if (Session::get('user') != null)
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ asset(Session::get('user')->image) }}" alt="{{ Session::get('user')->name }} " width="32" />
                            @else
                                <div class="h-8 w-8 rounded-full bg-gray-300"></div>
                            @endif
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-black-100 dark:hover:bg-black-900 focus:outline-none focus:bg-black-100 dark:focus:bg-black-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">

                @if (Auth::user() != null)
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Session::get('user')->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Session::get('user')->email }}</div>
                @else
                <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    LOG IN
                   </x-nav-link>
                @endif
                
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
