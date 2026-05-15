<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengajuan Surat Akademik UKDW</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* --- Kustomisasi Warna --- */
        .ukdw-green { background-color: #00796B; } /* Teal 700 */
        .ukdw-text-green { color: #00796B; }
        .ukdw-bg-light { background-color: #E0F2F1; } /* Teal 50 */
        .ukdw-text-light { color: #E0F2F1; }

        /* --- Animasi Fade In --- */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Delay untuk Teks Kiri */
        .fade-in-1 { animation-delay: 0.1s; }
        .fade-in-2 { animation-delay: 0.3s; }
        .fade-in-3 { animation-delay: 0.5s; }
        .fade-in-4 { animation-delay: 0.7s; }

        /* Delay untuk Kartu Alur */
        .fade-in-5 { animation-delay: 0.9s; } /* Kartu 1 */
        .fade-in-6 { animation-delay: 1.1s; } /* Kartu 2 */
        .fade-in-7 { animation-delay: 1.3s; } /* Kartu 3 */
        .fade-in-8 { animation-delay: 1.5s; } /* Kartu 4 */

        /* Delay untuk Garis Penghubung */
        .fade-in-line-1 { animation-delay: 1.7s; }
        .fade-in-line-2 { animation-delay: 1.9s; }
        .fade-in-line-3 { animation-delay: 2.1s; }

        /* Delay untuk elemen di Section lain */
        .fade-in-next { animation-delay: 0.2s; }
        .fade-in-next-1 { animation-delay: 0.4s; }
        .fade-in-next-2 { animation-delay: 0.6s; }
        .fade-in-next-3 { animation-delay: 0.8s; }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- AUTO SLIDING BACKGROUND --- */
        .hero-background {
            position: relative;
            overflow: hidden;
        }

        .slider-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .slider-background.active {
            opacity: 1;
        }

        .slider-background::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.7) 0%, rgba(34, 197, 94, 0.6) 100%);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <header class="p-4 border-b border-gray-200 sticky top-0 bg-white z-20 shadow-sm">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('logo-ukdw.png') }}" alt="Logo UKDW" class="h-6 sm:h-8">
                <span class="text-lg sm:text-xl font-bold ukdw-text-green">SiSUA</span>
            </div>
            <nav class="space-x-2 sm:space-x-4 hidden md:flex">
                <a href="#statistik" class="text-gray-600 hover:ukdw-text-green font-medium text-sm sm:text-base">Statistik</a>
                <a href="#alur" class="text-gray-600 hover:ukdw-text-green font-medium text-sm sm:text-base">Alur Pengajuan</a>
                <a href="#fitur" class="text-gray-600 hover:ukdw-text-green font-medium text-sm sm:text-base">Fitur</a>
            </nav>
            <a href="/login" class="ukdw-green text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg font-medium shadow-md hover:bg-teal-800 transition text-sm sm:text-base">
                Masuk
            </a>
        </div>
    </header>
            </a>
        </div>
    </header>
            </a>
        </div>
    </header>

    <main class="hero-background min-h-[calc(100vh-65px)] flex items-center">
        <!-- Auto Sliding Background -->
        <div class="slider-background active" style="background-image: url('/slider-profil-1.jpeg')"></div>
        {{-- <div class="slider-background" style="background-image: url('/slider-seru-1.jpeg')"></div> --}}
        <div class="slider-background" style="background-image: url('/slider-akre-unggul.jpeg')"></div>

        <div class="container mx-auto px-4 py-8 sm:py-16 md:py-0 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 sm:gap-12">

                <div class="md:w-1/2 text-center md:text-left">

                    <span class="fade-in fade-in-1 inline-block bg-white text-teal-700 text-xs sm:text-sm font-semibold px-2 py-1 sm:px-3 sm:py-1 rounded-full mb-3 sm:mb-4 shadow-lg">
                        Sistem Digital Pengajuan Surat
                    </span>

                    <h1 class="fade-in fade-in-2 text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-3 sm:mb-4 drop-shadow-lg">
                        Ajukan Surat Akademik Lebih Cepat, Tanpa Ribet
                    </h1>

                    <p class="fade-in fade-in-3 text-base sm:text-lg md:text-xl text-gray-100 mb-6 sm:mb-8 drop-shadow-md">
                        Layanan terpusat untuk mahasiswa, dekan, dan pejabat di lingkungan UKDW. Cek status surat Anda secara real-time.
                    </p>

                    <div class="fade-in fade-in-4 flex flex-col sm:flex-row justify-center md:justify-start space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="/login" class="ukdw-green text-white px-6 py-3 sm:px-8 sm:py-3 rounded-xl font-bold text-base sm:text-lg shadow-xl hover:bg-teal-800 transition transform hover:scale-105">
                            Ajukan Sekarang
                        </a>
                        <a href="#alur" class="text-gray-700 bg-white border border-gray-300 px-6 py-3 sm:px-8 sm:py-3 rounded-xl font-medium text-base sm:text-lg hover:bg-gray-100 transition">
                            Lihat Alur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Statistics Section -->
    <section id="statistik" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="fade-in text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Kami Membuat Proses Administrasi Lebih Efisien</h2>
                <p class="fade-in fade-in-next text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">Sistem digital yang mengoptimalkan layanan akademik untuk seluruh civitas akademika UKDW</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                <div class="fade-in fade-in-next text-center p-4 sm:p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-teal-600 mb-1 sm:mb-2">4</p>
                    <p class="text-gray-700 font-medium text-sm sm:text-base">Jenis Surat Tersedia</p>
                </div>

                <div class="fade-in fade-in-next-1 text-center p-4 sm:p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-teal-600 mb-1 sm:mb-2">98%</p>
                    <p class="text-gray-700 font-medium text-sm sm:text-base">Tingkat Akurasi Data</p>
                </div>

                <div class="fade-in fade-in-next-2 text-center p-4 sm:p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-teal-600 mb-1 sm:mb-2">24 Jam</p>
                    <p class="text-gray-700 font-medium text-sm sm:text-base">Rata-rata Waktu Proses</p>
                </div>

                <div class="fade-in fade-in-next-3 text-center p-4 sm:p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-teal-600 mb-1 sm:mb-2">500+</p>
                    <p class="text-gray-700 font-medium text-sm sm:text-base">Pengajuan Per Bulan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Flow Section -->
    <section id="alur" class="py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-teal-50 to-blue-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="fade-in text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Langkah Mudah Mengajukan Surat</h2>
                <p class="fade-in fade-in-next text-base sm:text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto">Proses pengajuan surat yang sederhana dan efisien dalam 3 langkah</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 items-center">

                    <div class="fade-in fade-in-next text-center">
                        <div class="relative">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-teal-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                                <span class="text-xl sm:text-2xl font-bold">1</span>
                            </div>
                            <div class="hidden md:block absolute top-8 sm:top-10 left-full w-full h-0.5 bg-teal-200"></div>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Pilih Surat</h3>
                        <p class="text-gray-600 text-sm sm:text-base">Login dan pilih jenis surat yang dibutuhkan dari 4 pilihan yang tersedia.</p>
                    </div>

                    <div class="fade-in fade-in-next-1 text-center">
                        <div class="relative">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-teal-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                                <span class="text-xl sm:text-2xl font-bold">2</span>
                            </div>
                            <div class="hidden md:block absolute top-8 sm:top-10 left-full w-full h-0.5 bg-teal-200"></div>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Isi Data</h3>
                        <p class="text-gray-600 text-sm sm:text-base">Lengkapi detail yang dibutuhkan, data mahasiswa akan otomatis terisi dari sistem.</p>
                    </div>

                    <div class="fade-in fade-in-next-2 text-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-teal-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                            <span class="text-xl sm:text-2xl font-bold">3</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">Surat Selesai</h3>
                        <p class="text-gray-600 text-sm sm:text-base">Surat resmi telah terbit dengan tanda tangan digital dan dapat diunduh langsung.</p>
                    </div>

                </div>

                <div class="fade-in fade-in-next-3 mt-8 sm:mt-12 text-center">
                    <a href="/login" class="inline-flex items-center bg-teal-600 text-white px-6 py-3 sm:px-8 sm:py-4 rounded-xl font-semibold text-base sm:text-lg hover:bg-teal-700 transition transform hover:scale-105 shadow-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Mulai Pengajuan Anda
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="fade-in text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Fitur Unggulan Sistem</h2>
                <p class="fade-in fade-in-next text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">Teknologi modern untuk mendukung efisiensi administrasi akademik</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">

                <div class="fade-in fade-in-next group">
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-6 sm:p-8 rounded-2xl shadow-sm hover:shadow-lg transition duration-300 h-full">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-600 text-white rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Pelacakan Status Real-Time</h3>
                        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Mahasiswa dapat melacak progres surat dari pengajuan hingga persetujuan secara real-time.</p>
                    </div>
                </div>

                <div class="fade-in fade-in-next-1 group">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 sm:p-8 rounded-2xl shadow-sm hover:shadow-lg transition duration-300 h-full">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-600 text-white rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Validasi Cepat oleh Dekan</h3>
                        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Dekan dapat meninjau dan memvalidasi detail surat dengan sistem yang terintegrasi.</p>
                    </div>
                </div>

                <div class="fade-in fade-in-next-2 group">
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 sm:p-8 rounded-2xl shadow-sm hover:shadow-lg transition duration-300 h-full">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-purple-600 text-white rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Tanda Tangan Digital</h3>
                        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Pejabat dapat memberikan persetujuan dan TTD digital secara instan dan aman.</p>
                    </div>
                </div>

                <div class="fade-in fade-in-next-3 group">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 sm:p-8 rounded-2xl shadow-sm hover:shadow-lg transition duration-300 h-full">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-600 text-white rounded-xl flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Download & Arsip Digital</h3>
                        <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Surat dapat diunduh dalam format PDF dan tersimpan otomatis dalam arsip digital.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-r from-teal-600 to-blue-600">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h2 class="fade-in text-xl sm:text-2xl lg:text-3xl font-bold mb-6 sm:mb-8">Dipercaya oleh Civitas Akademika UKDW</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 max-w-4xl mx-auto">
                    <div class="fade-in fade-in-next">
                        <div class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-1 sm:mb-2">1,200+</div>
                        <div class="text-teal-100 text-sm sm:text-base">Mahasiswa Aktif</div>
                    </div>
                    <div class="fade-in fade-in-next-1">
                        <div class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-1 sm:mb-2">50+</div>
                        <div class="text-teal-100 text-sm sm:text-base">Dekan & Pejabat</div>
                    </div>
                    <div class="fade-in fade-in-next-2">
                        <div class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-1 sm:mb-2">99.5%</div>
                        <div class="text-teal-100 text-sm sm:text-base">Kepuasan Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak" class="bg-gray-800 mt-8 sm:mt-16 py-8 sm:py-12 text-white">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">

            <div class="fade-in fade-in-next">
                <img src="{{ asset('logo-ukdw.png') }}" alt="Logo UKDW" class="h-8 sm:h-10 mb-3 sm:mb-4 filter brightness-0 invert">
                <p class="text-sm text-gray-400">SiSUA adalah layanan terpusat untuk efisiensi administrasi surat menyurat akademik di lingkungan UKDW.</p>
            </div>

            <div class="fade-in fade-in-next-1">
                <h3 class="text-base sm:text-lg font-semibold mb-2 sm:mb-3 border-b border-gray-700 pb-1">Navigasi Cepat</h3>
                <ul class="space-y-1 sm:space-y-2 text-sm text-gray-400">
                    <li><a href="#alur" class="hover:ukdw-text-green transition">Alur Pengajuan</a></li>
                    <li><a href="#fitur" class="hover:ukdw-text-green transition">Fitur Unggulan</a></li>
                    <li><a href="/login" class="hover:ukdw-text-green transition">Portal Login</a></li>
                </ul>
            </div>

            <div class="fade-in fade-in-next-2">
                <h3 class="text-base sm:text-lg font-semibold mb-2 sm:mb-3 border-b border-gray-700 pb-1">Kontak & Dukungan</h3>
                <ul class="space-y-1 sm:space-y-2 text-sm text-gray-400">
                    <li><p>Administrasi Akademik (Biro AAK)</p></li>
                    <li><p>Email: aak@ukdw.ac.id</p></li>
                    <li><p>Telepon: (0274) 563929</p></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-6 sm:mt-8 pt-4 sm:pt-6">
            <p class="text-center text-xs sm:text-sm text-gray-500">
                &copy; {{ date('Y') }} Sistem Surat Akademik UKDW. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

    <script>
        // Auto Slider Background
        const slides = document.querySelectorAll('.slider-background');
        let currentSlide = 0;

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        // Change slide every 4 seconds
        setInterval(nextSlide, 4000);
    </script>

</body>
</html>
