<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $details; 

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        if(isset($this->details['theme_id']) && $this->details['theme_id'] == THEME_TWO){
            $this->details['color'] = '#ec7130';
            return $this->subject($this->details['subject'])->markdown('emails.mail-theme-design');
        }elseif(isset($this->details['theme_id']) && $this->details['theme_id'] == THEME_THREE){
            $this->details['color'] = '#46b4b3';
            return $this->subject($this->details['subject'])->markdown('emails.mail-theme-design');
        }else{
            return $this->subject($this->details['subject'])->markdown('emails.mail');
        }
    }
}
