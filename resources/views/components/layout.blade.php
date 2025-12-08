<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'EcoCycle-Naima Sustainability' }}</title>
    <link rel="shorcut icon" href="assets/img/icon.jpeg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .carousel-container {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .service-card {
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        * {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-white text-gray-900">
    <!-- Header -->
    <x-front-header />

    {{ $slot }}

    <!-- Footer -->
    <footer class="bg-[#063C2E] text-white py-12">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <a href="/" class="inline-block">
                    <img src="assets/img/logo2.png" alt="NAIMA Sustainability" class="h-12 mb-6 hover:opacity-80 transition-opacity cursor-pointer">
                </a>
                <p class="text-base leading-relaxed text-gray-200 font-light">Kami hadir untuk yang telah atau akan menerapkan pengelolaan perusahaan saat
                    memulai, ingin memperbaiki, hingga ingin mencapai level yang lebih tinggi dalam aspek keberlanjutan.
                </p>
                <div class="flex gap-6 mt-6">
                    <a href="#" class="text-white hover:text-gray-300 transition-colors text-2xl"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white hover:text-gray-300 transition-colors text-2xl"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div>
                <h3 class="font-bold text-xl mb-6 text-white">Alamat Kantor</h3>
                <div class="space-y-4 text-base text-gray-200">
                    <p class="leading-relaxed">Mustang No.14a, Dadok Tunggul Hitam, <span class="font-semibold text-white">Kec. Koto Tangah, Kota Padang, Sumatera
                        Barat 25586</span></p>
                    <p><span class="font-semibold text-white">Email:</span><br>naima.sustainability@gmail.com</p>
                    <p><span class="font-semibold text-white">Telepon:</span><br>+62 823 4567 890</p>
                </div>
            </div>
        </div>
        <div class="container mx-auto px-4 mt-8 pt-8 border-t border-white/20 text-center">
            <p class="text-sm text-gray-300 font-light">© {{ date('Y') }} Naima. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Add carousel JavaScript before closing body tag -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.bottom-8 button');

        // Show initial slide
        showSlide(currentSlide);

        // Auto advance slides every 5 seconds
        setInterval(() => moveSlide(1), 5000);

        function showSlide(n) {
            // Hide all slides
            slides.forEach(slide => slide.style.opacity = '0');
            dots.forEach(dot => dot.classList.replace('bg-white', 'bg-gray-400'));

            // Show current slide
            slides[n].style.opacity = '1';
            dots[n].classList.replace('bg-gray-400', 'bg-white');
        }

        function moveSlide(direction) {
            currentSlide = (currentSlide + direction + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        function goToSlide(n) {
            currentSlide = n;
            showSlide(currentSlide);
        }
    </script>
</body>

</html>