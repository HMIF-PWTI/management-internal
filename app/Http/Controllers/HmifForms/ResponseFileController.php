<?php

namespace App\Http\Controllers\HmifForms;

use App\Http\Controllers\Controller;
use App\Models\HmifFormResponseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseFileController extends Controller
{
    public function __invoke(Request $request, HmifFormResponseFile $file): StreamedResponse
    {
        $file->loadMissing('response.form');
        abort_unless($file->response->form->owner_id === $request->user()->id, 403);
        abort_unless(Storage::disk($file->disk)->exists($file->path), 404);

        return Storage::disk($file->disk)->download($file->path, $file->original_name, ['Content-Type' => $file->mime_type]);
    }
}
