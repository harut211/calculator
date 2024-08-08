<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function show()
    {

        $captcha_code = $this->generateRandomString();

        Session::put('captcha_code', $captcha_code);
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 200, 200, 200);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $line_color = imagecolorallocate($image, 30, 30, 30);

        imagefilledrectangle($image, 0, 0, 200, 50, $background_color);

        for ($i = 0; $i < 8; $i++) {
            imageline($image, 0, rand(0, 50), 200, rand(0, 50), $line_color);
        }

        $font = public_path('fonts/paint/Paintingwithchocolate-K5mo.ttf');
        imagettftext($image, 20, rand(-10, 10), 30, 35, $text_color, $font, $captcha_code);


        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
    }

    private function generateRandomString($length = 6)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
