<!-- Header -->
<header class="fixed w-full z-50 bg-gradient-to-r from-[#1B4332] to-[#2D6A4F] text-white shadow-lg">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <a href="/">
                    <img src="assets/img/logo7.png" alt="NAIMA Sustainability" class="h-10">
                </a>
                <div class="hidden md:block">
                    <p class="text-sm font-light">Solusi Pengelolaan</p>
                    <p class="text-lg font-semibold -mt-1">Limbah Berkelanjutan</p>
                </div>
            </div>
            <nav class="hidden md:flex items-center space-x-1">
                <a href="/" 
                   class="px-4 py-2 rounded-lg transition-all duration-300 {{ request()->is('/') || request()->is('') ? 'bg-[#40916C]' : 'hover:bg-[#40916C]' }}">
                   Beranda
                </a>
                <a href="/tentang-kami"
                   class="px-4 py-2 rounded-lg transition-all duration-300 {{ request()->is('tentang-kami') ? 'bg-[#40916C]' : 'hover:bg-[#40916C]' }}">
                   Tentang Kami
                </a>
                <a href="/layanan"
                   class="px-4 py-2 rounded-lg transition-all duration-300 {{ request()->is('layanan') ? 'bg-[#40916C]' : 'hover:bg-[#40916C]' }}">
                   Layanan
                </a>
                <a href="{{ route('frontend.artikel.index') }}"
                   class="px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('frontend.artikel.*') ? 'bg-[#40916C]' : 'hover:bg-[#40916C]' }}">
                   Artikel
                </a>
                
                <a href="/login"
                   class="ml-2 bg-[#52B788] hover:bg-[#74C69D] px-6 py-2 rounded-lg transition-all duration-300 font-medium {{ request()->is('login') || request()->is('register') ? 'bg-[#74C69D]' : '' }}">
                   Masuk/Daftar
                </a>
            </nav>
            <!-- Mobile menu button -->
            <button class="md:hidden text-white hover:text-gray-300" id="mobile-menu-btn">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>
    <!-- Mobile menu (hidden by default) -->
    <div class="md:hidden hidden bg-[#2D6A4F] pb-4" id="mobile-menu">
        <nav class="container mx-auto px-4 flex flex-col space-y-2">
            <a href="/" 
               class="px-4 py-2 rounded-lg transition-all duration-300 {{ request()->is('/') || request()->is('') ? 'bg-[#40916C]' : 'hover:bg-[#40916C]' }}">
               Beranda
            </a>
            <a href="/tentang-kami"
               class="px-4 py-2 rounded-lg transition-all duration-300 {{ request()->is('tentang-kami') ? 'bg-[#40916C]' : 'hover:bg-[#40916C]' }}">
               Tentang Kami
            </a>
            <a href="/layanan"
               class="px-4 py-2 rounded-lg transition-all duration-300 {{ request()->is('layanan') ? 'bg-[#40916C]' : 'hover:bg-[#40916C]' }}">
               Layanan
            </a>
            <a href="{{ route('frontend.artikel.index') }}"
               class="px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('frontend.artikel.*') ? 'bg-[#40916C]' : 'hover:bg-[#40916C]' }}">
               Artikel
            </a>
            <a href="/login"
               class="bg-[#52B788] hover:bg-[#74C69D] px-6 py-2 rounded-lg transition-all duration-300 text-center font-medium {{ request()->is('login') || request()->is('register') ? 'bg-[#74C69D]' : '' }}">
               Masuk/Daftar
            </a>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuBtn.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
});
</script>
