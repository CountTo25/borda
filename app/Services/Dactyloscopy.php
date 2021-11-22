<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Dactyloscopy
{
    public function make(): string
    {
        return Hash::make($this->getFingerPrint());
    }

    public function check(?string $val): bool
    {
        return $val !== null && Hash::check($this->getFingerPrint(), $val);
    }

    private function getFingerPrint(): string
    {
        $request = request();
        return $request->userAgent().$request->ip().($request->cookie('LARABA_RANDOM') ?? Str::random(5));
    }
}
