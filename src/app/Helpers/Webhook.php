<?php

namespace App\Helpers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class Webhook
{
    public function send(array $data): Response
    {
        return Http::asForm()->post(env('CLIENT_HOST_URL'), $data);
    }
}
