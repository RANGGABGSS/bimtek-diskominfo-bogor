<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ParticipantProfile;
use App\Models\SpeakerProfile;

class DocumentStreamController extends Controller
{
    public function stream(Request $request)
    {
        $user = auth()->user();
        $type = $request->query('type'); // 'ktp' or 'npwp'
        $role = $request->query('role'); // 'peserta' or 'pembicara'
        $profileId = $request->query('id');

        // Only admin or the profile owner can stream sensitive identity documents
        if ($user->role !== 'admin') {
            if ($role === 'peserta') {
                $profile = ParticipantProfile::where('id', $profileId)->where('user_id', $user->id)->first();
            } else {
                $profile = SpeakerProfile::where('id', $profileId)->where('user_id', $user->id)->first();
            }

            if (!$profile) {
                abort(403, 'Akses ditolak ke dokumen sensitif ini.');
            }
        } else {
            if ($role === 'peserta') {
                $profile = ParticipantProfile::find($profileId);
            } else {
                $profile = SpeakerProfile::find($profileId);
            }
        }

        if (!$profile) {
            abort(404, 'Profil tidak ditemukan.');
        }

        $filePath = $type === 'ktp' ? $profile->foto_ktp_path : $profile->foto_npwp_path;

        if (!$filePath || !Storage::disk('local')->exists($filePath)) {
            abort(404, 'Dokumen belum diunggah atau tidak ditemukan.');
        }

        return response()->file(Storage::disk('local')->path($filePath));
    }
}
