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
                <img src="logo.png" alt="Logo UKDW" class="h-8">
                <span class="text-xl font-bold ukdw-text-green">SiSUA</span>
            </div>
            <nav class="space-x-4 hidden md:flex">
                <a href="#statistik" class="text-gray-600 hover:ukdw-text-green font-medium">Statistik</a>
                <a href="#alur" class="text-gray-600 hover:ukdw-text-green font-medium">Alur Pengajuan</a>
                <a href="#fitur" class="text-gray-600 hover:ukdw-text-green font-medium">Fitur</a>
            </nav>
            <a href="/login" class="ukdw-green text-white px-4 py-2 rounded-lg font-medium shadow-md hover:bg-teal-800 transition">
                Masuk
            </a>
        </div>
    </header>

    <main class="hero-background min-h-[calc(100vh-65px)] flex items-center"> 
        <!-- Auto Sliding Background -->
        <div class="slider-background active" style="background-image: url('/slider-profil-1.jpeg')"></div>
        {{-- <div class="slider-background" style="background-image: url('/slider-seru-1.jpeg')"></div> --}}
        <div class="slider-background" style="background-image: url('/slider-akre-unggul.jpeg')"></div>
        
        <div class="container mx-auto px-4 py-16 md:py-0 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                
                <div class="md:w-1/2 text-center md:text-left">
                    
                    <span class="fade-in fade-in-1 inline-block bg-white text-teal-700 text-sm font-semibold px-3 py-1 rounded-full mb-4 shadow-lg">
                        Sistem Digital Pengajuan Surat
                    </span>
                    
                    <h1 class="fade-in fade-in-2 text-4xl md:text-5xl font-extrabold text-white leading-tight mb-4 drop-shadow-lg">
                        Ajukan Surat Akademik Lebih Cepat, Tanpa Ribet
                    </h1>
                    
                    <p class="fade-in fade-in-3 text-xl text-gray-100 mb-8 drop-shadow-md">
                        Layanan terpusat untuk mahasiswa, staff, dan pejabat di lingkungan UKDW. Cek status surat Anda secara real-time.
                    </p>
                    
                    <div class="fade-in fade-in-4 flex justify-center md:justify-start space-x-4">
                        <a href="/login" class="ukdw-green text-white px-8 py-3 rounded-xl font-bold text-lg shadow-xl hover:bg-teal-800 transition transform hover:scale-105">
                            Ajukan Sekarang
                        </a>
                        <a href="#alur" class="text-gray-700 bg-white border border-gray-300 px-8 py-3 rounded-xl font-medium text-lg hover:bg-gray-100 transition">
                            Lihat Alur
                        </a>
                    </div>
                </div>

                {{-- <!-- Bagian Kanan: Visual Alur Pengajuan -->
                <div class="md:w-1/2 mt-10 md:mt-0 relative">
                    <!-- Container untuk semua kartu dengan garis -->
                    <div class="relative h-[500px]">
                        
                        <!-- Kartu 1: Formulir Pengajuan (Mahasiswa) -->
                        <div id="card-1" class="fade-in fade-in-5 absolute top-0 left-1/2 transform -translate-x-1/2 w-[90%] max-w-md bg-white rounded-xl shadow-xl p-5 transition duration-500 hover:shadow-2xl z-10">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="w-10 h-10 ukdw-bg-light rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 ukdw-text-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </span>
                                    <h4 class="text-lg font-bold text-gray-800">1. Formulir Pengajuan</h4>
                                </div>
                                <span class="text-sm font-semibold px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full">Mahasiswa</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Status: <span class="text-yellow-600 font-semibold">Drafting</span></p>
                            <p class="text-xs text-gray-500">Mahasiswa mengisi data dan melampirkan berkas yang dibutuhkan.</p>
                        </div>

                        <!-- Garis 1: Dari Kartu 1 ke Kartu 2 -->
                        <div class="fade-in fade-in-line-1 absolute top-[110px] left-1/2 transform -translate-x-1/2 w-px h-[60px] border-r-2 border-dashed border-gray-400"></div>
                        
                        <!-- Kartu 2: Validasi Staff Jurusan -->
                        <div id="card-2" class="fade-in fade-in-6 absolute top-[170px] left-1/2 transform -translate-x-1/2 w-[90%] max-w-md bg-white rounded-xl shadow-xl p-5 transition duration-500 hover:shadow-2xl z-10">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="w-10 h-10 ukdw-bg-light rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 ukdw-text-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M16 16h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </span>
                                    <h4 class="text-lg font-bold text-gray-800">2. Validasi Staff Jurusan</h4>
                                </div>
                                <span class="text-sm font-semibold px-3 py-1 bg-blue-100 text-blue-800 rounded-full">Staff</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Status: <span class="text-blue-600 font-semibold">In Review</span></p>
                            <p class="text-xs text-gray-500">Staff memeriksa kelengkapan dan keabsahan data akademik mahasiswa.</p>
                        </div>

                        <!-- Garis 2: Dari Kartu 2 ke Kartu 3 -->
                        <div class="fade-in fade-in-line-2 absolute top-[280px] left-1/2 transform -translate-x-1/2 w-px h-[60px] border-r-2 border-dashed border-gray-400"></div>
                        
                        <!-- Kartu 3: Persetujuan Pejabat -->
                        <div id="card-3" class="fade-in fade-in-7 absolute top-[340px] left-1/2 transform -translate-x-1/2 w-[90%] max-w-md bg-white rounded-xl shadow-xl p-5 transition duration-500 hover:shadow-2xl border-l-4 border-l-ukdw-green z-10">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="w-10 h-10 ukdw-green text-white rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </span>
                                    <h4 class="text-lg font-bold ukdw-text-green">3. Persetujuan Pejabat</h4>
                                </div>
                                <span class="text-sm font-semibold px-3 py-1 bg-green-100 text-green-800 rounded-full">Pejabat</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Status: <span class="ukdw-text-green font-semibold">Approved</span></p>
                            <p class="text-xs text-gray-500">Pejabat (Kaprodi/Dekan) memberikan Tanda Tangan Digital (TTD).</p>
                        </div>

                        <!-- Garis 3: Dari Kartu 3 ke Kartu 4 -->
                        <div class="fade-in fade-in-line-3 absolute top-[450px] left-1/2 transform -translate-x-1/2 w-px h-[60px] border-r-2 border-dashed border-gray-400"></div>
                        
                        <!-- Kartu 4: Surat Tersedia -->
                        <div id="card-4" class="fade-in fade-in-8 absolute top-[510px] left-1/2 transform -translate-x-1/2 w-[90%] max-w-md bg-white rounded-xl shadow-xl p-5 transition duration-500 hover:shadow-2xl z-10">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="w-10 h-10 ukdw-bg-light rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 ukdw-text-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </span>
                                    <h4 class="text-lg font-bold text-gray-800">4. Surat Tersedia</h4>
                                </div>
                                <span class="text-sm font-semibold px-3 py-1 bg-gray-100 text-gray-800 rounded-full">Hasil</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Status: <span class="text-gray-700 font-semibold">Selesai</span></p>
                            <p class="text-xs text-gray-500">Surat resmi telah terbit dan dapat diunduh oleh mahasiswa.</p>
                        </div>
                        
                    </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </main>
    
    <!-- Statistics Section -->
    <section id="statistik" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="fade-in text-4xl font-bold text-gray-900 mb-4">Kami Membuat Proses Administrasi Lebih Efisien</h2>
                <p class="fade-in fade-in-next text-xl text-gray-600 max-w-3xl mx-auto">Sistem digital yang mengoptimalkan layanan akademik untuk seluruh civitas akademika UKDW</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="fade-in fade-in-next text-center p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-teal-600 mb-2">4</p>
                    <p class="text-gray-700 font-medium">Jenis Surat Tersedia</p>
                </div>

                <div class="fade-in fade-in-next-1 text-center p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-teal-600 mb-2">98%</p>
                    <p class="text-gray-700 font-medium">Tingkat Akurasi Data</p>
                </div>

                <div class="fade-in fade-in-next-2 text-center p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-teal-600 mb-2">24 Jam</p>
                    <p class="text-gray-700 font-medium">Rata-rata Waktu Proses</p>
                </div>

                <div class="fade-in fade-in-next-3 text-center p-6 bg-gray-50 rounded-xl hover:bg-teal-50 transition duration-300">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-teal-600 mb-2">500+</p>
                    <p class="text-gray-700 font-medium">Pengajuan Per Bulan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Flow Section -->
    <section id="alur" class="py-20 bg-gradient-to-br from-teal-50 to-blue-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="fade-in text-4xl font-bold text-gray-900 mb-4">Langkah Mudah Mengajukan Surat</h2>
                <p class="fade-in fade-in-next text-xl text-gray-600 max-w-2xl mx-auto">Proses pengajuan surat yang sederhana dan efisien dalam 3 langkah</p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    
                    <div class="fade-in fade-in-next text-center">
                        <div class="relative">
                            <div class="w-20 h-20 bg-teal-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <span class="text-2xl font-bold">1</span>
                            </div>
                            <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-teal-200"></div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Pilih Surat</h3>
                        <p class="text-gray-600">Login dan pilih jenis surat yang dibutuhkan dari 4 pilihan yang tersedia.</p>
                    </div>
                    
                    <div class="fade-in fade-in-next-1 text-center">
                        <div class="relative">
                            <div class="w-20 h-20 bg-teal-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <span class="text-2xl font-bold">2</span>
                            </div>
                            <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-teal-200"></div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Isi Data</h3>
                        <p class="text-gray-600">Lengkapi detail yang dibutuhkan, data mahasiswa akan otomatis terisi dari sistem.</p>
                    </div>

                    <div class="fade-in fade-in-next-2 text-center">
                        <div class="w-20 h-20 bg-teal-600 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <span class="text-2xl font-bold">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Surat Selesai</h3>
                        <p class="text-gray-600">Surat resmi telah terbit dengan tanda tangan digital dan dapat diunduh langsung.</p>
                    </div>
                    
                </div>
                
                <div class="fade-in fade-in-next-3 mt-12 text-center">
                    <a href="/login" class="inline-flex items-center bg-teal-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-teal-700 transition transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Mulai Pengajuan Anda
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="fade-in text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan Sistem</h2>
                <p class="fade-in fade-in-next text-xl text-gray-600 max-w-3xl mx-auto">Teknologi modern untuk mendukung efisiensi administrasi akademik</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <div class="fade-in fade-in-next group">
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-8 rounded-2xl shadow-sm hover:shadow-lg transition duration-300 h-full">
                        <div class="w-16 h-16 bg-teal-600 text-white rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Pelacakan Status Real-Time</h3>
                        <p class="text-gray-600 leading-relaxed">Mahasiswa dapat melacak progres surat dari pengajuan hingga persetujuan secara real-time.</p>
                    </div>
                </div>
                
                <div class="fade-in fade-in-next-1 group">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl shadow-sm hover:shadow-lg transition duration-300 h-full">
                        <div class="w-16 h-16 bg-blue-600 text-white rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Validasi Cepat oleh Staff</h3>
                        <p class="text-gray-600 leading-relaxed">Staff Jurusan dapat meninjau dan memvalidasi detail surat dengan sistem yang terintegrasi.</p>
                    </div>
                </div>
                
                <div class="fade-in fade-in-next-2 group">
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl shadow-sm hover:shadow-lg transition duration-300 h-full">
                        <div class="w-16 h-16 bg-purple-600 text-white rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Tanda Tangan Digital</h3>
                        <p class="text-gray-600 leading-relaxed">Pejabat dapat memberikan persetujuan dan TTD digital secara instan dan aman.</p>
                    </div>
                </div>

                <div class="fade-in fade-in-next-3 group">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-sm hover:shadow-lg transition duration-300 h-full">
                        <div class="w-16 h-16 bg-green-600 text-white rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Download & Arsip Digital</h3>
                        <p class="text-gray-600 leading-relaxed">Surat dapat diunduh dalam format PDF dan tersimpan otomatis dalam arsip digital.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-20 bg-gradient-to-r from-teal-600 to-blue-600">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h2 class="fade-in text-3xl font-bold mb-8">Dipercaya oleh Civitas Akademika UKDW</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <div class="fade-in fade-in-next">
                        <div class="text-4xl font-bold mb-2">1,200+</div>
                        <div class="text-teal-100">Mahasiswa Aktif</div>
                    </div>
                    <div class="fade-in fade-in-next-1">
                        <div class="text-4xl font-bold mb-2">50+</div>
                        <div class="text-teal-100">Staff & Pejabat</div>
                    </div>
                    <div class="fade-in fade-in-next-2">
                        <div class="text-4xl font-bold mb-2">99.5%</div>
                        <div class="text-teal-100">Kepuasan Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak" class="bg-gray-800 mt-16 py-12 text-white">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="fade-in fade-in-next">
                <img src="https://www.ukdw.ac.id/wp-content/uploads/2019/07/logo-ukdw.png" alt="Logo UKDW" class="h-10 mb-4 filter brightness-0 invert">
                <p class="text-sm text-gray-400">SiSUA adalah layanan terpusat untuk efisiensi administrasi surat menyurat akademik di lingkungan UKDW.</p>
            </div>
            
            <div class="fade-in fade-in-next-1">
                <h3 class="text-lg font-semibold mb-3 border-b border-gray-700 pb-1">Navigasi Cepat</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#alur" class="hover:ukdw-text-green transition">Alur Pengajuan</a></li>
                    <li><a href="#fitur" class="hover:ukdw-text-green transition">Fitur Unggulan</a></li>
                    <li><a href="/login" class="hover:ukdw-text-green transition">Portal Login</a></li>
                </ul>
            </div>
            
            <div class="fade-in fade-in-next-2">
                <h3 class="text-lg font-semibold mb-3 border-b border-gray-700 pb-1">Kontak & Dukungan</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><p>Administrasi Akademik (Biro AAK)</p></li>
                    <li><p>Email: aak@ukdw.ac.id</p></li>
                    <li><p>Telepon: (0274) 563929</p></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-6">
            <p class="text-center text-sm text-gray-500">
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