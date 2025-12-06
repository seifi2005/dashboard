<?php

namespace App\Services;

use App\Models\OtpCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function generateOtp(string $mobile): array
    {
        // حذف OTP های قبلی استفاده نشده
        OtpCode::where('mobile', $mobile)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->delete();

        // تولید کد 6 رقمی
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // ذخیره OTP
        $otp = OtpCode::create([
            'mobile' => $mobile,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(5),
            'used' => false,
        ]);

        // TODO: ارسال SMS
        Log::info("OTP Code for {$mobile}: {$code}");

        return [
            'success' => true,
            'message' => 'OTP code sent successfully',
            'code' => $code, // در production حذف کن
        ];
    }

    public function verifyOtp(string $mobile, string $code): bool
    {
        $otp = OtpCode::where('mobile', $mobile)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($otp && $otp->isValid()) {
            $otp->markAsUsed();
            return true;
        }

        return false;
    }
}

