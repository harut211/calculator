<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(FileUploadRequest $request)
    {
        $fileName =$request->file('file')->getClientOriginalName();
        $file =  $request->file('file');
        $fileName = sha1($fileName) . '.' .$file->getClientOriginalExtension();
        $file->storeAs('/public', $fileName);

        return redirect()->route('private',compact('fileName'));
    }
}
