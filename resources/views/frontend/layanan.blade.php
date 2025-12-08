<x-layout>
    <x-slot:title>Layanan EcoCycle - Pengelolaan Limbah Berkelanjutan</x-slot:title>
    <!-- Hero Section -->
    <section class="pt-24 pb-16 bg-gradient-to-r from-[#1B4332] to-[#2D6A4F] text-white">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">Layanan EcoCycle</h1>
            <p class="text-xl max-w-3xl mx-auto">Platform digital komprehensif untuk pengelolaan limbah berkelanjutan dengan solusi terintegrasi</p>
        </div>
    </section>

    <!-- Main Services Overview -->
    <section class="py-16 container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-[#042A20] mb-4">Solusi Pengelolaan Limbah Digital</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                EcoCycle menghadirkan platform terintegrasi untuk mengelola seluruh aspek pengelolaan limbah 
                dengan teknologi modern dan pendekatan berkelanjutan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            <!-- GRI 306 -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-r from-[#18B17C] to-[#34C78F] p-6 text-white">
                    <div class="flex items-center justify-between">
                        <i class="fas fa-chart-line text-3xl"></i>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">GRI Standard</span>
                    </div>
                    <h3 class="text-xl font-bold mt-4">GRI 306</h3>
                    <p class="text-white/90 mt-2">Pelaporan Standar Limbah</p>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Sistem pelaporan limbah sesuai standar GRI 306 untuk transparansi dan kepatuhan regulasi.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Pelaporan otomatis</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Dashboard real-time</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Analisis tren</li>
                    </ul>
                    <button onclick="showServiceDetail('gri306')" class="w-full mt-4 bg-[#18B17C] text-white py-2 rounded-lg hover:bg-[#15A06B] transition-colors">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
            </div>

            <!-- Jenis Limbah -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-r from-[#2D6A4F] to-[#40916C] p-6 text-white">
                    <div class="flex items-center justify-between">
                        <i class="fas fa-list-alt text-3xl"></i>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">Klasifikasi</span>
                    </div>
                    <h3 class="text-xl font-bold mt-4">Jenis Limbah</h3>
                    <p class="text-white/90 mt-2">Kategorisasi & Identifikasi</p>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Sistem kategorisasi dan identifikasi jenis limbah untuk penanganan yang tepat dan efisien.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Klasifikasi manual</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Database lengkap</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Penanganan khusus</li>
                    </ul>
                    <button onclick="showServiceDetail('jenisLimbah')" class="w-full mt-4 bg-[#2D6A4F] text-white py-2 rounded-lg hover:bg-[#1B4332] transition-colors">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
            </div>

            <!-- Vendor Management -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-r from-[#52B788] to-[#74C69D] p-6 text-white">
                    <div class="flex items-center justify-between">
                        <i class="fas fa-handshake text-3xl"></i>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">Partnership</span>
                    </div>
                    <h3 class="text-xl font-bold mt-4">Vendor</h3>
                    <p class="text-white/90 mt-2">Manajemen Mitra</p>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Platform manajemen vendor dan mitra untuk pengelolaan limbah yang terintegrasi.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Database vendor</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Evaluasi performa</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Kontrak digital</li>
                    </ul>
                    <button onclick="showServiceDetail('vendor')" class="w-full mt-4 bg-[#52B788] text-white py-2 rounded-lg hover:bg-[#40916C] transition-colors">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
            </div>

            <!-- Manajemen Penyimpanan -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-r from-[#0B4D3C] to-[#0F5A47] p-6 text-white">
                    <div class="flex items-center justify-between">
                        <i class="fas fa-warehouse text-3xl"></i>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">Storage</span>
                    </div>
                    <h3 class="text-xl font-bold mt-4">Manajemen Penyimpanan</h3>
                    <p class="text-white/90 mt-2">Sistem Penyimpanan Limbah</p>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Sistem manajemen penyimpanan limbah yang aman, efisien, dan sesuai standar.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Monitoring kapasitas</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Keamanan data</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Optimasi ruang</li>
                    </ul>
                    <button onclick="showServiceDetail('penyimpanan')" class="w-full mt-4 bg-[#0B4D3C] text-white py-2 rounded-lg hover:bg-[#042A20] transition-colors">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
            </div>

            <!-- Pengelolaan Limbah -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-r from-[#34C78F] to-[#18B17C] p-6 text-white">
                    <div class="flex items-center justify-between">
                        <i class="fas fa-recycle text-3xl"></i>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">Processing</span>
                    </div>
                    <h3 class="text-xl font-bold mt-4">Pengelolaan Limbah</h3>
                    <p class="text-white/90 mt-2">End-to-End Solution</p>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Solusi pengelolaan limbah end-to-end dari pengumpulan hingga daur ulang.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Pengumpulan terjadwal</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Pemrosesan manual</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Daur ulang maksimal</li>
                    </ul>
                    <button onclick="showServiceDetail('pengelolaan')" class="w-full mt-4 bg-[#34C78F] text-white py-2 rounded-lg hover:bg-[#2D6A4F] transition-colors">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
            </div>

            <!-- Strategi Bisnis -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="bg-gradient-to-r from-[#1B4332] to-[#2D6A4F] p-6 text-white">
                    <div class="flex items-center justify-between">
                        <i class="fas fa-chess text-3xl"></i>
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">Strategy</span>
                    </div>
                    <h3 class="text-xl font-bold mt-4">Strategi Bisnis</h3>
                    <p class="text-white/90 mt-2">Perencanaan & Konsultasi</p>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">
                        Konsultasi strategi pengelolaan limbah untuk bisnis yang lebih efisien.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Konsultasi kebutuhan</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Perencanaan strategi</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Bantuan implementasi</li>
                    </ul>
                    <button onclick="showServiceDetail('strategi')" class="w-full mt-4 bg-[#1B4332] text-white py-2 rounded-lg hover:bg-[#0B4D3C] transition-colors">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Detail Modal -->
    <div id="serviceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-3xl font-bold text-[#042A20]" id="modalTitle"></h3>
                    <button onclick="closeServiceModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="modalContent" class="space-y-6">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Features -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-[#042A20] mb-4">Fitur Layanan EcoCycle</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Layanan pengelolaan limbah yang terorganisir dan mudah diakses
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <div class="text-center bg-white p-6 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-[#18B17C] rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#042A20] mb-2">Akses Mudah</h3>
                    <p class="text-gray-600 text-sm">Akses layanan dari mana saja dengan website yang responsif</p>
                </div>

                <div class="text-center bg-white p-6 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-[#18B17C] rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#042A20] mb-2">Laporan</h3>
                    <p class="text-gray-600 text-sm">Laporan berkala untuk monitoring pengelolaan limbah</p>
                </div>

                <div class="text-center bg-white p-6 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-[#18B17C] rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#042A20] mb-2">Keamanan</h3>
                    <p class="text-gray-600 text-sm">Sistem keamanan standar untuk melindungi data</p>
                </div>

                <div class="text-center bg-white p-6 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-[#18B17C] rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sync text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#042A20] mb-2">Koordinasi</h3>
                    <p class="text-gray-600 text-sm">Koordinasi dengan vendor dan mitra pengelolaan limbah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-[#18B17C]">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Mulai Layanan Pengelolaan Limbah</h2>
            <p class="text-xl text-white mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan organisasi yang telah mempercayai EcoCycle untuk layanan pengelolaan limbah
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/login" 
                   class="bg-white text-[#18B17C] hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold transition-colors">
                    Daftar Sekarang
                </a>
                <a href="/tentang-kami" 
                   class="border-2 border-white text-white hover:bg-white hover:text-[#18B17C] px-8 py-3 rounded-lg font-semibold transition-colors">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>

    <script>
        const serviceDetails = {
            gri306: {
                title: "GRI 306 - Pelaporan Standar Limbah",
                content: `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-[#18B17C] to-[#34C78F] p-6 rounded-lg text-white">
                            <h4 class="text-xl font-bold mb-2">Pelaporan Standar</h4>
                            <p>Pelaporan pengelolaan limbah sesuai standar yang berlaku</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#18B17C] mb-3">Layanan</h5>
                                <ul class="space-y-2 text-sm">
                                    <li>• Pelaporan sesuai standar</li>
                                    <li>• Dokumentasi lengkap</li>
                                    <li>• Laporan berkala</li>
                                    <li>• Bantuan dokumen</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#18B17C] mb-3">Manfaat</h5>
                                <ul class="space-y-2 text-sm">
                                    <li>• Kepatuhan regulasi</li>
                                    <li>• Dokumentasi terorganisir</li>
                                    <li>• Transparansi</li>
                                    <li>• Audit trail</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                `
            },
            jenisLimbah: {
                title: "Klasifikasi Jenis Limbah",
                content: `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-[#2D6A4F] to-[#40916C] p-6 rounded-lg text-white">
                            <h4 class="text-xl font-bold mb-2">Klasifikasi Limbah</h4>
                            <p>Identifikasi dan kategorisasi jenis limbah untuk penanganan yang tepat</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#2D6A4F] mb-3">Limbah Organik</h5>
                                <p class="text-sm text-gray-600">Sisa makanan, daun, kayu, dan material biodegradable lainnya</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#2D6A4F] mb-3">Limbah Anorganik</h5>
                                <p class="text-sm text-gray-600">Plastik, logam, kaca, dan material non-biodegradable</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#2D6A4F] mb-3">Limbah B3</h5>
                                <p class="text-sm text-gray-600">Limbah berbahaya dan beracun dengan penanganan khusus</p>
                            </div>
                        </div>
                        
                    </div>
                `
            },
            vendor: {
                title: "Manajemen Vendor & Mitra",
                content: `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-[#52B788] to-[#74C69D] p-6 rounded-lg text-white">
                            <h4 class="text-xl font-bold mb-2">Manajemen Vendor</h4>
                            <p>Kelola mitra dan vendor pengelolaan limbah dengan sistem yang terorganisir</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#52B788] mb-3">Data Vendor</h5>
                                <ul class="space-y-2 text-sm">
                                    <li>• Profil vendor</li>
                                    <li>• Sertifikasi</li>
                                    <li>• Riwayat layanan</li>
                                    <li>• Kapasitas</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#52B788] mb-3">Monitoring</h5>
                                <ul class="space-y-2 text-sm">
                                    <li>• Evaluasi vendor</li>
                                    <li>• Laporan layanan</li>
                                    <li>• Kontrak</li>
                                    <li>• Pembayaran</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h5 class="font-bold text-[#52B788] mb-3">Manfaat</h5>
                            <p class="text-sm text-gray-600">Memudahkan pengelolaan vendor dan memastikan layanan pengelolaan limbah yang berkualitas</p>
                        </div>
                    </div>
                `
            },
            penyimpanan: {
                title: "Manajemen Penyimpanan Limbah",
                content: `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-[#0B4D3C] to-[#0F5A47] p-6 rounded-lg text-white">
                            <h4 class="text-xl font-bold mb-2">Sistem Penyimpanan Limbah</h4>
                            <p>Kelola penyimpanan limbah dengan sistem yang terorganisir dan sesuai standar</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#0B4D3C] mb-3">Monitoring Kapasitas</h5>
                                <ul class="space-y-2 text-sm">
                                    <li>• Pencatatan kapasitas penyimpanan</li>
                                    <li>• Notifikasi saat penuh</li>
                                    <li>• Jadwal pengangkutan teratur</li>
                                    <li>• Dokumentasi penyimpanan</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#0B4D3C] mb-3">Keamanan & Compliance</h5>
                                <ul class="space-y-2 text-sm">
                                    <li>• Standar keamanan yang berlaku</li>
                                    <li>• Kepatuhan regulasi</li>
                                    <li>• Dokumentasi lengkap</li>
                                    <li>• Protokol keselamatan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                `
            },
            pengelolaan: {
                title: "Pengelolaan Limbah End-to-End",
                content: `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-[#34C78F] to-[#18B17C] p-6 rounded-lg text-white">
                            <h4 class="text-xl font-bold mb-2">Layanan Lengkap</h4>
                            <p>Dari pengumpulan hingga penanganan, layanan pengelolaan limbah yang komprehensif</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <div class="w-12 h-12 bg-[#34C78F] rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-truck text-white"></i>
                                </div>
                                <h5 class="font-bold text-[#34C78F] mb-2">Pengumpulan</h5>
                                <p class="text-sm text-gray-600">Pengumpulan terjadwal sesuai kebutuhan</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <div class="w-12 h-12 bg-[#34C78F] rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-cogs text-white"></i>
                                </div>
                                <h5 class="font-bold text-[#34C78F] mb-2">Pemrosesan</h5>
                                <p class="text-sm text-gray-600">Pemrosesan sesuai jenis limbah</p>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <div class="w-12 h-12 bg-[#34C78F] rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-recycle text-white"></i>
                                </div>
                                <h5 class="font-bold text-[#34C78F] mb-2">Daur Ulang</h5>
                                <p class="text-sm text-gray-600">Maksimalkan nilai dari limbah melalui daur ulang</p>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h5 class="font-bold text-[#34C78F] mb-3">Keunggulan Layanan</h5>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <li>• Dokumentasi proses lengkap</li>
                                <li>• Efisiensi biaya operasional</li>
                                <li>• Pengurangan dampak lingkungan</li>
                                <li>• Laporan berkala</li>
                            </ul>
                        </div>
                    </div>
                `
            },
            strategi: {
                title: "Strategi Bisnis",
                content: `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-[#1B4332] to-[#2D6A4F] p-6 rounded-lg text-white">
                            <h4 class="text-xl font-bold mb-2">Strategi Pengelolaan Limbah</h4>
                            <p>Konsultasi dan perencanaan strategi pengelolaan limbah untuk bisnis</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#1B4332] mb-3">Konsultasi</h5>
                                <ul class="space-y-2 text-sm">
                                    <li>• Analisis kebutuhan limbah</li>
                                    <li>• Perencanaan strategi</li>
                                    <li>• Konsultasi regulasi</li>
                                    <li>• Bantuan dokumen</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h5 class="font-bold text-[#1B4332] mb-3">Implementasi</h5>
                                <ul class="space-y-2 text-sm">
                                    <li>• Pendampingan proses</li>
                                    <li>• Pengumpulan limbah</li>
                                    <li>• Pengangkutan limbah</li>
                                    <li>• Dokumentasi</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h5 class="font-bold text-[#1B4332] mb-3">Manfaat Layanan</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-[#1B4332] mb-1">Efisien</div>
                                    <p class="text-gray-600">Pengelolaan limbah yang terorganisir</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-[#1B4332] mb-1">Terpercaya</div>
                                    <p class="text-gray-600">Layanan yang konsisten dan berkualitas</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-[#1B4332] mb-1">Berkelanjutan</div>
                                    <p class="text-gray-600">Mendukung lingkungan yang lebih baik</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `
            }
        };

        function showServiceDetail(serviceId) {
            const service = serviceDetails[serviceId];
            if (service) {
                document.getElementById('modalTitle').textContent = service.title;
                document.getElementById('modalContent').innerHTML = service.content;
                document.getElementById('serviceModal').classList.remove('hidden');
            }
        }

        function closeServiceModal() {
            document.getElementById('serviceModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('serviceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeServiceModal();
            }
        });
    </script>
</x-layout> 