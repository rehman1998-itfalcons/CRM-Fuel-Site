<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class AccountantApproveRecordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail)
    {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {
		$record = \App\Record::find($this->mail['record_id']);
   		$smtp = \App\Smtp::first();
      	$subject = $this->mail['subject'];
      	$body = $this->mail['body'];
      	$delivery_docket = $this->mail['sdd_status'];
      	$bill_of_lading = $this->mail['bol_status'];
      	$invoice_status = $this->mail['invoice_status'];
      	
      
      	$mail = $this->view('emails.accountant_approverecord_email',['body' => $body])->from($smtp->username)->subject($subject);

      	if (!empty($request->dockets[0])) {
          	$dockets = $request->dockets;
          	foreach ($dockets as $key => $docket) {
          	    if (file_exists('public/uploads/records/'.$docket)) {
                    $attachments[] = [
                        'file' => asset('uploads/records/'.$docket)
                    ];
          	    }
            }
            
            if ($attachments != '') {
                foreach($attachments as $attachment) {
                	$mail->attach($attachment['file']);
                }
            }
        }
      	if (!empty($request->lading[0])) {
          	$ladings = $request->lading;
          	foreach ($ladings as $key => $lading) {
          	    if (file_exists('public/uploads/records/'.$lading)) {
                    $attachments[] = [
                        'file' => asset('uploads/records/'.$lading)
                    ];  
          	    }
            }
            if ($attachments != '') {
                foreach($attachments as $attachment) {
                	$mail->attach($attachment['file']);
                }
            }
        }
      
      	if ($invoice_status == 1) {
            $customPaper = array(0,0,680,920);
        	$pdf = \PDF::loadView('invoice', compact('record'))->setPaper($customPaper);
          	$mail->attachData($pdf->output(),'Invoice #'.$record->invoice_number.'.pdf');
        }
      
      	return $mail;
      
    }
}
