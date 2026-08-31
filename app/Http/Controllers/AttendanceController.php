<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRegistration;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\BimtekEvent;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Halaman scan kamera untuk peserta/pembicara, atau rekap admin.
     */
    public function scanView(Request $request)
    {
        $user = auth()->user();

        $allEvents = BimtekEvent::where('status', '!=', 'completed')
            ->orderBy('start_date', 'desc')
            ->get();

        $selectedEventId = $request->query('event_id', $allEvents->first()?->id);

        $recentAttendances = [];
        $myAttendances = [];
        $myEvents = [];

        $gatekeeperStatus = [
            'is_allowed' => true,
            'status' => 'ready',
            'missing_items' => [],
            'register_url' => $selectedEventId ? route('events.register', $selectedEventId) : route('events.index'),
        ];

        if ($user->role === 'admin') {
            if ($selectedEventId) {
                $recentAttendances = Attendance::where('event_id', $selectedEventId)
                    ->with(['user', 'event'])
                    ->orderBy('checked_in_at', 'desc')
                    ->get();
            }
        } elseif ($user->role === 'pembicara') {
            // Ambil event penugasan narasumber
            $speaker = \App\Models\Speaker::where('user_id', $user->id)->first();
            $assignedEventIds = $speaker 
                ? \App\Models\EventSpeaker::where('speaker_id', $speaker->id)->pluck('bimtek_event_id')->toArray() 
                : [];

            $myEvents = BimtekEvent::whereIn('id', $assignedEventIds)->get();
            if ($myEvents->isEmpty()) {
                $myEvents = $allEvents;
            }

            if ($myEvents->isNotEmpty() && !$request->has('event_id')) {
                $selectedEventId = $myEvents->first()->id;
            }

            $myAttendances = Attendance::where('user_id', $user->id)
                ->with(['event'])
                ->orderBy('checked_in_at', 'desc')
                ->get();

            // Gatekeeper check untuk narasumber
            if ($selectedEventId) {
                $eventSpeaker = $speaker ? \App\Models\EventSpeaker::where('bimtek_event_id', $selectedEventId)->where('speaker_id', $speaker->id)->first() : null;
                $speakerProfile = \App\Models\SpeakerProfile::where('user_id', $user->id)->first();

                $missing = [];
                if (!$eventSpeaker) $missing[] = 'Konfirmasi Penugasan Sesi Materi';
                if (empty($speakerProfile?->bank_name)) $missing[] = 'Nama Bank Pencairan';
                if (empty($speakerProfile?->account_number)) $missing[] = 'Nomor Rekening Bank';
                if (empty($speakerProfile?->foto_ktp_path)) $missing[] = 'Upload Foto KTP';
                if (empty($speakerProfile?->foto_npwp_path)) $missing[] = 'Upload Foto NPWP';
                if (empty($speakerProfile?->salinan_buku_rekening_path)) $missing[] = 'Upload Salinan Buku Rekening';

                if (count($missing) > 0) {
                    $gatekeeperStatus = [
                        'is_allowed' => false,
                        'status' => 'incomplete_data',
                        'missing_items' => $missing,
                        'message' => 'Anda wajib melengkapi semua data narasumber dan mengunggah berkas persyaratan sebelum dapat melakukan absensi.',
                        'register_url' => route('events.register', $selectedEventId),
                    ];
                }
            }
        } else {
            // Ambil event yang diikuti peserta
            $myEvents = EventRegistration::where('user_id', $user->id)
                ->with('event')
                ->get()
                ->pluck('event')
                ->filter()
                ->values();

            if ($myEvents->isEmpty()) {
                $myEvents = $allEvents;
            }

            if ($myEvents->isNotEmpty() && !$request->has('event_id')) {
                $selectedEventId = $myEvents->first()->id;
            }

            $myAttendances = Attendance::where('user_id', $user->id)
                ->with(['event'])
                ->orderBy('checked_in_at', 'desc')
                ->get();

            // Gatekeeper check untuk peserta
            if ($selectedEventId) {
                $registration = EventRegistration::where('bimtek_event_id', $selectedEventId)
                    ->where('user_id', $user->id)
                    ->first();
                $profile = \App\Models\ParticipantProfile::where('user_id', $user->id)->first();

                $missing = [];
                if (!$registration) {
                    $missing[] = 'Pendaftaran Kegiatan BIMTEK ini';
                }
                if (empty($profile?->nik) && empty($user->nip_nik)) {
                    $missing[] = 'NIK KTP (16 Digit)';
                }
                if (empty($profile?->bank_name)) {
                    $missing[] = 'Nama Bank Pencairan Uang Saku/Transport';
                }
                if (empty($profile?->account_number)) {
                    $missing[] = 'Nomor Rekening Bank';
                }
                if (empty($profile?->account_name)) {
                    $missing[] = 'Nama Pemilik Rekening';
                }

                if (count($missing) > 0) {
                    $gatekeeperStatus = [
                        'is_allowed' => false,
                        'status' => 'incomplete_data',
                        'missing_items' => $missing,
                        'message' => 'Anda wajib mengisi dan melengkapi seluruh data pendaftaran, NIK KTP, serta nomor rekening sebelum dapat mengakses fitur absensi.',
                        'register_url' => route('events.register', $selectedEventId),
                    ];
                }
            }
        }

        return Inertia::render('Attendance/Scan', [
            'events' => $allEvents,
            'myEvents' => $myEvents,
            'selectedEventId' => (int) $selectedEventId,
            'recentAttendances' => $recentAttendances,
            'myAttendances' => $myAttendances,
            'gatekeeperStatus' => $gatekeeperStatus,
        ]);
    }

    /**
     * Admin: Tampilkan QR Code dinamis untuk ditayangkan di proyektor.
     * QR berisi token yang dirotasi setiap interval menit.
     */
    public function adminEventQr(Request $request, $id)
    {
        $event = BimtekEvent::withCount('registrations')->findOrFail($id);
        $interval = (int) $request->query('interval', 10);

        $now = now();
        $session = AttendanceSession::where('event_id', $id)
            ->where('is_active', true)
            ->where('valid_until', '>', $now->copy()->addSeconds(10))
            ->latest()
            ->first();

        if (!$session) {
            AttendanceSession::where('event_id', $id)->update(['is_active' => false]);
            $validFrom = $now;
            $validUntil = $now->copy()->addMinutes($interval);
            $secretKey = Str::random(32);
            $token = hash_hmac('sha256', "event_{$id}_" . $validFrom->timestamp, $secretKey);

            $session = AttendanceSession::create([
                'event_id' => $id,
                'token' => $token,
                'secret_key' => $secretKey,
                'interval_minutes' => $interval,
                'valid_from' => $validFrom,
                'valid_until' => $validUntil,
                'is_active' => true,
            ]);
        }

        $attendancesCount = Attendance::where('event_id', $id)->count();

        return Inertia::render('Attendance/AdminEventQr', [
            'event' => $event,
            'session' => [
                'id' => $session->id,
                'token' => $session->token,
                'interval_minutes' => $session->interval_minutes,
                'valid_from' => $session->valid_from->toIso8601String(),
                'valid_until' => $session->valid_until->toIso8601String(),
                'remaining_seconds' => max(1, $now->diffInSeconds($session->valid_until, false)),
            ],
            'attendancesCount' => $attendancesCount,
        ]);
    }

    /**
     * Admin: Generate sesi QR baru (rotasi token).
     */
    public function generateNewQrSession(Request $request, $id)
    {
        $interval = (int) $request->input('interval', 10);
        $now = now();
        $validFrom = $now;
        $validUntil = $now->copy()->addMinutes($interval);
        $secretKey = Str::random(32);
        $token = hash_hmac('sha256', "event_{$id}_" . $validFrom->timestamp, $secretKey);

        AttendanceSession::where('event_id', $id)->update(['is_active' => false]);

        $session = AttendanceSession::create([
            'event_id' => $id,
            'token' => $token,
            'secret_key' => $secretKey,
            'interval_minutes' => $interval,
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'session' => [
                'id' => $session->id,
                'token' => $session->token,
                'interval_minutes' => $session->interval_minutes,
                'valid_from' => $session->valid_from->toIso8601String(),
                'valid_until' => $session->valid_until->toIso8601String(),
                'remaining_seconds' => max(0, $now->diffInSeconds($session->valid_until, false)),
            ],
        ]);
    }

    /**
     * Peserta/Pembicara: Check-in presensi Hari-H.
     * Bisa via QR scan (token dari QR admin) atau self-verify (tombol 1-klik).
     */
    public function checkIn(Request $request)
    {
        $tokenData = $request->input('token');
        $eventId = $request->input('event_id');
        $method = $request->input('method', 'qr_scan');

        // Auto-extract event_id from QR JSON payload if available
        $parsedToken = null;
        if (!empty($tokenData)) {
            $decoded = json_decode($tokenData, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                if (isset($decoded['event_id'])) {
                    $eventId = (int) $decoded['event_id'];
                }
                if (isset($decoded['token'])) {
                    $parsedToken = $decoded['token'];
                }
            } else {
                $parsedToken = $tokenData;
            }
        }

        if (!$eventId) {
            return back()->with('error', '⚠️ Silakan pilih kegiatan BIMTEK terlebih dahulu atau scan QR Code resmi.');
        }

        $user = auth()->user();
        $event = BimtekEvent::findOrFail($eventId);

        // 1. Validasi token QR jika metode qr_scan
        if ($method === 'qr_scan' && !empty($parsedToken)) {
            $session = AttendanceSession::where('event_id', $eventId)
                ->where('token', $parsedToken)
                ->first();

            if (!$session || !$session->is_active || now()->gt($session->valid_until)) {
                return back()->with('error', '⚠️ QR CODE SUDAH KADALUARSA: Silakan scan QR Code terbaru yang ditampilkan di layar Admin.');
            }
        }

        // 2. Validasi Keikutsertaan / Penugasan Berdasarkan Role
        $registrationId = null;

        if ($user->role === 'pembicara') {
            // A. Khusus Narasumber: Cek penugasan EventSpeaker & kelengkapan administrasi (Bukan EventRegistration)
            $speaker = \App\Models\Speaker::where('user_id', $user->id)->first();
            $eventSpeaker = $speaker ? \App\Models\EventSpeaker::where('bimtek_event_id', $eventId)->where('speaker_id', $speaker->id)->first() : null;
            $speakerProfile = \App\Models\SpeakerProfile::where('user_id', $user->id)->first();

            $hasBank = !empty($speakerProfile?->bank_name) && !empty($speakerProfile?->account_number);
            $hasTopic = !empty($eventSpeaker?->topic);
            $hasDocs = !empty($speakerProfile?->foto_ktp_path) && !empty($speakerProfile?->foto_npwp_path) && !empty($speakerProfile?->salinan_buku_rekening_path);

            if (!$eventSpeaker || !$hasBank || !$hasTopic || !$hasDocs) {
                return redirect()->route('events.register', $eventId)
                    ->with('error', '⚠️ PERHATIAN NARASUMBER: Anda wajib mengonfirmasi penugasan dan mengunggah semua berkas persyaratan (KTP, NPWP, Salinan Buku Rekening) sebelum dapat melakukan absensi.');
            }
        } else {
            // B. Khusus Peserta: Cek pendaftaran di EventRegistration dan kelengkapan data NIK/Rekening
            $registration = EventRegistration::where('bimtek_event_id', $eventId)
                ->where('user_id', $user->id)
                ->first();
            $profile = \App\Models\ParticipantProfile::where('user_id', $user->id)->first();

            $hasNik = !empty($profile?->nik) || !empty($user->nip_nik);
            $hasBank = !empty($profile?->bank_name) && !empty($profile?->account_number);

            if (!$registration || !$hasNik || !$hasBank) {
                return redirect()->route('events.register', $eventId)
                    ->with('error', '⚠️ ABSENSI DITOLAK: Anda wajib mengisi semua data administrasi, NIK KTP, dan rekening pencairan di formulir pendaftaran terlebih dahulu sebelum dapat melakukan absensi.');
            }
            $registrationId = $registration->id;
        }

        // 3. Cek duplikasi presensi
        $existing = Attendance::where('event_id', $eventId)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('success', '✓ ANDA SUDAH PRESENSI: Kehadiran Anda dalam kegiatan ini sudah tercatat sebelumnya pada ' . $existing->checked_in_at->format('d/m/Y H:i') . '.');
        }

        // 4. Catat presensi resmi
        $roleType = ($user->role === 'pembicara') ? 'pembicara' : 'peserta';
        $checkinMethod = ($method === 'self_verify') ? 'self_verify' : 'qr_scan';

        Attendance::create([
            'registration_id' => $registrationId,
            'user_id' => $user->id,
            'event_id' => $eventId,
            'role_type' => $roleType,
            'attendance_type' => 'absensi_hari_h',
            'checkin_method' => $checkinMethod,
            'checked_in_at' => now(),
            'notes' => $roleType === 'pembicara'
                ? 'Presensi Narasumber / Pemateri Kegiatan BIMTEK'
                : ($checkinMethod === 'qr_scan' ? 'Presensi Peserta via Scan QR Code Admin' : 'Presensi Peserta via Verifikasi Mandiri'),
        ]);

        $roleLabel = $roleType === 'pembicara' ? 'Narasumber' : 'Peserta';

        // REAL-TIME BROADCAST: Push attendance event to real-time projector & dashboard & admin reports
        try {
            \App\Services\RealtimeStreamService::pushEvent('AttendanceRecorded', [
                'event_id' => $eventId,
                'user_id' => $user->id,
                'participant_name' => $user->name,
                'role_type' => $roleType,
                'role_label' => $roleLabel,
                'checked_in_at' => now()->format('H:i') . ' WIB',
            ]);

            \App\Services\RealtimeStreamService::pushEvent('ParticipantRegistered', [
                'id' => $registration->id,
                'participant_name' => $user->name,
                'nip_nik' => $user->nip_nik ?? '-',
                'instansi' => $user->instansi ?? 'Umum',
                'jabatan' => $user->jabatan ?? 'Peserta BIMTEK',
                'bimtek_id' => $eventId,
                'bimtek_name' => $event->title,
                'registration_code' => $registration->registration_code,
                'registration_status' => 'APPROVED',
                'registered_at' => now()->format('H:i') . ' WIB',
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Real-time AttendanceRecorded push error: ' . $e->getMessage());
        }

        return back()->with('success', "🎉 PRESENSI BERHASIL! Kehadiran Anda sebagai {$roleLabel} pada kegiatan \"{$event->title}\" telah tercatat.");
    }

    /**
     * Admin: Presensi manual untuk peserta/pembicara.
     */
    public function adminManualCheckIn(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:bimtek_events,id',
            'user_id' => 'required|exists:users,id',
            'notes' => 'required|string|max:500',
        ]);

        $targetUser = User::findOrFail($validated['user_id']);
        $eventId = $validated['event_id'];

        $registration = EventRegistration::firstOrCreate(
            [
                'bimtek_event_id' => $eventId,
                'user_id' => $targetUser->id,
            ],
            [
                'registration_code' => 'MAN-' . strtoupper(Str::random(6)),
                'status' => 'approved',
                'registered_at' => now(),
            ]
        );

        $existing = Attendance::where('event_id', $eventId)
            ->where('user_id', $targetUser->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Peserta/Pembicara ini sudah memiliki catatan presensi.');
        }

        $roleType = ($targetUser->role === 'pembicara') ? 'pembicara' : 'peserta';

        Attendance::create([
            'registration_id' => $registration->id,
            'user_id' => $targetUser->id,
            'event_id' => $eventId,
            'role_type' => $roleType,
            'attendance_type' => 'absensi_manual_admin',
            'checkin_method' => 'manual_admin',
            'verified_by_admin_id' => auth()->id(),
            'checked_in_at' => now(),
            'notes' => '[Presensi Manual Admin] ' . $validated['notes'],
        ]);

        return back()->with('success', "Presensi manual untuk {$targetUser->name} berhasil dicatat.");
    }
}
