<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Equipment - Your One-Stop Shop for Equipment Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Add Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .bg-primary {
            background-color: #fe0000;
        }
        .text-primary {
            color: #131313;
        }
        .border-primary {
            border-color: #ef4444;
        }
        .hover\:bg-primary-dark:hover {
            background-color: #b91c1c;
        }
        .from-primary {
            --tw-gradient-from: #ef4444;
        }
        .to-primary-dark {
            --tw-gradient-to: #b91c1c;
        }
        /* Add text color for regular text */
        body {
            color: #131313;
        }
        .text-gray-600 {
            color: #131313;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Header -->
    <header class=" border-gray-700 shadow-md">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold text-primary">Al Management</h1>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="px-4 py-2 border border-primary text-primary rounded hover:bg-red-50 transition">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 border border-primary text-primary rounded hover:bg-red-50 transition">Register</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="bg-white text-black py-20">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Jual, Beli, Sewa Kamera dan Iphone</h2>
                <p class="text-xl mb-8">Jual Beli Kamera Bekas Purbalingga</p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark transition">Get Started</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Browse by Category</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($categories as $category)
                <div class="bg-gray-50 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="h-40 bg-gray-200 flex items-center justify-center">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-tools text-5xl text-gray-400"></i>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $category->description ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section id="featured" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Featured Equipment</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="h-48 bg-gray-200">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-5xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold">{{ $product->title }}</h3>
                            <span class="bg-red-100 text-primary text-xs font-semibold px-2.5 py-0.5 rounded">{{ $product->category->name ?? 'Equipment' }}</span>
                        </div>
                        <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark transition">Lihat Semua Barang</a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Bagaimana cara kerjanya?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-3xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">1. Cari dan Pilih</h3>
                    <p class="text-gray-600">Cari peralatan sesuai kebutuhan anda.</p>
                </div>
                <div class="text-center">
                    <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-alt text-3xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">2. Booking</h3>
                    <p class="text-gray-600">Pilih barang dan tanggal sewa kamu.</p>
                </div>
                <div class="text-center">
                    <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-truck text-3xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">3. Ambil dan Kembalikan</h3>
                    <p class="text-gray-600">Ambil barang yang kamu sewa, lalu kembalikan barang sesuai tanggalnya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Lokasi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Kontak kita</h3>
                    <p class="text-gray-600 mb-6">Temukan kami di lokasi yang strategis untuk pengambilan dan pengembalian peralatan.</p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-1 mr-4"></i>
                            <p class="text-gray-600">Jl. Palem Kuning, Karangmanyar, Kec. Kalimanah, Kabupaten Purbalingga, Jawa Tengah 53371</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-phone text-primary mt-1 mr-4"></i>
                            <p class="text-gray-600">0896-1222-2209</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="h-full bg-gray-300 rounded-lg">
                        <!-- Placeholder for a map -->
                        <div class="w-full h-full min-h-[300px]">
                        <iframe 
  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.2900482996423!2d109.356873!3d-7.402685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e65599bdffdca79%3A0x17b1d97c69f23fc9!2sAL%20CAMERA%20PURBALINGGA!5e0!3m2!1sid!2sid!4v1681081011123!5m2!1sid!2sid"
  width="100%" 
  height="100%" 
  style="border:0;" 
  allowfullscreen="" 
  loading="lazy" 
  referrerpolicy="no-referrer-when-downgrade">
</iframe>

                    </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Al Management</h3>
                    <div class="flex space-x-4">
                    <a href="https://wa.me/6289612222209" target="_blank" class="text-gray-400 hover:text-white transition">
                         <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.instagram.com/alcamera__/" target="_blank" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                    </a>

                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Categories</h4>
                    <ul class="space-y-2">
                        @foreach($categories->take(5) as $category)
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Al Management. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Simple scroll to section functionality
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>