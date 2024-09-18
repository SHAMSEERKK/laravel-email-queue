<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 5; //number of retries
    private $emailDetails;
    /**
     * Create a new job instance.
     */
    public function __construct($emailDetails)
    {
        $this->emailDetails = $emailDetails;
    }

    /**
     * Execute the job.
     */
   

    public function handle()
    {
        try {
            // Attempt to send the email
            Mail::to($this->emailDetails['to'])->send(new NotificationMail($this->emailDetails));
        } catch (\Exception $e) {
            // Log the exception, if email sending fails within handle()
            Log::error("Email sending failed: " . $e->getMessage());
            throw $e; // Rethrow the exception to trigger retry/failure handling
        }
    }
    /** * Handle a job failure.
     */
    public function failed(\Exception $exception)
    {
        // Log the failure for debugging purposes
        Log::error("SendEmailJob failed: " . $exception->getMessage());
        // Example: Send an alert email to the admin about the failure 
        $adminEmail = 'admin@timesworld.com';
        Mail::raw("Job failed for email: " . $this->emailDetails['to'] . ". Error: " . $exception->getMessage(), function ($message) use ($adminEmail) {
            $message->to($adminEmail)
                ->subject("SendEmailJob Failed");
        });
    }
}
