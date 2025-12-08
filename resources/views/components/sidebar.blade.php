<!-- Sidebar Mobile -->
<aside
  x-show="isSideMenuOpen"
  x-transition:enter="transition ease-in-out duration-150"
  x-transition:enter-start="opacity-0 -translate-x-20"
  x-transition:enter-end="opacity-100 translate-x-0"
  x-transition:leave="transition ease-in-out duration-150"
  x-transition:leave-start="opacity-100 translate-x-0"
  x-transition:leave-end="opacity-0 -translate-x-20"
  class="fixed inset-y-0 z-20 w-64 h-full flex-shrink-0 overflow-y-auto bg-white dark:bg-gray-900 border-r-2 border-gray-200 dark:border-gray-700 shadow-[4px_0_10px_-3px_rgba(0,0,0,0.1)] dark:shadow-[4px_0_10px_-3px_rgba(0,0,0,0.3)] transition-all duration-300 md:hidden"
>
  <div class="py-4 text-gray-500 dark:text-gray-400">
    <div class="flex items-center justify-between px-4">
      <a class="text-lg font-bold text-green-600 dark:text-green-400" href="#">
        EcoCycle
      </a>
     
      <button @click="closeSideMenu" class="ml-2 p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    
    <ul class="mt-6 space-y-1 px-2">
      @if(auth()->user()->isAdmin())
        @include('components.sidebar-admin')
      @endif
      @if(auth()->user()->isPerusahaan())
        @include('components.sidebar-perusahaan')
      @endif
    </ul>
    
    <ul class="mt-6 px-2">
      <li class="relative px-4 py-3">
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
          href="{{ route('admin.vendor.index') }}">
          <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
            stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
          </svg>
          <span class="ml-4">Vendor</span>
        </a>
      </li>
      <li class="relative px-4 py-3">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <button type="submit"
            class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
              </path>
            </svg>
            <span class="ml-4">Log out</span>
          </button>
        </form>
      </li>
    </ul>
  </div>
</aside>

<!-- Sidebar Desktop -->
<aside
  class="hidden md:flex h-full flex-shrink-0 bg-white dark:bg-gray-900 border-r-2 border-gray-200 dark:border-gray-700 shadow-[4px_0_10px_-3px_rgba(0,0,0,0.1)] dark:shadow-[4px_0_10px_-3px_rgba(0,0,0,0.3)] transition-all duration-300 flex-col"
  :class="{'w-64': !isSidebarCollapsed, 'w-20': isSidebarCollapsed}"
>
  <div class="py-4 text-gray-500 dark:text-gray-400 h-full flex flex-col">
    
    <!-- Logo/Brand -->
    <div class="flex items-center justify-center px-4 mb-6">
      <a class="text-lg font-bold text-green-600 dark:text-green-400 transition-all duration-300" 
         href="@if(auth()->user()->isAdmin()) {{ route('admin.dashboard') }} @elseif(auth()->user()->isPerusahaan()) {{ route('perusahaan.dashboard') }} @else {{ route('profile.edit') }} @endif"
         :class="{'text-center': isSidebarCollapsed}">
        <!-- Logo saat sidebar tidak collapse -->
        <img x-show="!isSidebarCollapsed" x-transition 
            src="{{ asset('assets/img/logo.png') }}" 
            alt="EcoCycle Logo" 
            class=" w-auto" />

        <!-- Logo kecil saat sidebar collapse -->
        <img x-show="isSidebarCollapsed" x-transition 
            src="{{ asset('assets/img/ikon.png') }}" 
            alt="EcoCycle Icon" 
            class="h-8 w-auto" />
      </a>
    </div>
    
    <!-- Navigation Menu -->
    <ul class="space-y-1 px-2 flex-1">
      @if(auth()->user()->isAdmin())
        @include('components.sidebar-admin')
      @endif
      @if(auth()->user()->isPerusahaan())
        @include('components.sidebar-perusahaan')
      @endif
    </ul>
    
    <!-- Bottom Menu -->
    <ul class="px-2 pb-4">
      <li class="relative px-4 py-1">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
          @csrf
          <button type="submit"
            class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200 px-2 py-2"
            :title="isSidebarCollapsed ? 'Log out' : ''">
            <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
              </path>
            </svg>
            <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Log out</span>
          </button>
        </form>
      </li>
    </ul>
  </div>
</aside>

<!-- Backdrop for mobile -->
<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center md:hidden"
  @click="closeSideMenu"></div>
  