<?php

namespace App\Livewire\Settings;

use App\Models\CompanyProfile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Pengaturan Profil Usaha')]
class CompanyProfileSettings extends Component
{
    public string $nama_usaha = '';

    public string $alamat = '';

    public string $telepon = '';

    public string $whatsapp = '';

    public string $email = '';

    public string $logo = '';

    public string $footer_invoice = '';

    public string $nama_bank = '';

    public string $no_rekening = '';

    public string $atas_nama = '';

    public function mount(): void
    {
        $profile = CompanyProfile::current();

        $this->nama_usaha = $profile->nama_usaha;
        $this->alamat = $profile->alamat ?? '';
        $this->telepon = $profile->telepon ?? '';
        $this->whatsapp = $profile->whatsapp ?? '';
        $this->email = $profile->email ?? '';
        $this->logo = $profile->logo ?? '';
        $this->footer_invoice = $profile->footer_invoice ?? '';
        $this->nama_bank = $profile->nama_bank ?? '';
        $this->no_rekening = $profile->no_rekening ?? '';
        $this->atas_nama = $profile->atas_nama ?? '';
    }

    protected function rules(): array
    {
        return [
            'nama_usaha' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'logo' => ['nullable', 'string'],
            'footer_invoice' => ['nullable', 'string'],
            'nama_bank' => ['nullable', 'string', 'max:100'],
            'no_rekening' => ['nullable', 'string', 'max:100'],
            'atas_nama' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $profile = CompanyProfile::current();
        $profile->update([
            'nama_usaha' => $this->nama_usaha,
            'alamat' => $this->alamat ?: null,
            'telepon' => $this->telepon ?: null,
            'whatsapp' => $this->whatsapp ?: null,
            'email' => $this->email ?: null,
            'logo' => $this->logo ?: null,
            'footer_invoice' => $this->footer_invoice ?: null,
            'nama_bank' => $this->nama_bank ?: null,
            'no_rekening' => $this->no_rekening ?: null,
            'atas_nama' => $this->atas_nama ?: null,
        ]);

        $this->dispatch('toast', message: 'Profil usaha dan pengaturan invoice berhasil disimpan.', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.company-profile-settings');
    }
}
