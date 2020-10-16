<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Test;
use App\User;

class PositiveTests extends Mailable
{
    use Queueable, SerializesModels;

    public $tests;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $tests)
    {
        $this->tests = $tests;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "New Positive COVID-19 Tests";
        $from_email = env('MAIL_FROM_ADDRESS');
        $from_name = env('MAIL_FROM_NAME');
        $positive_tests = Test::join('users as u', 'u.id', '=', 'tests.user_id')
            ->select('tests.*')
            ->whereIn('tests.id', $this->tests)
            ->orderBy('u.last_name')->orderBy('u.first_name')
            ->with('user')
            ->get();
        
        return $this->from($from_email, $from_name)->subject($subject)->markdown('emails.tests.positive', [
            "positive_tests" => $positive_tests
        ]);
    }
}
