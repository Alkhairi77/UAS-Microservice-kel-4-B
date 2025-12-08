<x-layout>
    
    <!-- Carousel Section with overlay text -->
    <section id="home" class="relative h-[700px]">
        <div class="carousel-container absolute inset-0">
            <div class="carousel-slides h-full relative">
                <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                    <img src="assets/img/carousel/1.jpg" alt="Carousel Image 1"
                        class="w-full h-full object-cover object-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent flex items-center">
                        <div class="container mx-auto px-4">
                            <div class="max-w-2xl text-white">
                                <h1 class="text-5xl font-bold mb-4 ml-7">Solusi Limbah Berkelanjutan</h1>
                                <p class="text-xl mb-8 ml-7">Bersama menciptakan lingkungan yang lebih bersih untuk masa
                                    depan yang lebih baik</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                    <img src="assets/img/carousel/2.jpg" alt="Carousel Image 2"
                        class="w-full h-full object-cover object-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent flex items-center">
                        <div class="container mx-auto px-4">
                            <div class="max-w-2xl text-white">
                                <h1 class="text-5xl font-bold mb-4 ml-7">Inovasi Pengolahan Limbah</h1>
                                <p class="text-xl mb-8 ml-7">Teknologi modern untuk pengelolaan limbah yang efisien dan
                                    ramah lingkungan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-500">
                    <img src="assets/img/carousel/5.jpg" alt="Carousel Image 3"
                        class="w-full h-full object-cover object-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent flex items-center">
                        <div class="container mx-auto px-4">
                            <div class="max-w-2xl text-white">
                                <h1 class="text-5xl font-bold mb-4 ml-7">Komitmen Lingkungan</h1>
                                <p class="text-xl mb-8 ml-7">Mendukung pembangunan berkelanjutan melalui pengelolaan
                                    limbah yang bertanggung jawab</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button onclick="moveSlide(-1)"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-4xl z-10 hover:text-[#52B788] transition-colors">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="moveSlide(1)"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-4xl z-10 hover:text-[#52B788] transition-colors">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-10">
            <button onclick="goToSlide(0)" class="w-3 h-3 rounded-full bg-white transition-colors"></button>
            <button onclick="goToSlide(1)" class="w-3 h-3 rounded-full bg-gray-400 transition-colors"></button>
            <button onclick="goToSlide(2)" class="w-3 h-3 rounded-full bg-gray-400 transition-colors"></button>
        </div>
    </section>

    <!-- Quick Overview Section -->
    <section class="py-16 container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-[#042A20] mb-4">Solusi Pengelolaan Limbah Berkelanjutan</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                EcoCycle hadir memberikan solusi komprehensif untuk pengelolaan limbah yang ramah lingkungan
                dan berkelanjutan untuk berbagai jenis organisasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="text-center p-8 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <i class="fas fa-leaf text-5xl text-[#18B17C] mb-6"></i>
                <h3 class="text-xl font-bold mb-4">Ramah Lingkungan</h3>
                <p class="text-gray-600">
                    Menggunakan teknologi dan metode yang meminimalkan dampak terhadap lingkungan
                </p>
            </div>
            <div class="text-center p-8 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <i class="fas fa-chart-line text-5xl text-[#18B17C] mb-6"></i>
                <h3 class="text-xl font-bold mb-4">Efisien & Optimal</h3>
                <p class="text-gray-600">
                    Sistem pengelolaan yang efisien untuk mengoptimalkan biaya dan sumber daya
                </p>
            </div>
            <div class="text-center p-8 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <i class="fas fa-shield-alt text-5xl text-[#18B17C] mb-6"></i>
                <h3 class="text-xl font-bold mb-4">Terpercaya</h3>
                <p class="text-gray-600">
                    Memenuhi standar kualitas dan regulasi yang berlaku dengan pengalaman bertahun-tahun
                </p>
            </div>
        </div>
    </section>

    <!-- Sustainability & Waste Management Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-[#042A20] mb-4">Keberlanjutan dalam Pengelolaan Limbah</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Pendekatan holistik kami memastikan setiap tahap pengelolaan limbah mendukung
                    tujuan keberlanjutan dan pelestarian lingkungan.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h3 class="text-2xl font-bold text-[#042A20] mb-6">Prinsip 3R+ dalam Praktik</h3>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-[#18B17C] rounded-full flex items-center justify-center">
                                <i class="fas fa-recycle text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-[#042A20] mb-2">Reduce (Kurangi)</h4>
                                <p class="text-gray-600">Mengurangi volume limbah dari sumber dengan optimasi proses
                                    produksi dan konsumsi yang lebih efisien.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-[#18B17C] rounded-full flex items-center justify-center">
                                <i class="fas fa-redo text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-[#042A20] mb-2">Reuse (Gunakan Ulang)</h4>
                                <p class="text-gray-600">Memanfaatkan kembali material dan produk untuk mengurangi
                                    kebutuhan sumber daya baru.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-[#18B17C] rounded-full flex items-center justify-center">
                                <i class="fas fa-cogs text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-[#042A20] mb-2">Recycle (Daur Ulang)</h4>
                                <p class="text-gray-600">Mengubah limbah menjadi produk baru melalui proses daur ulang
                                    yang ramah lingkungan.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-[#18B17C] rounded-full flex items-center justify-center">
                                <i class="fas fa-seedling text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-[#042A20] mb-2">Recovery (Pemulihan)</h4>
                                <p class="text-gray-600">Mengambil nilai dari limbah melalui pemulihan energi dan
                                    material yang masih bermanfaat.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <img src="assets/img/visi_misi.png" alt="Sustainability Vision"
                        class="w-full h-64 object-cover rounded-lg mb-6">
                    <h4 class="text-xl font-bold text-[#042A20] mb-4">Visi Keberlanjutan Kami</h4>
                    <p class="text-gray-600 mb-4">
                        Menciptakan ekosistem pengelolaan limbah yang berkelanjutan dengan memadukan
                        teknologi inovatif dan praktik terbaik untuk masa depan yang lebih hijau.
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Zero waste to
                            landfill</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Efficient waste
                            processing</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Circular economy
                            principles</li>
                        <li class="flex items-center"><i class="fas fa-check text-[#18B17C] mr-2"></i>Sustainable
                            resource management</li>
                    </ul>
                </div>
            </div>

            <!-- Waste Management Impact Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="text-center bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-4xl font-bold text-[#18B17C] mb-2">90%</div>
                    <p class="text-gray-600">Tingkat daur ulang</p>
                </div>
                <div class="text-center bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-4xl font-bold text-[#18B17C] mb-2">500+</div>
                    <p class="text-gray-600">Ton limbah dikelola</p>
                </div>
                <div class="text-center bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-4xl font-bold text-[#18B17C] mb-2">100+</div>
                    <p class="text-gray-600">Klien puas</p>
                </div>
            </div>

            <!-- Waste Management Process -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-[#042A20] mb-8 text-center">Proses Pengelolaan Limbah</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-[#18B17C] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-sort text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-[#042A20] mb-3">Pemisahan & Klasifikasi</h4>
                        <p class="text-gray-600">
                            Proses pemisahan limbah berdasarkan jenis dan karakteristik untuk
                            memastikan penanganan yang tepat dan efisien.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-[#18B17C] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-truck text-white text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-[#042A20] mb-3">Pengangkutan & Logistik</h4>
                        <p class="text-gray-600">
                            Sistem pengangkutan yang terkoordinasi dengan rute optimal untuk
                            mengurangi emisi dan biaya transportasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-[#18B17C]">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Mulai Perjalanan Keberlanjutan Anda</h2>
            <p class="text-xl text-white mb-8 max-w-2xl mx-auto">
                Temukan solusi pengelolaan limbah yang tepat untuk organisasi Anda
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/tentang-kami"
                    class="bg-white text-[#18B17C] hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold transition-colors">
                    Pelajari Lebih Lanjut
                </a>
                <a href="/layanan"
                    class="border-2 border-white text-white hover:bg-white hover:text-[#18B17C] px-8 py-3 rounded-lg font-semibold transition-colors">
                    Lihat Layanan
                </a>
            </div>
        </div>
    </section>

</x-layout>