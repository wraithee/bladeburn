<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendSecretReadNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $email,
        public string $hash
        ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::raw("Your secret with hash {$this->hash} was succesfully read", function($message) {
            $message->to($this->email)->subject('BladeBurn read notification');
        });
    }
}
