<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CineWave - Streaming Film Terbaik')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --netflix-red: #E50914;
            --netflix-black: #141414;
            --netflix-dark: #000000;
            --netflix-gray: #808080;
            --netflix-light-gray: #e5e5e5;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--netflix-black);
            color: #fff;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 4%;
            z-index: 1000;
            transition: background-color 0.3s ease;
            background: linear-gradient(180deg, rgba(0,0,0,0.7) 10%, rgba(0,0,0,0));
        }

        .navbar.scrolled {
            background-color: var(--netflix-black);
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1920px;
            margin: 0 auto;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .logo {
            font-size: 32px;
            font-weight: 800;
            color: var(--netflix-red);
            text-decoration: none;
            letter-spacing: -1px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .nav-links a {
            color: #e5e5e5;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #b3b3b3;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            background: rgba(0,0,0,0.75);
            border: 1px solid #fff;
            padding: 8px 35px 8px 15px;
            border-radius: 4px;
            color: #fff;
            width: 250px;
            font-size: 14px;
        }

        .search-box input:focus {
            outline: none;
            border-color: #fff;
        }

        .search-box i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #fff;
        }

        .user-menu {
            position: relative;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 4px;
            background: var(--netflix-red);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 600;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--netflix-red);
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #f40612;
        }

        .btn-secondary {
            background-color: rgba(255,255,255,0.3);
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: rgba(255,255,255,0.2);
        }

        /* Container */
        .container {
            max-width: 1920px;
            margin: 0 auto;
            padding: 0 4%;
        }

        /* Footer */
        .footer {
            background-color: var(--netflix-dark);
            padding: 50px 4%;
            margin-top: 80px;
        }

        .footer-content {
            max-width: 980px;
            margin: 0 auto;
            color: var(--netflix-gray);
        }

        .footer-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .footer-links a {
            color: var(--netflix-gray);
            text-decoration: none;
            font-size: 13px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            margin-top: 30px;
            font-size: 13px;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #155724;
            border: 1px solid #28a745;
            color: #d4edda;
        }

        .alert-error {
            background-color: #721c24;
            border: 1px solid #dc3545;
            color: #f8d7da;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                background: var(--netflix-black);
                flex-direction: column;
                padding: 20px;
                gap: 15px;
            }

            .nav-links.active {
                display: flex;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .search-box input {
                width: 180px;
            }

            .logo {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 15px 3%;
            }

            .search-box {
                display: none;
            }

            .navbar-left {
                gap: 15px;
            }

            .navbar-content {
                gap: 10px;
            }

            .navbar-right {
                gap: 10px;
            }

            .btn {
                padding: 8px 14px;
                font-size: 13px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="navbar-content">
            <div class="navbar-left">
                <a href="{{ route('home') }}" class="logo">CINEWAVE</a>
                <ul class="nav-links" id="navLinks">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('browse') }}">Film</a></li>
                    <li><a href="{{ route('browse', ['category' => 'trending']) }}">Trending</a></li>
                    <li><a href="{{ route('browse', ['category' => 'new']) }}">Terbaru</a></li>
                    @auth
                        <li><a href="{{ route('watchlist.index') }}"><i class="fas fa-heart"></i> My Watchlist</a></li>
                        @if(auth()->user()->is_admin)
                            <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                        @endif
                    @endauth
                </ul>
                <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
            <div class="navbar-right">
                <div class="search-box">
                    <input type="text" placeholder="Cari film..." id="searchInput">
                    <i class="fas fa-search"></i>
                </div>
                @auth
                    <div class="user-menu" style="position: relative;">
                        <div class="user-avatar" onclick="toggleUserMenu()" style="cursor: pointer;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div id="userDropdown" style="display: none; position: absolute; right: 0; top: 45px; background: rgba(0,0,0,0.95); border: 1px solid #333; border-radius: 4px; min-width: 200px; padding: 10px 0; z-index: 1000;">
                            <div style="padding: 10px 20px; border-bottom: 1px solid #333; color: #999; font-size: 12px;">
                                {{ auth()->user()->email }}
                            </div>
                            <a href="{{ route('profile.show') }}" style="display: block; padding: 10px 20px; color: #fff; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-user" style="margin-right: 10px;"></i> My Profile
                            </a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" style="display: block; padding: 10px 20px; color: #fff; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                                    <i class="fas fa-cog" style="margin-right: 10px;"></i> Admin Dashboard
                                </a>
                            @endif
                            <div style="border-top: 1px solid #333; margin: 5px 0;"></div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; padding: 10px 20px; color: #fff; background: transparent; border: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                                    <i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="padding-top: 100px;">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>Pertanyaan? Hubungi kami</p>
            <div class="footer-links">
                <div>
                    <a href="#">FAQ</a><br>
                    <a href="#">Hubungi Kami</a><br>
                    <a href="#">Akun</a>
                </div>
                <div>
                    <a href="#">Pusat Media</a><br>
                    <a href="#">Investor Relations</a><br>
                    <a href="#">Cara Menonton</a>
                </div>
                <div>
                    <a href="#">Ketentuan Penggunaan</a><br>
                    <a href="#">Privasi</a><br>
                    <a href="#">Cookie Preferences</a>
                </div>
                <div>
                    <a href="#">Uji Kecepatan</a><br>
                    <a href="#">Hanya di CineWave</a><br>
                    <a href="#">Info Legal</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 CineWave Indonesia</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Toggle user dropdown menu
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu');
            const dropdown = document.getElementById('userDropdown');
            
            if (userMenu && dropdown && !userMenu.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value;
                if (query) {
                    window.location.href = `/browse?search=${encodeURIComponent(query)}`;
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
