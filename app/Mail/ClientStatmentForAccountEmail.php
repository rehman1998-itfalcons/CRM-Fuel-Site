<?php

namespace App\Mail;

use App\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Record;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;


class ClientStatmentForAccountEmail extends Mailable
{
    use Queueable, SerializesModels;
    //public $mail;
    protected $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdf)
    {
        //$this->mail = $mail;
        $this->pdf = $pdf;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {

// dd($this->pdf);
        $smtp = \App\Smtp::first();
        $subject = "Client statement For bank";
        $mail = $this->view('emails.ClientStatmentForAccountEmail', ['body' => $smtp->body])->from($smtp->username)->subject($subject)->attachData($this->pdf->output(), 'Report.pdf');
        
       // dd($mail);
        return $mail;
    }
}
