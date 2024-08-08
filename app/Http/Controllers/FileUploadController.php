<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use Illuminate\Support\Facades\Session;

class FileUploadController extends Controller
{
    public function upload(FileUploadRequest $request)
    {
        $input = $request->input('captcha');
        $sessionCaptcha = Session::get('captcha_code');
        if ($input === $sessionCaptcha) {

            $fileName = $request->file('file')->getClientOriginalName();
            $file = $request->file('file');
            $fileName = sha1($fileName) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/public', $fileName);

            return redirect()->route('private', compact('fileName'));

        } else {
            return  back()->with('success', "Captcha verification failed.");
        }


    }

}
