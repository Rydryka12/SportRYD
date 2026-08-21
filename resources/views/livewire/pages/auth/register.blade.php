<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $no_hp = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'no_hp'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password']    = Hash::make($validated['password']);
        $validated['role']        = 'Customer';
        $validated['status_akun'] = 'Aktif';

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- Judul --}}
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1" style="color:#12244a;">Buat Akun SportRYD</h4>
        <p class="text-muted small mb-0">Gratis, langsung bisa booking lapangan</p>
    </div>

    <form wire:submit="register">
        {{-- Nama --}}
        <div class="mb-3">
            <label class="auth-label" for="name">Nama Lengkap</label>
            <input wire:model="name"
                   id="name" type="text" name="name"
                   class="auth-input @error('name') is-invalid @enderror"
                   placeholder="Nama kamu"
                   required autofocus autocomplete="name">
            @error('name')
                <div class="auth-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="auth-label" for="email">Email</label>
            <input wire:model="email"
                   id="email" type="email" name="email"
                   class="auth-input @error('email') is-invalid @enderror"
                   placeholder="email@kamu.com"
                   required autocomplete="username">
            @error('email')
                <div class="auth-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- No HP --}}
        <div class="mb-3">
            <label class="auth-label" for="no_hp">
                No. HP <span class="text-muted fw-normal">(opsional)</span>
            </label>
            <input wire:model="no_hp"
                   id="no_hp" type="text" name="no_hp"
                   class="auth-input @error('no_hp') is-invalid @enderror"
                   placeholder="08xxxxxxxxxx">
            @error('no_hp')
                <div class="auth-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="auth-label" for="password">Password</label>
            <input wire:model="password"
                   id="password" type="password" name="password"
                   class="auth-input @error('password') is-invalid @enderror"
                   placeholder="Min. 8 karakter"
                   required autocomplete="new-password">
            @error('password')
                <div class="auth-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label class="auth-label" for="password_confirmation">Konfirmasi Password</label>
            <input wire:model="password_confirmation"
                   id="password_confirmation" type="password" name="password_confirmation"
                   class="auth-input"
                   placeholder="Ulangi password"
                   required autocomplete="new-password">
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-auth">
            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
        </button>
    </form>

    <div class="auth-divider">atau</div>

    <div class="text-center small">
        Sudah punya akun?
        <a href="{{ route('login') }}" wire:navigate class="auth-link">Masuk</a>
    </div>
</div>
