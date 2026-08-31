import re

def add_export_honorarium():
    filepath = r"C:\Users\User\.gemini\antigravity\scratch\bimtek-diskominfo-bogor\app\Http\Controllers\ReportCenterController.php"
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    export_logic = """
    public function exportHonorariumExcel(Request $request)
    {
        $eventId = $request->query('event_id');

        $event = BimtekEvent::with(['payments.user'])
            ->find($eventId) ?? BimtekEvent::with(['payments.user'])->first();

        $template = DocumentTemplate::where('template_code', 'HONORARIUM_EVENT_' . $eventId)->first() 
                 ?? DocumentTemplate::where('template_code', 'HONORARIUM_OFFICIAL')->first();
        $header = $template?->header_config ?? [];

        $reportTitle = $header['report_title'] ?? ('TANDA TERIMA HONORARIUM ' . ($event ? strtoupper($event->title) : 'BIMBINGAN TEKNIS'));
        $prog = $header['program_code_name'] ?? '2.16.03 Program Pengelolaan Aplikasi Informatika';
        $keg = $header['kegiatan_code_name'] ?? '2.16.03.2.02 Pengelolaan E-Government Di Lingkup Pemerintah Daerah Kabupaten/Kota';
        $subKeg = $header['sub_kegiatan_code_name'] ?? '2.16.03.2.02.0035 Koordinasi dan Fasilitasi Promosi Literasi SPBE dan/atau Kolaborasi Penyelenggaraan SPBE';
        $acara = $header['acara'] ?? ($event ? $event->title : 'Bimbingan Teknis Artificial Intelligence (AI)');
        $tanggal = $header['tanggal'] ?? ($event && $event->start_date ? $event->start_date->translatedFormat('d F Y') : '11 Agustus 2026');
        $tempat = $header['tempat'] ?? ($event && $event->location ? $event->location : 'Hotel New Pesona Anggraini Kec. Cisarua Kab. Bogor');
        $colSchool = $header['col_school_label'] ?? 'Uraian / Komponen';
        $colDistrict = $header['col_district_label'] ?? 'Pajak & Honor Bersih';

        $filename = "Honorarium_" . str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9 ]/', '', $acara)) . ".csv";

        $output = "========================================================================================\\n";
        $output .= "{$reportTitle}\\n";
        $output .= "========================================================================================\\n";
        $output .= "Kode/Program       : {$prog}\\n";
        $output .= "Kode/Kegiatan      : {$keg}\\n";
        $output .= "Kode/Sub. Kegiatan : {$subKeg}\\n";
        $output .= "Acara              : {$acara}\\n";
        $output .= "Tanggal            : {$tanggal}\\n";
        $output .= "Tempat             : {$tempat}\\n";
        $output .= "========================================================================================\\n\\n";

        $output .= "No,Nama,{$colSchool},{$colDistrict},Tandatangan\\n";

        $no = 1;
        
        $payments = \\App\\Models\\Payment::with('user')->where('event_id', $event->id ?? $eventId)->get();

        if ($payments && $payments->count() > 0) {
            foreach ($payments as $p) {
                $u = $p->user;
                $komponen = "{$p->component_type} - {$p->volume} {$p->unit}";
                $pajak = "PPh {$p->tax_amount} / Bersih: {$p->net_amount}";
                $sigNumber = $no;

                $output .= implode(',', [
                    $no,
                    '"' . str_replace('"', '""', $u->name ?? 'Penerima Honor') . '"',
                    '"' . str_replace('"', '""', $komponen) . '"',
                    '"' . str_replace('"', '""', $pajak) . '"',
                    '"' . ($no % 2 === 1 ? ($sigNumber . ' ..........') : ('.......... ' . $sigNumber)) . '"'
                ]) . "\\n";

                $no++;
            }
        }

        return response($output, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}"""
    
    # Replace the last closing brace of the class
    content = re.sub(r'\}\s*$', export_logic, content)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

if __name__ == '__main__':
    add_export_honorarium()
