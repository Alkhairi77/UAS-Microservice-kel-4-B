<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
  style="{{ request()->routeIs('admin.dashboard') ? '' : 'display:none;' }}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('admin.dashboard') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('admin.dashboard') }}"
    :title="isSidebarCollapsed ? 'Dashboard' : ''">
    <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
      stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Dashboard</span>
  </a>
</li>

<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
  style="{{ request()->routeIs('users.*') ? '' : 'display:none;' }}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold text-gray-500 dark:text-gray-200 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('users.*') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('users.index') }}"
    :title="isSidebarCollapsed ? 'Manajemen User' : ''">
    <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
      stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Manajemen User</span>
  </a>
</li>

<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
  style="{{ request()->routeIs('admin.perusahaan.*') ? '' : 'display:none;' }}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold text-gray-500 dark:text-gray-200 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('admin.perusahaan.*') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('admin.perusahaan.index') }}"
    :title="isSidebarCollapsed ? 'Manajemen Perusahaan' : ''">
    <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
      stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Manajemen Perusahaan</span>
  </a>
</li>

<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
  style="{{ request()->routeIs('jenis-limbah.*') ? '' : 'display:none;' }}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('jenis-limbah.*') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('jenis-limbah.index') }}"
    :title="isSidebarCollapsed ? 'Jenis Limbah' : ''">
    <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
      stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Jenis Limbah</span>
  </a>
</li>

<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
  style="{{ request()->routeIs('vendor*') ? '' : 'display:none;' }}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('vendor.*') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('vendor.index') }}"
    :title="isSidebarCollapsed ? 'Daftar Vendor' : ''">
    <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
      stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Daftar Vendor</span>
  </a>
</li>

<!-- Tambahkan menu laporan harian untuk admin -->
<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
  style="{{ request()->routeIs('laporan-harian.*') ? '' : 'display:none;' }}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('laporan-harian.*') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('laporan-harian.index') }}"
    :title="isSidebarCollapsed ? 'Laporan Harian' : ''">
    <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
      stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Laporan Harian Perusahaan</span>
  </a>
</li>

<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
    style="{{ request()->routeIs('laporan-hasil-pengelolaan.*') ? ''  :'display:none;'}}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('laporan-hasil-pengelolaan.*') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('laporan-hasil-pengelolaan.index') }}">
    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      viewBox="0 0 24 24" stroke="currentColor">
      <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Laporan Hasil Pengolahan</span>
  </a>
</li>

<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
  style="{{ request()->routeIs('kategori-artikel.*') ? '' : 'display:none;' }}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('kategori-artikel.*') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('kategori-artikel.index') }}"
    :title="isSidebarCollapsed ? 'Daftar Vendor' : ''">
    <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
      stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Kategori artikel</span>
  </a>
</li>

<!-- Tambahkan setelah menu kategori-artikel -->
<li class="relative px-4 py-1">
  <span x-cloak class="absolute inset-y-0 left-0 w-1 bg-green-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"
  style="{{ request()->routeIs('admin.artikel.*') ? '' : 'display:none;' }}"></span>
  <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md px-2 py-2 {{ request()->routeIs('admin.artikel.*') ? 'text-green-700 font-bold bg-green-100 dark:bg-green-900' : '' }}"
    href="{{ route('admin.artikel.index') }}"
    :title="isSidebarCollapsed ? 'Artikel' : ''">
    <svg class="w-5 h-5 flex-shrink-0" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
      stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
    </svg>
    <span x-show="!isSidebarCollapsed" x-transition class="ml-4">Artikel</span>
  </a>
</li>

