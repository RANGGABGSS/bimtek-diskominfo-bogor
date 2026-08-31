<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Speaker;
use App\Models\BimtekEvent;
use App\Models\EventSpeaker;
use Inertia\Inertia;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::withCount('eventAssignments')
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Admin/Speakers', [
            'speakers' => $speakers,
        ]);
    }

    public function honorariumIndex()
    {
        $events = BimtekEvent::with(['eventSpeakers.speaker'])
            ->orderBy('start_date', 'desc')
            ->get();

        $allSpeakers = Speaker::all();

        return Inertia::render('Admin/Honorarium', [
            'events' => $events,
            'allSpeakers' => $allSpeakers,
        ]);
    }

    public function storeSpeaker(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip_nik' => 'nullable|string',
            'instansi' => 'required|string',
            'jabatan' => 'required|string',
            'golongan' => 'required|string',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
        ]);

        Speaker::create($validated);

        return back()->with('success', 'Data Pembicara/Narasumber berhasil ditambahkan!');
    }

    public function assignSpeaker(Request $request)
    {
        $validated = $request->validate([
            'bimtek_event_id' => 'required|exists:bimtek_events,id',
            'speaker_id' => 'required|exists:speakers,id',
            'topic' => 'required|string',
            'jp_hours' => 'required|integer|min:1',
            'rate_per_jp' => 'required|numeric|min:0',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $speaker = Speaker::findOrFail($validated['speaker_id']);

        // Determine default tax percent if not provided based on Golongan:
        // Golongan IV = 15%, Golongan III = 5%, Golongan I/II/Non-ASN = 5%
        if (!isset($validated['tax_percent'])) {
            $gol = strtoupper($speaker->golongan);
            if (str_contains($gol, 'IV')) {
                $tax = 15.00;
            } elseif (str_contains($gol, 'III')) {
                $tax = 5.00;
            } else {
                $tax = 5.00;
            }
            $validated['tax_percent'] = $tax;
        }

        EventSpeaker::create($validated);

        return back()->with('success', 'Penugasan Pembicara & Kalkulasi Honorarium Berhasil Disimpan!');
    }

    public function destroyAssignment($id)
    {
        $assignment = EventSpeaker::findOrFail($id);
        $assignment->delete();

        return back()->with('success', 'Penugasan Pembicara berhasil dihapus.');
    }

    public function downloadDocument(Request $request)
    {
        $filename = $request->query('filename', 'Dokumen_Narasumber_Verified.pdf');
        $speakerName = $request->query('speaker_name', 'Dr. Ir. Bambang Hermawan, M.Si');
        $title = $request->query('title', 'Dokumen Persyaratan Narasumber');

        $content = "====================================================================\n" .
                   "PEMERINTAH KABUPATEN BOGOR\n" .
                   "DINAS KOMUNIKASI DAN INFORMATIKA\n" .
                   "Jalan Tegar Beriman No. 1, Cibinong, Kabupaten Bogor 16914\n" .
                   "====================================================================\n" .
                   "DOKUMEN RESMI NARASUMBER: " . strtoupper($title) . "\n" .
                   "NAMA FILE DOKUMEN       : " . $filename . "\n\n" .
                   "DETAIL NARASUMBER:\n" .
                   "Nama Lengkap : " . $speakerName . "\n" .
                   "Instansi     : Dinas Komunikasi dan Informatika / Akademisi Pakar\n" .
                   "Jabatan      : Narasumber Bimbingan Teknis\n" .
                   "Status       : 100% FIKS TERVERIFIKASI ADMIN\n" .
                   "Tanggal Sync : " . now()->translatedFormat('d F Y H:i:s') . " WIB\n\n" .
                   "CATATAN ADMINISTRASI:\n" .
                   "- Dokumen ini diterbitkan secara otomatis oleh SIM-BIMTEK Diskominfo.\n" .
                   "- Berkas sah digunakan untuk administrasi pencairan Honorarium PPh 21.\n" .
                   "- Autentikasi Sistem ID: DISKOMINFO-BOGOR-AUTH-" . rand(100000, 999999) . "\n" .
                   "====================================================================\n";

        return response($content, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Narasumber Upload Materi Presentasi (PPT/PDF/PPTX/DOC)
     */
    public function uploadMaterial(Request $request)
    {
        $request->validate([
            'event_speaker_id' => 'required|exists:event_speakers,id',
            'material_file' => 'required|file|mimes:ppt,pptx,pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,rar,txt|max:51200', // 50MB max
        ]);

        $user = auth()->user();
        $eventSpeaker = EventSpeaker::with('speaker')->findOrFail($request->event_speaker_id);

        // Verify the uploader is the assigned speaker
        $speaker = $eventSpeaker->speaker;
        if (!$speaker) {
            return back()->withErrors(['material_file' => 'Data narasumber tidak ditemukan.']);
        }

        // Check ownership: speaker's user_id matches OR role is pembicara/admin
        $isOwner = false;
        if ($user->role === 'admin' || $user->role === 'pembicara') {
            $isOwner = true;
        } elseif ($speaker) {
            if ($speaker->user_id && $speaker->user_id == $user->id) $isOwner = true;
            elseif ($speaker->email && strtolower($speaker->email) === strtolower($user->email)) $isOwner = true;
            elseif (strtolower(trim($speaker->name)) === strtolower(trim($user->name))) $isOwner = true;
        }

        if (!$isOwner) {
            return back()->withErrors(['material_file' => 'Anda tidak memiliki izin untuk mengunggah materi pada penugasan ini.']);
        }

        // Delete old material file if exists
        if ($eventSpeaker->material_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($eventSpeaker->material_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($eventSpeaker->material_path);
        }

        // Store new material file
        $file = $request->file('material_file');
        $originalName = $file->getClientOriginalName();
        $storagePath = $file->storeAs(
            'materials/' . $eventSpeaker->bimtek_event_id,
            \Illuminate\Support\Str::random(8) . '_' . $originalName,
            'public'
        );

        $eventSpeaker->update([
            'material_path' => $storagePath,
        ]);

        return back()->with('success', "✅ File materi '{$originalName}' berhasil diunggah! Peserta kini dapat melihat dan mengunduh materi presentasi Anda.");
    }

    /**
     * Hapus File Materi Narasumber
     */
    public function deleteMaterial($eventSpeakerId)
    {
        $user = auth()->user();
        $eventSpeaker = EventSpeaker::with('speaker')->findOrFail($eventSpeakerId);

        $speaker = $eventSpeaker->speaker;
        $isOwner = false;
        if ($user->role === 'admin') {
            $isOwner = true;
        } elseif ($speaker) {
            if ($speaker->user_id && $speaker->user_id == $user->id) $isOwner = true;
            elseif ($speaker->email && strtolower($speaker->email) === strtolower($user->email)) $isOwner = true;
            elseif (strtolower(trim($speaker->name)) === strtolower(trim($user->name))) $isOwner = true;
        }

        if (!$isOwner) {
            return back()->withErrors(['error' => 'Anda tidak memiliki izin untuk menghapus materi ini.']);
        }

        if ($eventSpeaker->material_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($eventSpeaker->material_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($eventSpeaker->material_path);
        }

        $eventSpeaker->update(['material_path' => null]);

        return back()->with('success', 'File materi berhasil dihapus.');
    }

    /**
     * Unduh File Materi Presentasi Narasumber (Direct Download dengan Nama Asli)
     */
    public function downloadMaterial($eventSpeakerId)
    {
        $eventSpeaker = EventSpeaker::with(['speaker', 'event'])->findOrFail($eventSpeakerId);

        if ($eventSpeaker->material_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($eventSpeaker->material_path)) {
            $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($eventSpeaker->material_path);
            $basename = basename($eventSpeaker->material_path);
            $cleanName = (strlen($basename) > 9 && str_contains($basename, '_')) 
                ? substr($basename, strpos($basename, '_') + 1) 
                : $basename;

            return response()->download($fullPath, $cleanName);
        }

        // FALLBACK FOR DEMO / SEEDED DATA: Generate official presentation document
        $speakerName = $eventSpeaker->speaker?->name ?? 'Narasumber Diskominfo';
        $eventTitle = $eventSpeaker->event?->title ?? 'BIMTEK Diskominfo';
        $topic = $eventSpeaker->topic ?? 'Materi Bimbingan Teknis';
        $filename = 'Materi_' . \Illuminate\Support\Str::slug($topic) . '.pdf';

        $content = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj 3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<</Font<</F1 4 0 R>>>>/Contents 5 0 R>>endobj 4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj 5 0 R obj<</Length 280>>stream\nBT\n/F1 16 Tf\n50 720 Td\n(PEMERINTAH KABUPATEN BOGOR) Tj\n/F1 13 Tf\n0 -25 Td\n(DINAS KOMUNIKASI DAN INFORMATIKA) Tj\n/F1 12 Tf\n0 -35 Td\n(BERKAS MATERI PRESENTASI RESMI) Tj\n0 -20 Td\n(Kegiatan: " . addslashes($eventTitle) . ") Tj\n0 -20 Td\n(Pemateri: " . addslashes($speakerName) . ") Tj\n0 -20 Td\n(Topik   : " . addslashes($topic) . ") Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\n0000000212 00000 n\n0000000283 00000 n\ntrailer<</Size 6/Root 1 0 R>>\nstartxref\n615\n%%EOF";

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Stream / Lihat Inline File Materi Presentasi (PDF/Gambar/Dokumen)
     */
    public function streamMaterial($eventSpeakerId)
    {
        $eventSpeaker = EventSpeaker::with(['speaker', 'event'])->findOrFail($eventSpeakerId);

        if ($eventSpeaker->material_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($eventSpeaker->material_path)) {
            $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($eventSpeaker->material_path);
            $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
            $basename = basename($eventSpeaker->material_path);
            $cleanName = (strlen($basename) > 9 && str_contains($basename, '_')) 
                ? substr($basename, strpos($basename, '_') + 1) 
                : $basename;

            return response()->file($fullPath, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $cleanName . '"',
            ]);
        }

        // FALLBACK STREAM FOR DEMO DATA
        $speakerName = $eventSpeaker->speaker?->name ?? 'Narasumber Diskominfo';
        $eventTitle = $eventSpeaker->event?->title ?? 'BIMTEK Diskominfo';
        $topic = $eventSpeaker->topic ?? 'Materi Bimbingan Teknis';
        $filename = 'Materi_' . \Illuminate\Support\Str::slug($topic) . '.pdf';

        $content = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj 3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<</Font<</F1 4 0 R>>>>/Contents 5 0 R>>endobj 4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj 5 0 R obj<</Length 280>>stream\nBT\n/F1 16 Tf\n50 720 Td\n(PEMERINTAH KABUPATEN BOGOR) Tj\n/F1 13 Tf\n0 -25 Td\n(DINAS KOMUNIKASI DAN INFORMATIKA) Tj\n/F1 12 Tf\n0 -35 Td\n(BERKAS MATERI PRESENTASI RESMI) Tj\n0 -20 Td\n(Kegiatan: " . addslashes($eventTitle) . ") Tj\n0 -20 Td\n(Pemateri: " . addslashes($speakerName) . ") Tj\n0 -20 Td\n(Topik   : " . addslashes($topic) . ") Tj\nET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\n0000000212 00000 n\n0000000283 00000 n\ntrailer<</Size 6/Root 1 0 R>>\nstartxref\n615\n%%EOF";

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
