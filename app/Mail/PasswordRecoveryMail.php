<?php
 
namespace App\Mail;
 
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 
class PasswordRecoveryMail extends Mailable
{
    use Queueable, SerializesModels;
 
    /**
     * The order instance.
     *
     * @var \App\Models\User
     */
    public $user;
 

    public function __construct(User $user)
    {
        $this->user = $user;
    }
 
    public function build()
    {
        return $this->subject('Recovery Password Mail')
                    ->view('emails.password-recovery');
    }
}