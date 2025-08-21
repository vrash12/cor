<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Farm2Go - Fresh from Farm to Your Table')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        :root {
            --sidebar-w: 280px;
            --primary-green: #198754;
            --light-green: #d1e7dd;
            --dark-green: #146c43;
            --gradient: linear-gradient(135deg, #198754 0%, #20c997 100%);
            --sidebar-gradient: linear-gradient(180deg, #198754 0%, #146c43 100%);
            --shadow-soft: 0 2px 15px rgba(0,0,0,0.08);
            --shadow-hover: 0 5px 25px rgba(0,0,0,0.15);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
        }

        /* Enhanced Brand Styling */
        .brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            font-size: 1.5rem;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .brand i {
            margin-right: 8px;
            color: #fff;
            -webkit-text-fill-color: #fff;
        }

        /* Enhanced Sidebar */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-gradient);
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="75" cy="75" r="0.5" fill="rgba(255,255,255,0.02)"/><circle cx="50" cy="10" r="1.5" fill="rgba(255,255,255,0.02)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            margin: 4px 12px;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(4px);
            backdrop-filter: blur(10px);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
            box-shadow: inset 3px 0 0 rgba(255, 255, 255, 0.5);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* User Profile Section */
        .user-profile {
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            padding: 20px;
            margin: 0 12px 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 12px;
        }

        .user-info h6 {
            color: #fff;
            margin: 0;
            font-weight: 600;
        }

        .user-role {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            margin: 0;
        }

        /* Mobile Navigation */
        .mobile-nav {
            background: var(--gradient);
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
        }

        /* Main Content Area */
        .main-content {
            background: transparent;
            min-height: 100vh;
        }

        /* Enhanced Cards */
        .card {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--shadow-soft);
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .card-header {
            background: rgba(25, 135, 84, 0.05);
            border-bottom: 1px solid rgba(25, 135, 84, 0.1);
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            font-weight: 600;
            color: var(--dark-green);
        }

        /* Enhanced Alerts */
        .alert {
            border-radius: var(--border-radius);
            border: none;
            backdrop-filter: blur(10px);
            font-weight: 500;
        }

        .alert-success {
            background: rgba(25, 135, 84, 0.1);
            color: var(--dark-green);
            border-left: 4px solid var(--primary-green);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Enhanced Buttons */
        .btn-success {
            background: var(--gradient);
            border: none;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            box-shadow: 0 2px 10px rgba(25, 135, 84, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4);
            background: linear-gradient(135deg, #146c43 0%, #1a9e7a 100%);
        }

        .btn-outline-success {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .btn-outline-success:hover {
            background: var(--gradient);
            border-color: var(--primary-green);
            transform: translateY(-1px);
        }

        /* Logout Button */
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            transform: translateY(-1px);
        }

        /* Footer */
        footer {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Offcanvas Enhancements */
        .offcanvas {
            backdrop-filter: blur(10px);
        }

        .offcanvas-header {
            background: var(--gradient);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .offcanvas-body {
            background: linear-gradient(180deg, rgba(25, 135, 84, 0.02) 0%, rgba(255, 255, 255, 0.95) 100%);
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            :root {
                --sidebar-w: 0px;
            }
            
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 576px) {
            .user-profile {
                margin: 0 8px 16px;
                padding: 16px;
            }
            
            .sidebar .nav-link {
                margin: 4px 8px;
                padding: 10px 16px;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Smooth page transitions */
        .page-transition {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>

{{-- Mobile Navigation --}}
<nav class="navbar navbar-expand-lg mobile-nav d-lg-none">
  <div class="container-fluid">
    <a class="navbar-brand brand text-white" href="{{ url('/') }}">
        <i class="fas fa-leaf"></i>Farm2Go
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
      <i class="fas fa-bars text-white"></i>
    </button>
  </div>
</nav>

<div class="d-flex">
  {{-- Desktop Sidebar --}}
  <aside class="sidebar d-none d-lg-flex flex-column text-white min-vh-100">
    <div class="p-3 position-relative z-1">
        <a class="brand h4 mb-4 text-white text-decoration-none d-block" href="{{ url('/') }}">
            <i class="fas fa-leaf"></i>Farm2Go
        </a>

        @auth
            @php($role = strtolower(auth()->user()->role ?? ''))
            @php($userName = auth()->user()->name ?? 'User')
            @php($userInitials = strtoupper(substr($userName, 0, 2)))
            
            <div class="user-profile">
                <div class="d-flex align-items-center">
                    <div class="user-avatar">
                        {{ $userInitials }}
                    </div>
                    <div class="user-info flex-grow-1">
                        <h6>{{ $userName }}</h6>
                        <p class="user-role">{{ ucfirst($role) }}</p>
                    </div>
                </div>
            </div>

            <ul class="nav nav-pills flex-column mb-auto">
                @if ($role === 'customer')
      
<li class="nav-item">
  <a class="nav-link {{ request()->routeIs('customer.products.*') ? 'active' : '' }}" href="{{ route('customer.products.index') }}">
    <i class="fas fa-shopping-basket"></i>Products
  </a>
</li>
<li class="nav-item">
  <a class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">
    <i class="fas fa-receipt"></i>My Orders
  </a>
</li>
<li class="nav-item">
  <a class="nav-link {{ request()->routeIs('customer.notifications.*') ? 'active' : '' }}" href="{{ route('customer.notifications.index') }}">
    <i class="fas fa-bell"></i>Notifications
  </a>
</li>
<li class="nav-item">
  <a class="nav-link {{ request()->routeIs('customer.settings.*') ? 'active' : '' }}" href="{{ route('customer.settings.index') }}">
    <i class="fas fa-user-circle"></i>My Profile
  </a>
</li>

                @elseif ($role === 'farmer')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}" href="#">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('farmer.products.*') ? 'active' : '' }}" href="{{ route('farmer.products.index') }}">
                            <i class="fas fa-seedling"></i>My Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('farmer.orders.*') ? 'active' : '' }}" href="{{ route('farmer.orders.index') }}">
                            <i class="fas fa-clipboard-list"></i>Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('farmer.reports.*') ? 'active' : '' }}" href="{{ route('farmer.reports.sales') }}">
                            <i class="fas fa-chart-line"></i>Sales Report
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('farmer.profile.*') ? 'active' : '' }}" href="{{ route('farmer.profile.edit') }}">
                            <i class="fas fa-user-cog"></i>My Profile
                        </a>
                    </li>
                @elseif ($role === 'cooperativeadmin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-pie"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="#">
                            <i class="fas fa-users"></i>Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="#">
                            <i class="fas fa-boxes"></i>Manage Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="#">
                            <i class="fas fa-file-alt"></i>Reports
                        </a>
                    </li>
                @endif
            </ul>

            <div class="mt-auto p-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-logout w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </div>
        @endauth

        @guest
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i>Register
                    </a>
                </li>
            </ul>
        @endguest
    </div>
  </aside>

  {{-- Main Content --}}
  <div class="flex-grow-1 main-content">
    <main class="container-fluid py-4 page-transition">
      {{-- Flash Messages --}}
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      
      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>There were some problems with your input:</strong>
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @yield('content')
    </main>

    <footer class="border-top py-4 mt-5">
      <div class="container-fluid text-center text-muted">
        <div class="row align-items-center">
            <div class="col-md-6 text-md-start">
                <small>&copy; {{ date('Y') }} Farm2Go · Tarlac City</small>
            </div>
            <div class="col-md-6 text-md-end">
                <small>
                    <i class="fas fa-leaf text-success me-1"></i>
                    Fresh from Farm to Your Table
                </small>
            </div>
        </div>
      </div>
    </footer>
  </div>
</div>

{{-- Mobile Offcanvas Sidebar --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title brand text-white" id="mobileSidebarLabel">
        <i class="fas fa-leaf"></i>Farm2Go
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    @auth
      @php($role = strtolower(auth()->user()->role ?? ''))
      @php($userName = auth()->user()->name ?? 'User')
      @php($userInitials = strtoupper(substr($userName, 0, 2)))
      
      <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: rgba(25, 135, 84, 0.1);">
        <div class="user-avatar me-3" style="background: var(--gradient); width: 40px; height: 40px; font-size: 1.2rem;">
          {{ $userInitials }}
        </div>
        <div>
          <h6 class="mb-0 text-dark">{{ $userName }}</h6>
          <small class="text-muted">{{ ucfirst($role) }}</small>
        </div>
      </div>

      <ul class="nav nav-pills flex-column mb-auto">
        @if ($role === 'customer')
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('customer.products.index') }}">
              <i class="fas fa-shopping-basket me-2"></i>Products
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('customer.orders.index') }}">
              <i class="fas fa-receipt me-2"></i>My Orders
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="#">
              <i class="fas fa-user-circle me-2"></i>My Profile
            </a>
          </li>
        @elseif ($role === 'farmer')
          <li class="nav-item">
            <a class="nav-link text-dark" href="#">
              <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('farmer.products.index') }}">
              <i class="fas fa-seedling me-2"></i>My Products
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('farmer.orders.index') }}">
              <i class="fas fa-clipboard-list me-2"></i>Orders
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('farmer.reports.sales') }}">
              <i class="fas fa-chart-line me-2"></i>Sales Report
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('farmer.profile.edit') }}">
              <i class="fas fa-user-cog me-2"></i>My Profile
            </a>
          </li>
        @elseif ($role === 'cooperativeadmin')
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('admin.dashboard') }}">
              <i class="fas fa-chart-pie me-2"></i>Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="#">
              <i class="fas fa-users me-2"></i>Manage Users
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="#">
              <i class="fas fa-boxes me-2"></i>Manage Products
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="#">
              <i class="fas fa-file-alt me-2"></i>Reports
            </a>
          </li>
        @endif
      </ul>

      <form method="POST" action="{{ route('logout') }}" class="mt-auto">
        @csrf
        <button class="btn btn-success w-100">
          <i class="fas fa-sign-out-alt me-2"></i>Logout
        </button>
      </form>
    @endauth

    @guest
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
          <a class="nav-link text-dark" href="{{ route('login') }}">
            <i class="fas fa-sign-in-alt me-2"></i>Login
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="{{ route('register') }}">
            <i class="fas fa-user-plus me-2"></i>Register
          </a>
        </li>
      </ul>
    @endguest
  </div>
