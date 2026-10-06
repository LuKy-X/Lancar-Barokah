<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Masuk ke Sistem')]
class Login extends Component
{
    public string $username = '';

    public string $password = '';

    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password yang Anda masukkan salah.',
            ]);
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            throw ValidationException::withMessages([
                'username' => 'Akun Anda berstatus nonaktif. Silakan hubungi administrator.',
            ]);
        }

        session()->regenerate();

        $this->redirectIntended(route('dashboard'), navigate: true);
    }

    public function setDemoCredentials(string $role): void
    {
        if ($role === 'admin') {
            $this->username = 'admin';
            $this->password = 'password';
        } else {
            $this->username = 'petugas';
            $this->password = 'password';
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
