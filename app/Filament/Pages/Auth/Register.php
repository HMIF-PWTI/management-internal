<?php

namespace App\Filament\Pages\Auth;

use App\Mail\AktivasiAkunMail;
use App\Models\Divisi;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class Register extends BaseRegister
{
    protected static ?string $title = 'Daftar';

    protected Width|string|null $maxContentWidth = Width::SevenExtraLarge;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required(),

                TextInput::make('email')
                    ->label('Email Aktif')
                    ->placeholder('contoh: nama@gmail.com')
                    ->helperText('Gunakan email yang aktif untuk menerima link aktivasi akun.')
                    ->email()
                    ->required(),

                TextInput::make('nim')
                    ->label('NIM')
                    ->required(),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->confirmed(),

                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password')
                    ->password()
                    ->revealable()
                    ->required(),

                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                TextInput::make('alamat')
                    ->label('Alamat')
                    ->required(),

                Select::make('id_divisi')
                    ->label('Divisi')
                    ->options(
                        Divisi::query()
                            ->where('singkatan', '!=', 'superadmin')
                            ->pluck('singkatan', 'id')
                    )
                    ->searchable()
                    ->required(),
            ])
            ->columns(2);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title('Terlalu banyak percobaan')
                ->body("Silakan coba lagi dalam {$exception->secondsUntilAvailable} detik.")
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $this->handleRegistration($data);

        Notification::make()
            ->title('Registrasi berhasil')
            ->body('Silakan cek email kamu untuk mengaktifkan akun sebelum login.')
            ->success()
            ->send();

        return new class implements RegistrationResponse {
            public function toResponse($request)
            {
                return redirect()->to(Filament::getLoginUrl());
            }
        };
    }

    protected function handleRegistration(array $data): User
    {
        $existingUser = User::where('email', $data['email'])->first();

        if ($existingUser) {
            if (strtolower(trim((string) $existingUser->status_keanggotaan)) === 'nonaktif') {
                Mail::to($existingUser->email)->send(new AktivasiAkunMail($existingUser));

                return $existingUser;
            }

            throw ValidationException::withMessages([
                'data.email' => 'Email ini sudah terdaftar dan sudah aktif.',
            ]);
        }

        $user = User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'nim' => $data['nim'],
            'password' => Hash::make($data['password']),
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'id_divisi' => $data['id_divisi'],
            'status_keanggotaan' => 'nonaktif',
        ]);

        Mail::to($user->email)->send(new AktivasiAkunMail($user));

        return $user;
    }
}