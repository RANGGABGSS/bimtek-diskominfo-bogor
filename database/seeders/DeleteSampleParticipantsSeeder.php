<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DocumentTemplate;

class DeleteSampleParticipantsSeeder extends Seeder
{
    public function run()
    {
        // 1. Delete all sample participants
        $deletedCount = User::where('email', 'like', 'sample_peserta_%')->delete();

        // 2. Restore initial baseline 3 participants
        $baselineRows = [
            ['id' => 1, 'no' => 1, 'name' => 'IRZI', 'school' => 'UMUM', 'district' => 'KEC. CIBINONG', 'isNew' => false],
            ['id' => 2, 'no' => 2, 'name' => 'RANGGA BAGAS SETIAWAN', 'school' => 'UMUM', 'district' => 'KEC. CIBINONG', 'isNew' => false],
            ['id' => 3, 'no' => 3, 'name' => 'UDIN', 'school' => 'UMUM', 'district' => 'KEC. CIBINONG', 'isNew' => false],
        ];

        // 3. Update all DocumentTemplates
        $templates = DocumentTemplate::all();
        foreach ($templates as $t) {
            $hc = $t->header_config ?? [];
            $hc['custom_rows'] = $baselineRows;
            $t->header_config = $hc;
            $t->save();
        }

        echo "Successfully deleted {$deletedCount} sample participants and restored initial 3 baseline participants (IRZI, RANGGA BAGAS SETIAWAN, UDIN)!\n";
    }
}
