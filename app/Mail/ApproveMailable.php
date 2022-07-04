<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveMailable extends Mailable
{
    use Queueable, SerializesModels;
    
    public $module_name,
     $model,
     $class,
     $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($module_name, $model_id, $class)
    {
        $this->module_name = $module_name;
        $this->model = $class::find($model_id);
        $this->subject = "Aprobar {$this->module_name}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.approve');
    }
}