</div>
@stack('modals')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
// Enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (!alert.querySelector('.btn-close')) return;
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Add loading states to buttons
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>Loading...';
                submitBtn.disabled = true;
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Enhanced nav link active states
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});

// Add some utility functions for future use
window.Farm2Go = {
    showNotification: function(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('main .container-fluid');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
            
            // Auto dismiss after 4 seconds
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 4000);
        }
    },

    confirmAction: function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },

    // Form validation helpers
    validateForm: function(formId) {
        const form = document.getElementById(formId);
        if (!form) return false;
        
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    },

    // Dynamic content loading
    loadContent: function(url, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = '<div class="text-center py-4"><div class="loading-spinner"></div></div>';
        
        fetch(url)
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(error => {
                container.innerHTML = '<div class="alert alert-danger">Error loading content</div>';
            });
    }
};

// Handle responsive sidebar behavior
function handleSidebarResize() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (window.innerWidth < 992) {
        sidebar?.classList.add('d-none');
        mainContent?.style.setProperty('margin-left', '0');
    } else {
        sidebar?.classList.remove('d-none');
        mainContent?.style.setProperty('margin-left', 'var(--sidebar-w)');
    }
}

window.addEventListener('resize', handleSidebarResize);
document.addEventListener('DOMContentLoaded', handleSidebarResize);
