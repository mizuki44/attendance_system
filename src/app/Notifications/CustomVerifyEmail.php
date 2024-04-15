<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class CustomVerifyEmail extends VerifyEmail
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
// CustomVerifyEmailクラスのtoMailメソッド内に追加
info($verificationUrl);

        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->markdown('emails.verify_email', [
                'verify_url' => $verificationUrl
            ]);
    }
}

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification;

// class CustomVerifyEmail extends Notification
// {
//     use Queueable;

//     /**
//      * Create a new notification instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         //
//     }

//     /**
//      * Get the notification's delivery channels.
//      *
//      * @param  mixed  $notifiable
//      * @return array
//      */
//     public function via($notifiable)
//     {
//         return ['mail'];
//     }

//     /**
//      * Get the mail representation of the notification.
//      *
//      * @param  mixed  $notifiable
//      * @return \Illuminate\Notifications\Messages\MailMessage
//      */
//     public function toMail($notifiable)
//     {
//         return (new MailMessage)->markdown('emails.verify_email');
//     }

//     /**
//      * Get the array representation of the notification.
//      *
//      * @param  mixed  $notifiable
//      * @return array
//      */
//     public function toArray($notifiable)
//     {
//         return [
//             //
//         ];
//     }
// }
