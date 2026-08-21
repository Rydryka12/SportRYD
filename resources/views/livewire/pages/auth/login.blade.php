<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- Judul --}}
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1" style="color:#12244a;">Masuk ke SportRYD</h4>
        <p class="text-muted small mb-0">Booking lapangan favoritmu sekarang</p>
    </div>

    {{-- Session status --}}
    @if (session('status'))
        <div class="auth-alert">
            <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
        </div>
    @endif

    <form wire:submit="login">
        {{-- Email --}}
        <div class="mb-3">
            <label class="auth-label" for="email">Email</label>
            <input wire:model="form.email"
                   id="email" type="email" name="email"
                   class="auth-input @error('form.email') is-invalid @enderror"
                   placeholder="email@kamu.com"
                   required autofocus autocomplete="username">
            @error('form.email')
                <div class="auth-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="auth-label mb-0" for="password">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="auth-link" style="font-size:0.8rem;">Lupa password?</a>
                @endif
            </div>
            <input wire:model="form.password"
                   id="password" type="password" name="password"
                   class="auth-input @error('form.password') is-invalid @enderror"
                   placeholder="••••••••"
                   required autocomplete="current-password">
            @error('form.password')
                <div class="auth-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <input wire:model="form.remember" id="remember" type="checkbox" class="auth-check">
            <label for="remember" class="text-muted small mb-0" style="cursor:pointer;">Ingat saya</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-auth">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
        </button>
    </form>

    <div class="auth-divider">atau</div>

    <div class="text-center small">
        Belum punya akun?
        <a href="{{ route('register') }}" wire:navigate class="auth-link">Daftar sekarang</a>
    </div>
</div>
