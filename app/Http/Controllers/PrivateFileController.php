<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenUjian;
use App\Models\KajurSubmissionFile;
use App\Models\PermintaanPembimbing;
use App\Models\SubmissionFile;
use App\Models\UndanganUjian;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    public function view($type, $id)
    {
        $path = $this->resolvePath($type, $id);

        return Storage::disk('local')->response($path, basename($path));
    }

    public function download($type, $id)
    {
        $path = $this->resolvePath($type, $id);

        return Storage::disk('local')->download($path, basename($path));
    }

    private function resolvePath($type, $id)
    {
        $model = match ($type) {
            'submission-file' => SubmissionFile::findOrFail($id),
            'kajur-submission-file' => KajurSubmissionFile::findOrFail($id),
            'dokumen-ujian' => DokumenUjian::findOrFail($id),
            'undangan-ujian' => UndanganUjian::findOrFail($id),
            'permintaan-pembimbing' => PermintaanPembimbing::findOrFail($id),
            default => abort(404),
        };

        $this->authorize('view', $model);

        $path = $type === 'permintaan-pembimbing'
            ? $model->bukti_acc_path
            : $model->file_path;

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return $path;
    }
}
