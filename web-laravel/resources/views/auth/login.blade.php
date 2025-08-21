@extends('layouts.app')

@section('title', 'Login - Farm2Go')

@push('styles')
<style>
    /* Auth-specific styles */
    body {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        min-height: 100vh;
    }
    
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        max-width: 440px;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .auth-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        padding: 3rem 2rem 2rem;
        text-align: center;
        position: relative;
    }
    
    .auth-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(25,135,84,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(25,135,84,0.08)"/><circle cx="30" cy="80" r="1" fill="rgba(25,135,84,0.06)"/><circle cx="70" cy="70" r="2.5" fill="rgba(25,135,84,0.04)"/></svg>');
        animation: float 15s ease-in-out infinite;
        pointer-events: none;
    }
    
    .brand-logo {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--primary-green);
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .brand-logo i {
        color: var(--primary-green);
    }
    
    .brand-tagline {
        color: var(--dark-green);
        font-size: 1rem;
        font-weight: 500;
        opacity: 0.8;
    }
    
    .auth-body {
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.98);
    }
    
    .form-floating {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .form-floating input {
        border: 2px solid #e9ecef;
        border-radius: 16px;
        font-size: 1rem;
        padding: 1.2rem 1rem 0.6rem;
        background: rgba(248, 249, 250, 0.8);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
    }
    
    .form-floating input:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-1px);
    }
    
    .form-floating label {
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .form-floating input:focus ~ label,
    .form-floating input:not(:placeholder-shown) ~ label {
        color: var(--primary-green);
        font-weight: 600;
    }
    
    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 5;
        transition: color 0.3s ease;
    }
    
    .form-floating input:focus ~ .input-icon {
        color: var(--primary-green);
    }
    
    .btn-auth {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        padding: 1rem 2rem;
        border-radius: 16px;
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(25, 135, 84, 0.3);
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .btn-auth::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-auth:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(25, 135, 84, 0.4);
        background: linear-gradient(135deg, #146c43 0%, #1a9e7a 100%);
    }
    
    .btn-auth:hover::before {
        left: 100%;
    }
    
    .btn-auth:active {
        transform: translateY(-1px);
    }
    
    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 1.5rem 0;
    }
    
    .form-check {
        display: flex;
        align-items: center;
    }
    
    .form-check-input {
        margin-right: 0.5rem;
        border-radius: 6px;
        border: 2px solid #dee2e6;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }
    
    .form-check-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .forgot-link {
        color: var(--primary-green);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .forgot-link:hover {
        color: var(--dark-green);
        text-decoration: underline;
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .auth-footer p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .auth-footer a {
        color: var(--primary-green);
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    
    .auth-footer a:hover {
        color: var(--dark-green);
        background: rgba(25, 135, 84, 0.1);
    }
    
    /* Hide the sidebar and adjust layout for auth pages */
    .sidebar {
        display: none !important;
    }
    
    .mobile-nav {
        display: none !important;
    }
    
    .main-content {
        margin-left: 0 !important;
        width: 100%;
    }
    
    .main-content main {
        padding: 0;
    }
    
    footer {
        display: none;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(180deg); }
    }
    
    @media (max-width: 576px) {
        .auth-card {
            margin: 10px;
            border-radius: 20px;
        }
        
        .auth-header {
            padding: 2rem 1.5rem 1.5rem;
        }
        
        .auth-body {
            padding: 2rem 1.5rem;
        }
        
        .brand-logo {
            font-size: 2.2rem;
        }
        
        .remember-forgot {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="brand-logo">
                <i class="fas fa-leaf"></i> Farm2Go
            </div>
            <p class="brand-tagline">Fresh from Farm to Your Table</p>
        </div>
        
        <div class="auth-body">
            <div class="mb-4 text-center">
                <h2 class="h4 fw-bold text-dark mb-2">Welcome Back!</h2>
                <p class="text-muted mb-0">Sign in to your account to continue</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="form-floating">
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="name@example.com" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                    >
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope input-icon"></i>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Password" 
                        required
                    >
                    <label for="password">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-auth text-white">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Sign In
                </button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? 
                    <a href="{{ route('register') }}">Create one here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing In...';
        submitBtn.disabled = true;
        
        // Re-enable after timeout as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
    
    // Enhanced form validation
    const inputs = form.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endpush