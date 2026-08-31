<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Speaker;
use App\Models\BimtekEvent;
use App\Models\EventSpeaker;
use App\Models\FormField;
use App\Models\EventRegistration;
use App\Models\RegistrationAnswer;
use App\Models\Attendance;
use App\Models\DocumentTemplate;
use App\Models\ParticipantProfile;
use App\Models\SpeakerProfile;
use App\Models\TaxParameter;
use App\Models\PaymentComponent;
use App\Models\Certificate;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TAX PARAMETERS
        TaxParameter::create([
            'category_name' => 'ASN Golongan IV',
            'has_npwp' => true,
            'tax_rate_percent' => 15.00,
            'description' => 'Pajak PPh 21 untuk Pegawai Negeri Sipil Golongan IV (15%)',
        ]);

        TaxParameter::create([
            'category_name' => 'ASN Golongan III',
            'has_npwp' => true,
            'tax_rate_percent' => 5.00,
            'description' => 'Pajak PPh 21 untuk Pegawai Negeri Sipil Golongan III (5%)',
        ]);

        TaxParameter::create([
            'category_name' => 'Non-ASN / Umum',
            'has_npwp' => true,
            'tax_rate_percent' => 2.50,
            'description' => 'Pajak PPh 21 Non-ASN / Pakar Umum (2.5%)',
        ]);

        // 2. CREATE USERS (Admin, Peserta, Pembicara)
        $admin = User::create([
            'name' => 'Administrator Diskominfo',
            'email' => 'admin@bogorkab.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nip_nik' => '198503122010011002',
            'instansi' => 'Dinas Komunikasi dan Informatika Kab. Bogor',
            'jabatan' => 'Kepala Bidang Aplikasi Informatika (APTIKA)',
            'no_hp' => '081298765432',
        ]);

        $peserta1 = User::create([
            'name' => 'Reza Fahdi Faisal, S.Kom',
            'email' => 'peserta@bogorkab.go.id',
            'password' => Hash::make('password'),
            'role' => 'user',
            'nip_nik' => '199208152020011005',
            'instansi' => 'Dinas Komunikasi dan Informatika Kab. Bogor',
            'jabatan' => 'Pranata Komputer Ahli Pertama',
            'no_hp' => '085711223344',
        ]);

        ParticipantProfile::create([
            'user_id' => $peserta1->id,
            'nik' => '3201021508920005',
            'foto_ktp_path' => null,
            'npwp' => '73.019.284.1-404.000',
            'foto_npwp_path' => null,
            'bank_name' => 'Bank Jabar Banten (BJB)',
            'account_number' => '0019283746101',
            'account_name' => 'Reza Fahdi Faisal',
            'instansi' => 'Dinas Komunikasi dan Informatika Kab. Bogor',
            'no_hp' => '085711223344',
            'verification_status' => 'terverifikasi',
            'verification_notes' => 'Dokumen NIK & Rekening BJB telah disesuaikan',
        ]);

        $peserta2 = User::create([
            'name' => 'Siti Nurhaliza, S.STP',
            'email' => 'siti.nurhaliza@bogorkab.go.id',
            'password' => Hash::make('password'),
            'role' => 'user',
            'nip_nik' => '199405202019032008',
            'instansi' => 'Diskominfo Kabupaten Bogor',
            'jabatan' => 'Pengelola Data Informasi Publik',
            'no_hp' => '081377889900',
        ]);

        ParticipantProfile::create([
            'user_id' => $peserta2->id,
            'nik' => '3201042005940008',
            'foto_ktp_path' => null,
            'npwp' => '81.402.192.5-404.000',
            'foto_npwp_path' => null,
            'bank_name' => 'Bank Jabar Banten (BJB)',
            'account_number' => '0018837462002',
            'account_name' => 'Siti Nurhaliza',
            'instansi' => 'Diskominfo Kabupaten Bogor',
            'no_hp' => '081377889900',
            'verification_status' => 'terverifikasi',
            'verification_notes' => 'Data sesuai',
        ]);

        $pembicaraUser = User::create([
            'name' => 'Dr. Ir. Bambang Hermawan, M.Si',
            'email' => 'pembicara@bogorkab.go.id',
            'password' => Hash::make('password'),
            'role' => 'pembicara',
            'nip_nik' => '197501102002121001',
            'instansi' => 'Badan Siber dan Sandi Negara (BSSN) RI',
            'jabatan' => 'Widyaiswara Ahli Utama',
            'no_hp' => '081122334455',
        ]);

        SpeakerProfile::create([
            'user_id' => $pembicaraUser->id,
            'nip_nik' => '197501102002121001',
            'npwp' => '12.345.678.9-404.000',
            'bank_name' => 'Bank Jabar Banten (BJB)',
            'account_number' => '0012984711001',
            'account_name' => 'Bambang Hermawan',
            'instansi' => 'Badan Siber dan Sandi Negara (BSSN)',
            'jabatan' => 'Widyaiswara Ahli Utama',
            'golongan' => 'Golongan IV',
            'verification_status' => 'terverifikasi',
            'verification_notes' => 'Validasi SK Widyaiswara & NPWP selesai',
        ]);

        // 3. CREATE SPEAKERS MASTER DATA
        $speaker1 = Speaker::create([
            'user_id' => $pembicaraUser->id,
            'name' => 'Dr. Ir. Bambang Hermawan, M.Si',
            'nip_nik' => '197501102002121001',
            'instansi' => 'Badan Siber dan Sandi Negara (BSSN)',
            'jabatan' => 'Widyaiswara Ahli Utama',
            'golongan' => 'Golongan IV',
            'email' => 'pembicara@bogorkab.go.id',
            'no_hp' => '081122334455',
            'bank_name' => 'Bank Jabar Banten (BJB)',
            'account_number' => '0012984711001',
            'account_name' => 'Bambang Hermawan',
        ]);

        $speaker2 = Speaker::create([
            'name' => 'Dr. Ratna Juwita, S.T., M.T.',
            'nip_nik' => '198204122008012004',
            'instansi' => 'Institut Pertanian Bogor (IPB University)',
            'jabatan' => 'Dosen S2 Sistem Informasi & Data Science',
            'golongan' => 'Golongan III',
            'email' => 'ratna.juwita@ipb.ac.id',
            'no_hp' => '081299887766',
            'bank_name' => 'Bank Mandiri',
            'account_number' => '1330019283741',
            'account_name' => 'Ratna Juwita',
        ]);

        $speaker3 = Speaker::create([
            'name' => 'Ahmad Fauzi, S.Kom',
            'nip_nik' => '32010219950002',
            'instansi' => 'Asosiasi Software Engineer Indonesia (ASEI)',
            'jabatan' => 'Senior Cloud Security Architect',
            'golongan' => 'Non-ASN',
            'email' => 'ahmad.fauzi@asei.or.id',
            'no_hp' => '085611224455',
            'bank_name' => 'Bank Central Asia (BCA)',
            'account_number' => '8830192847',
            'account_name' => 'Ahmad Fauzi',
        ]);

        // 4. CREATE BIMTEK EVENTS
        $event1 = BimtekEvent::create([
            'title' => 'BIMTEK Digital Government & Keamanan Siber SPBE 2026',
            'slug' => 'bimtek-digital-government-keamanan-siber-spbe-2026',
            'description' => 'Bimbingan Teknis Peningkatan Kapasitas SDM Aparatur Pemerintah Kabupaten Bogor dalam Penerapan Arsitektur SPBE & Proteksi Data Siber Kedinasan.',
            'start_date' => now()->addDays(2)->setHour(8)->setMinute(0),
            'end_date' => now()->addDays(3)->setHour(16)->setMinute(0),
            'location' => 'Auditorium Gedung D Diskominfo Kab. Bogor, Jl. Tegar Beriman Cibinong',
            'quota' => 60,
            'status' => 'open',
        ]);

        $event2 = BimtekEvent::create([
            'title' => 'Pelatihan Pengelolaan Portal Satu Data Kabupaten Bogor',
            'slug' => 'pelatihan-pengelolaan-portal-satu-data-kabupaten-bogor',
            'description' => 'Pelatihan Integrasi Data Sektoral Perangkat Daerah ke Portal Resmi Satu Data Indonesia (SDI) Kabupaten Bogor.',
            'start_date' => now()->addDays(10)->setHour(9)->setMinute(0),
            'end_date' => now()->addDays(10)->setHour(15)->setMinute(0),
            'location' => 'Laboratorium Komputer Lantai 2 Diskominfo Kab. Bogor',
            'quota' => 40,
            'status' => 'open',
        ]);

        // 5. EVENT SPEAKERS & HONORARIUM ASSIGNMENT
        EventSpeaker::create([
            'bimtek_event_id' => $event1->id,
            'speaker_id' => $speaker1->id,
            'topic' => 'Arsitektur SPBE & Audit Keamanan Informasi Kedinasan',
            'jp_hours' => 4,
            'rate_per_jp' => 400000,
            'tax_percent' => 15.00,
        ]);

        EventSpeaker::create([
            'bimtek_event_id' => $event1->id,
            'speaker_id' => $speaker2->id,
            'topic' => 'Manajemen Privasi Data & Penanganan Insiden Siber (CSIRT)',
            'jp_hours' => 3,
            'rate_per_jp' => 350000,
            'tax_percent' => 5.00,
        ]);

        EventSpeaker::create([
            'bimtek_event_id' => $event2->id,
            'speaker_id' => $speaker3->id,
            'topic' => 'Standarisasi Data Metadata JSON & API OpenData',
            'jp_hours' => 5,
            'rate_per_jp' => 300000,
            'tax_percent' => 5.00,
        ]);

        // 6. DYNAMIC FORM FIELDS FOR EVENT 1
        $field1 = FormField::create([
            'bimtek_event_id' => $event1->id,
            'field_label' => 'Upload Surat Tugas / ST Pimpinan (PDF/JPG)',
            'field_type' => 'file',
            'field_options' => null,
            'is_required' => true,
            'order_index' => 1,
        ]);

        $field2 = FormField::create([
            'bimtek_event_id' => $event1->id,
            'field_label' => 'Tingkat Kemampuan Siber Saat Ini',
            'field_type' => 'radio',
            'field_options' => ['Pemula / Dasar', 'Menengah (Intermediate)', 'Mahir / Advance'],
            'is_required' => true,
            'order_index' => 2,
        ]);

        $field3 = FormField::create([
            'bimtek_event_id' => $event1->id,
            'field_label' => 'Pengalaman Menangani Aplikasi Kedinasan',
            'field_type' => 'select',
            'field_options' => ['Kurang dari 1 Tahun', '1 - 3 Tahun', 'Lebih dari 3 Tahun'],
            'is_required' => true,
            'order_index' => 3,
        ]);

        // 7. EVENT REGISTRATIONS & ANSWERS
        $reg1 = EventRegistration::create([
            'bimtek_event_id' => $event1->id,
            'user_id' => $peserta1->id,
            'registration_code' => 'BMK-2026-00188',
            'status' => 'approved',
            'registered_at' => now()->subHours(5),
        ]);

        RegistrationAnswer::create([
            'registration_id' => $reg1->id,
            'form_field_id' => $field1->id,
            'answer_value' => 'Surat_Tugas_Reza_2026.pdf',
        ]);

        RegistrationAnswer::create([
            'registration_id' => $reg1->id,
            'form_field_id' => $field2->id,
            'answer_value' => 'Menengah (Intermediate)',
        ]);

        RegistrationAnswer::create([
            'registration_id' => $reg1->id,
            'form_field_id' => $field3->id,
            'answer_value' => 'Lebih dari 3 Tahun',
        ]);

        $reg2 = EventRegistration::create([
            'bimtek_event_id' => $event1->id,
            'user_id' => $peserta2->id,
            'registration_code' => 'BMK-2026-00189',
            'status' => 'approved',
            'registered_at' => now()->subHours(2),
        ]);

        RegistrationAnswer::create([
            'registration_id' => $reg2->id,
            'form_field_id' => $field1->id,
            'answer_value' => 'Surat_Tugas_Siti_2026.pdf',
        ]);

        RegistrationAnswer::create([
            'registration_id' => $reg2->id,
            'form_field_id' => $field2->id,
            'answer_value' => 'Pemula / Dasar',
        ]);

        // 8. ATTENDANCE LOGS
        Attendance::create([
            'registration_id' => $reg1->id,
            'user_id' => $peserta1->id,
            'event_id' => $event1->id,
            'role_type' => 'peserta',
            'attendance_type' => 'absensi_masuk',
            'checkin_method' => 'qr_scan',
            'checked_in_at' => now()->subMinutes(45),
            'notes' => 'Presensi Dynamic QR Code Hari-H',
        ]);

        Attendance::create([
            'registration_id' => $reg2->id,
            'user_id' => $peserta2->id,
            'event_id' => $event1->id,
            'role_type' => 'peserta',
            'attendance_type' => 'absensi_masuk',
            'checkin_method' => 'manual_admin',
            'verified_by_admin_id' => $admin->id,
            'checked_in_at' => now()->subMinutes(30),
            'notes' => 'Presensi Manual oleh Admin (Kamera HP kendala)',
        ]);

        // 9. PAYMENT COMPONENTS (HONORARIUM & TRANSPORT / UANG JALAN)
        // Speaker Honorarium
        PaymentComponent::create([
            'event_id' => $event1->id,
            'user_id' => $pembicaraUser->id,
            'recipient_type' => 'pembicara',
            'component_type' => 'honorarium',
            'volume' => 4,
            'unit' => 'Jam Pelatihan',
            'unit_price' => 400000,
            'gross_amount' => 1600000,
            'tax_rate_percent' => 15.00,
            'tax_amount' => 240000,
            'net_amount' => 1360000,
            'bank_name' => 'Bank Jabar Banten (BJB)',
            'account_number' => '0012984711001',
            'account_name' => 'Bambang Hermawan',
            'payment_status' => 'verified',
            'payment_date' => now(),
            'notes' => 'Honorarium Widyaiswara Golongan IV',
        ]);

        // Speaker Transport
        PaymentComponent::create([
            'event_id' => $event1->id,
            'user_id' => $pembicaraUser->id,
            'recipient_type' => 'pembicara',
            'component_type' => 'uang_jalan',
            'volume' => 1,
            'unit' => 'Kegiatan',
            'unit_price' => 500000,
            'gross_amount' => 500000,
            'tax_rate_percent' => 0,
            'tax_amount' => 0,
            'net_amount' => 500000,
            'bank_name' => 'Bank Jabar Banten (BJB)',
            'account_number' => '0012984711001',
            'account_name' => 'Bambang Hermawan',
            'payment_status' => 'verified',
            'payment_date' => now(),
            'notes' => 'Uang Jalan & Transport Lokal Narasumber',
        ]);

        // Participant Transport
        PaymentComponent::create([
            'event_id' => $event1->id,
            'user_id' => $peserta1->id,
            'recipient_type' => 'peserta',
            'component_type' => 'transport',
            'volume' => 1,
            'unit' => 'Kegiatan',
            'unit_price' => 200000,
            'gross_amount' => 200000,
            'tax_rate_percent' => 0,
            'tax_amount' => 0,
            'net_amount' => 200000,
            'bank_name' => 'Bank Jabar Banten (BJB)',
            'account_number' => '0019283746101',
            'account_name' => 'Reza Fahdi Faisal',
            'payment_status' => 'paid',
            'payment_date' => now(),
            'notes' => 'Uang Transport Peserta BIMTEK Diskominfo',
        ]);

        // 10. CERTIFICATES
        Certificate::create([
            'event_id' => $event1->id,
            'user_id' => $peserta1->id,
            'role_type' => 'peserta',
            'certificate_number' => 'SERT-BMK/2026/001',
            'file_path' => 'certificates/sertifikat_reza_bmk1.pdf',
            'issue_date' => now()->toDateString(),
        ]);

        Certificate::create([
            'event_id' => $event1->id,
            'user_id' => $pembicaraUser->id,
            'role_type' => 'pembicara',
            'certificate_number' => 'SERT-NRS/2026/001',
            'file_path' => 'certificates/sertifikat_bambang_nrs1.pdf',
            'issue_date' => now()->toDateString(),
        ]);

        // 11. DOCUMENT TEMPLATES
        DocumentTemplate::create([
            'template_code' => 'REKAP_ABSENSI',
            'template_name' => 'Laporan Rekapitulasi Presensi & Kehadiran Peserta BIMTEK',
            'header_config' => [
                'kop_title' => 'PEMERINTAH KABUPATEN BOGOR',
                'sub_title' => 'DINAS KOMUNIKASI DAN INFORMATIKA',
                'address' => 'Jl. Tegar Beriman, Komplek Pemkab Bogor, Cibinong 16914',
                'contact' => 'Telp. (021) 8754533 | Website: diskominfo.bogorkab.go.id',
            ],
            'body_html' => '<div>BERITA ACARA PRESENSI</div>',
            'signee_nama' => 'Drs. Bambang Widodo Tawekal, M.Si.',
            'signee_nip' => '197108151996031004',
            'signee_jabatan' => 'Kepala Dinas Komunikasi dan Informatika',
        ]);
    }
}
