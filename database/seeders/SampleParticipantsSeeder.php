<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ParticipantProfile;
use App\Models\EventRegistration;
use App\Models\BimtekEvent;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SampleParticipantsSeeder extends Seeder
{
    public function run()
    {
        $events = BimtekEvent::all();
        if ($events->isEmpty()) {
            $event = BimtekEvent::create([
                'title' => 'BIMTEK PENGELOLAAN KEAMANAN INFORMASI & SPBE',
                'slug' => 'bimtek-pengelolaan-keamanan-informasi-spbe',
                'description' => 'Bimbingan teknis peningkatan kompetensi aparatur pemerintah dalam pengelolaan keamanan informasi dan sistem pemerintahan berbasis elektronik.',
                'start_date' => '2026-08-11 08:00:00',
                'end_date' => '2026-08-11 16:00:00',
                'location' => 'Hotel New Pesona Anggraini Cisarua, Bogor',
                'quota' => 100,
                'status' => 'active',
            ]);
            $events = collect([$event]);
        }

        // 35 realistic participants from Bogor
        $participantsData = array_slice([
            ['name' => 'Ahmad Fauzi, S.Kom.', 'instansi' => 'SDN Cibinong 01', 'district' => 'Kec. Cibinong'],
            ['name' => 'Siti Rahmawati, S.Pd.', 'instansi' => 'SMPN 1 Sukaraja', 'district' => 'Kec. Sukaraja'],
            ['name' => 'Budi Santoso, M.Pd.', 'instansi' => 'SDN Cisarua 02', 'district' => 'Kec. Cisarua'],
            ['name' => 'Dewi Sartika, S.AP.', 'instansi' => 'Bappedalitbang Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Hendra Gunawan, S.Kom.', 'instansi' => 'Diskominfo Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Nurul Hidayah, S.IP.', 'instansi' => 'Dinas Pendidikan Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Rizky Pratama, S.Tr.Kom.', 'instansi' => 'SMPN 2 Cibinong', 'district' => 'Kec. Cibinong'],
            ['name' => 'Sri Wahyuni, S.Pd., M.M.', 'instansi' => 'SDN Gunung Putri 01', 'district' => 'Kec. Gunung Putri'],
            ['name' => 'Agus Setiawan, S.E.', 'instansi' => 'BKPSDM Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Rina Marlina, S.Sos.', 'instansi' => 'Kecamatan Ciawi', 'district' => 'Kec. Ciawi'],
            ['name' => 'Dedi Supriadi, S.Kom.', 'instansi' => 'SMPN 1 Ciawi', 'district' => 'Kec. Ciawi'],
            ['name' => 'Maya Anggraini, S.Kom.', 'instansi' => 'Diskominfo Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Fajar Nugraha, S.T.', 'instansi' => 'Kecamatan Sukaraja', 'district' => 'Kec. Sukaraja'],
            ['name' => 'Putri Lestari, S.Pd.', 'instansi' => 'SDN Babakan Madang 01', 'district' => 'Kec. Babakan Madang'],
            ['name' => 'Arif Rahman, S.Kom.', 'instansi' => 'Dinas Kesehatan Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Yuni Astuti, S.Pd.', 'instansi' => 'SMPN 1 Bojonggede', 'district' => 'Kec. Bojonggede'],
            ['name' => 'Eko Prasetyo, S.Kom.', 'instansi' => 'Kecamatan Citeureup', 'district' => 'Kec. Citeureup'],
            ['name' => 'Tri Wulandari, S.AP.', 'instansi' => 'RSUD Cibinong', 'district' => 'Kec. Cibinong'],
            ['name' => 'Bambang Irawan, S.Sos.', 'instansi' => 'Kecamatan Kemang', 'district' => 'Kec. Kemang'],
            ['name' => 'Mega Pratiwi, S.Kom.', 'instansi' => 'SDN Bojonggede 03', 'district' => 'Kec. Bojonggede'],
            ['name' => 'Irvan Maulana, S.T.', 'instansi' => 'Dinas PUPR Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Anisa Fitriani, S.Pd.', 'instansi' => 'SMPN 1 Megamendung', 'district' => 'Kec. Megamendung'],
            ['name' => 'Wahyu Hidayat, S.Kom.', 'instansi' => 'RSUD Ciawi', 'district' => 'Kec. Ciawi'],
            ['name' => 'Lilis Suryani, S.Pd.', 'instansi' => 'SDN Sukaraja 02', 'district' => 'Kec. Sukaraja'],
            ['name' => 'Aditya Permana, S.Tr.Kom.', 'instansi' => 'Diskominfo Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Rini Indrawati, S.AP.', 'instansi' => 'Kecamatan Cisarua', 'district' => 'Kec. Cisarua'],
            ['name' => 'Gilang Ramadhan, S.Kom.', 'instansi' => 'SMPN 3 Cibinong', 'district' => 'Kec. Cibinong'],
            ['name' => 'Dian Kusuma, S.Pd.', 'instansi' => 'SDN Ciawi 01', 'district' => 'Kec. Ciawi'],
            ['name' => 'Bayu Saputra, S.Kom.', 'instansi' => 'Kecamatan Cibinong', 'district' => 'Kec. Cibinong'],
            ['name' => 'Fitri Handayani, S.Sos.', 'instansi' => 'Dinas Sosial Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Lukman Hakim, S.Kom.', 'instansi' => 'SMPN 1 Citeureup', 'district' => 'Kec. Citeureup'],
            ['name' => 'Nurlina Dewi, S.Pd.', 'instansi' => 'SDN Citeureup 01', 'district' => 'Kec. Citeureup'],
            ['name' => 'Surya Pratama, S.T.', 'instansi' => 'Bapenda Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Ratna Sari, S.Kom.', 'instansi' => 'Diskominfo Kab. Bogor', 'district' => 'Kec. Cibinong'],
            ['name' => 'Imam Syafi\'i, S.Pd.I.', 'instansi' => 'SMPN 1 Babakan Madang', 'district' => 'Kec. Babakan Madang'],
        ], 0, 35);

        // 1. Clean existing sample users first to avoid duplicates
        User::where('email', 'like', 'sample_peserta_%')->delete();

        // 2. Insert 35 sample participants
        $rowsForTemplate = [];
        $no = 1;

        foreach ($participantsData as $idx => $data) {
            $email = "sample_peserta_" . ($idx + 1) . "@bogorkab.go.id";
            
            $user = User::create([
                'name' => $data['name'],
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'user',
                'instansi' => $data['instansi'],
                'jabatan' => $data['district'],
            ]);

            ParticipantProfile::create([
                'user_id' => $user->id,
                'nik' => '3201' . str_pad(100000000000 + $idx, 12, '0', STR_PAD_LEFT),
                'instansi' => $data['instansi'],
                'no_hp' => '08' . rand(1111111111, 9999999999),
                'verification_status' => 'terverifikasi',
            ]);

            foreach ($events as $ev) {
                EventRegistration::create([
                    'bimtek_event_id' => $ev->id,
                    'user_id' => $user->id,
                    'registration_code' => 'REG-BMK-' . strtoupper(Str::random(6)),
                    'status' => 'approved',
                    'registered_at' => now(),
                ]);
            }

            $rowsForTemplate[] = [
                'id' => $user->id,
                'no' => $no,
                'name' => $data['name'],
                'school' => $data['instansi'],
                'district' => $data['district'],
                'isNew' => false,
            ];

            $no++;
        }

        // 3. Update all DocumentTemplates so the custom_rows directly displays the 35 sample participants
        $templates = DocumentTemplate::all();
        foreach ($templates as $t) {
            $hc = $t->header_config ?? [];
            $hc['custom_rows'] = $rowsForTemplate;
            $t->header_config = $hc;
            $t->save();
        }

        echo "Successfully generated 35 sample participants linked to all BIMTEK events!\n";
    }
}
