<?php

namespace App\Models;

use App\Mail\SentOtp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class OtpCode extends Model
{
    use HasFactory;
    protected $fillable = ['email','code'];

    public static function otp_sent($email)
    {
        OtpCode::where('email',$email)->delete();
        $otp = OtpCode::create([
            'email'=>$email,
            'code'=>random_int(100000,999999),
        ]);
        if ($otp) {
            Mail::to($email)->send(new SentOtp($email,$otp));
        }
    }
}
