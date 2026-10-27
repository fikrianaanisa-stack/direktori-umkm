<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mengarahkan tautan verifikasi email ke route pengunjung
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'pengunjung.verification.verify',
                now()->addMinutes(60),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });

        // Mengatur email verifikasi dalam bahasa Indonesia
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Email - Direktori UMKM Kota Ambon')
                ->greeting('Halo!')
                ->line('Terima kasih telah mendaftar di Direktori UMKM Kota Ambon.')
                ->line('Silakan verifikasi alamat email Anda dengan mengklik tombol berikut.')
                ->action('Verifikasi Email', $url)
                ->line('Tautan verifikasi email ini berlaku selama 60 menit.')
                ->line('Jika Anda tidak merasa mendaftarkan akun, abaikan email ini.');
        });

        // Mengatur email reset password dalam bahasa Indonesia
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

            return (new MailMessage)
                ->subject('Pengaturan Ulang Password - Direktori UMKM Kota Ambon')
                ->greeting('Halo!')
                ->line('Kami menerima permintaan untuk mengatur ulang password akun Anda.')
                ->action('Atur Ulang Password', $url)
                ->line("Tautan pengaturan ulang password ini berlaku selama {$expire} menit.")
                ->line('Jika Anda tidak meminta pengaturan ulang password, abaikan email ini.');
        });
    }
}