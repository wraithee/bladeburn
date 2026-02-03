<?php

namespace App\Http\Controllers\Api;

use App\Models\Secret;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSecretRequest;
use App\Jobs\SendSecretReadNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SecretController extends Controller
{
    public function store(StoreSecretRequest $request)
    {
        try {
            $secret = Secret::create([
                'content' => Crypt::encryptString($request->input('message')),
                'hash' => Str::random(16),
                'notification_email' => $request->input('email'),
                'expires_at' => Carbon::now()->addHours(24),
            ]);
            
            return response()->json([
                'status' => 'success',
                'link' => url("/view/{$secret->hash}"),
                'expires_at' => $secret->expires_at
            ], 201);
            
        } catch (\Exception $e) {
            logger()->error("Error creating secret message: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Cannot create secret message. Try again later'
            ], 500);
        }
    }

    public function show($hash)
    {
        $secret = Secret::where('hash', $hash)->first();

        if (!$secret) {
            return response()->json([
                'message' => 'message not found or was already read'
            ], 404);
        }

        if ($secret->expires_at->isPast()) {
            $secret->delete();
            return response()->json([
                'message' => 'link lifetime expired'
            ], 410);
        }

        $decryptedMessage = Crypt::decryptString($secret->content);

        $secret->delete();

        if ($secret->notification_email) {
            SendSecretReadNotification::dispatch($secret->notification_email, $secret->hash);
        }

        return response()->json([
            'message' => $decryptedMessage,
            'deleted' => true
        ]);
    }
}
