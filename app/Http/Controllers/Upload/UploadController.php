<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'documents' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('documents');

        $tempId = Str::uuid();
        $extension = $file->getClientOriginalExtension();

        $file->storeAs('tmp', "{$tempId}.{$extension}", 'local');

        return response($tempId, 200)
            ->header('Content-Type', 'text/plain');
    }

    public function revert(Request $request)
    {
        $tempId = trim($request->getContent(), " \t\n\r\0\x0B\"'");

        if (!Str::isUuid($tempId)) {
            return response('Invalid ID', 422);
        }

        $files = Storage::disk('local')->files('tmp');
        foreach ($files as $file) {
            if (str_contains($file, $tempId)) {
                Storage::disk('local')->delete($file);
                break;
            }
        }

        return response('', 204);
    }
}