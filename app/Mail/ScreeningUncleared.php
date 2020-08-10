<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Screening;
use App\Answer;

class ScreeningUncleared extends Mailable
{
    use Queueable, SerializesModels;

    public $screening;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Screening $screening)
    {
        $this->screening = $screening;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->screening->user;
        $subject = "Uncleared Screening - {$user->name} ({$user->erp_id})";
        $answers = Answer::where('screening_id', '=', $this->screening->id)->with(['question'])->get();
        $url = env('APP_URL');
        return $this->subject($subject)->markdown('emails.screenings.uncleared', [
            "url" => "{$url}/screenings/{$this->screening->id}",
            "user" => $user,
            "answers" => $answers
        ]);
    }
}
