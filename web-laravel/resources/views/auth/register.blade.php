@extends('layouts.app')

@section('title', 'Register - Farm2Go')

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
        max-width: 500px;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .auth-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        padding: 2.5rem 2rem 1.5rem;
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
        font-size: 2.5rem;
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
        font-size: 0.95rem;
        font-weight: 500;
        opacity: 0.8;
    }
    
    .auth-body {
        padding: 2rem;
        background: rgba(255, 255, 255, 0.98);
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .form-floating {
        margin-bottom: 1.2rem;
        position: relative;
    }
    
    .form-floating input,
    .form-floating select {
        border: 2px solid #e9ecef;
        border-radius: 14px;
        font-size: 0.95rem;
        padding: 1rem 1rem 0.5rem;
        background: rgba(248, 249, 250, 0.8);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
    }
    
    .form-floating input:focus,
    .form-floating select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-1px);
    }
    
    .form-floating label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .form-floating input:focus ~ label,
    .form-floating input:not(:placeholder-shown) ~ label,
    .form-floating select:focus ~ label,
    .form-floating select:not([value=""]) ~ label {
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
        font-size: 0.9rem;
    }
    
    .form-floating input:focus ~ .input-icon,
    .form-floating select:focus ~ .input-icon {
        color: var(--primary-green);
    }
    
    .role-selector {
        margin-bottom: 1.5rem;
    }
    
    .role-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.8rem;
        margin-top: 0.8rem;
    }
    
    .role-option {
        position: relative;
        cursor: pointer;
    }
    
    .role-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    
    .role-label {
        display: block;
        padding: 1rem 0.5rem;
        background: rgba(248, 249, 250, 0.9);
        border: 2px solid #e9ecef;
        border-radius: 12px;
        text-align: center;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #495057;
        backdrop-filter: blur(10px);
    }
    
    .role-option input[type="radio"]:checked + .role-label {
        background: rgba(25, 135, 84, 0.1);
        border-color: var(--primary-green);
        color: var(--dark-green);
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.2);
    }
    
    .role-icon {
        font-size: 1.4rem;
        display: block;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .role-option input[type="radio"]:checked + .role-label .role-icon {
        color: var(--primary-green);
        transform: scale(1.1);
    }
    
    .btn-auth {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        padding: 1rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 6px 20px rgba(25, 135, 84, 0.3);
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
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(25, 135, 84, 0.4);
        background: linear-gradient(135deg, #146c43 0%, #1a9e7a 100%);
    }
    
    .btn-auth:hover::before {
        left: 100%;
    }
    
    .btn-auth:active {
        transform: translateY(-1px);
    }
    
    .terms-checkbox {
        margin: 1.5rem 0;
    }
    
    .form-check {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .form-check-input {
        border-radius: 6px;
        border: 2px solid #dee2e6;
        margin-top: 0.2rem;
        flex-shrink: 0;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }
    
    .form-check-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
        line-height: 1.4;
    }
    
    .form-check-label a {
        color: var(--primary-green);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .form-check-label a:hover {
        color: var(--dark-green);
        text-decoration: underline;
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
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
    
    .password-strength {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        margin-top: 0.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .password-strength-bar {
        height: 100%;
        border-radius: 2px;
        transition: all 0.3s ease;
        width: 0%;
    }
    
    .strength-weak { background: #dc3545; width: 25%; }
    .strength-fair { background: #fd7e14; width: 50%; }
    .strength-good { background: #ffc107; width: 75%; }
    .strength-strong { background: #198754; width: 100%; }
    
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
    
    /* Custom scrollbar for form body */
    .auth-body::-webkit-scrollbar {
        width: 6px;
    }
    
    .auth-body::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 3px;
    }
    
    .auth-body::-webkit-scrollbar-thumb {
        background: rgba(25, 135, 84, 0.3);
        border-radius: 3px;
    }
    
    .auth-body::-webkit-scrollbar-thumb:hover {
        background: rgba(25, 135, 84, 0.5);
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
            padding: 2rem 1.5rem 1rem;
        }
        
        .auth-body {
            padding: 1.5rem;
            max-height: 65vh;
        }
        
        .brand-logo {
            font-size: 2rem;
        }
        
        .role-grid {
            grid-template-columns: 1fr;
            gap: 0.6rem;
        }
        
        .role-label {
            padding: 0.8rem;
            font-size: 0.9rem;
        }
        
        .form-floating {
            margin-bottom: 1rem;
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
            <div class="mb-3 text-center">
                <h2 class="h5 fw-bold text-dark mb-1">Join Farm2Go</h2>
                <p class="text-muted mb-0 small">Create your account to get started</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf
                
                <div class="form-floating">
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        placeholder="Full Name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                    >
                    <label for="name">Full Name</label>
                    <i class="fas fa-user input-icon"></i>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating">
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="name@example.com" 
                        value="{{ old('email') }}" 
                        required
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
                        minlength="8"
                    >
                    <label for="password">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="form-control @error('password_confirmation') is-invalid @enderror" 
                        placeholder="Confirm Password" 
                        required
                    >
                    <label for="password_confirmation">Confirm Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="role-selector">
                    <label class="form-label fw-semibold text-muted mb-2 small">
                        <i class="fas fa-users me-1"></i>Choose your role
                    </label>
                    <div class="role-grid">
                        <div class="role-option">
                            <input 
                                type="radio" 
                                name="role" 
                                id="farmer" 
                                value="Farmer" 
                                {{ old('role') == 'Farmer' ? 'checked' : '' }}
                                required
                            >
                            <label for="farmer" class="role-label">
                                <i class="fas fa-seedling role-icon"></i>
                                <span>Farmer</span>
                            </label>
                        </div>
                        <div class="role-option">
                            <input 
                                type="radio" 
                                name="role" 
                                id="customer" 
                                value="Customer" 
                                {{ old('role') == 'Customer' ? 'checked' : '' }}
                                required
                            >
                            <label for="customer" class="role-label">
                                <i class="fas fa-shopping-basket role-icon"></i>
                                <span>Customer</span>
                            </label>
                        </div>
                  
                    </div>
                    @error('role')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="terms-checkbox">
                    <div class="form-check">
                        <input 
                            class="form-check-input @error('terms') is-invalid @enderror" 
                            type="checkbox" 
                            name="terms" 
                            id="terms" 
                            {{ old('terms') ? 'checked' : '' }}
                            required
                        >
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" target="_blank">Terms of Service</a> and 
                            <a href="#" target="_blank">Privacy Policy</a>
                        </label>
                        @error('terms')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-auth text-white">
                    <i class="fas fa-user-plus me-2"></i>
                    Create Account
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? 
                    <a href="{{ route('login') }}">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('strengthBar');
    
    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        updateStrengthBar(strength);
    });
    
    // Password confirmation checker
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmation = this.value;
        
        if (confirmation && password !== confirmation) {
            this.setCustomValidity('Passwords do not match');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        // Validate passwords match
        if (passwordInput.value !== confirmPasswordInput.value) {
            e.preventDefault();
            confirmPasswordInput.focus();
            return false;
        }
        
        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Creating Account...';
        submitBtn.disabled = true;
        
        // Re-enable after timeout as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 8000);
    });
    
    // Enhanced form validation
    const inputs = form.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                validateField(this);
            }
        });
    });
    
    // Role selection animation
    const roleOptions = document.querySelectorAll('.role-option input[type="radio"]');
    roleOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove active class from all options
            roleOptions.forEach(opt => {
                opt.parentElement.classList.remove('selected');
            });
            
            // Add active class to selected option
            if (this.checked) {
                this.parentElement.classList.add('selected');
            }
        });
    });
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]/)) strength += 1;
        if (password.match(/[A-Z]/)) strength += 1;
        if (password.match(/[0-9]/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
        
        return strength;
    }
    
    function updateStrengthBar(strength) {
        const strengthClasses = ['', 'strength-weak', 'strength-fair', 'strength-good', 'strength-strong'];
        
        // Remove all strength classes
        strengthBar.className = 'password-strength-bar';
        
        // Add appropriate strength class
        if (strength > 0) {
            strengthBar.classList.add(strengthClasses[Math.min(strength, 4)]);
        }
    }
    
    function validateField(field) {
        const value = field.value.trim();
        
        switch (field.type) {
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (value && !emailRegex.test(value)) {
                    field.setCustomValidity('Please enter a valid email address');
                    field.classList.add('is-invalid');
                } else {
                    field.setCustomValidity('');
                    field.classList.remove('is-invalid');
                }
                break;
                
            case 'password':
                if (field.id === 'password') {
                    if (value && value.length < 8) {
                        field.setCustomValidity('Password must be at least 8 characters long');
                        field.classList.add('is-invalid');
                    } else {
                        field.setCustomValidity('');
                        field.classList.remove('is-invalid');
                    }
                }
                break;
                
            default:
                if (!value && field.hasAttribute('required')) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
                break;
        }
    }
});
</script>
@endpush