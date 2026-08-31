import re

def modify_honorarium():
    filepath = r"C:\Users\User\.gemini\antigravity\scratch\bimtek-diskominfo-bogor\resources\js\Pages\Admin\Reports\HonorariumReport.jsx"
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Regex to find buildInitialRows block and replace it
    pattern = r"const buildInitialRows = \(ev\) => \{[\s\S]*?\];\s*\};"
    
    new_build_rows = """const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number || 0);
  };

  const buildInitialRows = (ev) => {
    if (savedHeader.custom_rows && Array.isArray(savedHeader.custom_rows) && savedHeader.custom_rows.length > 0) {
      return savedHeader.custom_rows;
    }

    if (payments && payments.length > 0) {
        return payments.map((p, idx) => ({
          id: p.id || idx + 1,
          no: idx + 1,
          name: p.user?.name || 'Penerima Honor',
          komponen: `${p.component_type} - ${p.volume} ${p.unit}`,
          pajak: `${formatRupiah(p.tax_amount)} (${p.tax_rate_percent}%) / Bersih: ${formatRupiah(p.net_amount)}`,
          isNew: false
        }));
    }

    return [
      { id: 1, no: 1, name: 'IRZI (Narasumber)', komponen: 'Honorarium Narasumber 2 JP', pajak: 'PPh 21 5% / Bersih: Rp570.000', isNew: false },
      { id: 2, no: 2, name: 'RANGGA BAGAS (Moderator)', komponen: 'Honorarium Moderator 1 JP', pajak: 'PPh 21 5% / Bersih: Rp380.000', isNew: false },
    ];
  };"""

    content = re.sub(pattern, new_build_rows, content)
    
    # Let's also fix the preset templates
    content = content.replace("DAFTAR HADIR BIMBINGAN TEKNIS", "TANDA TERIMA HONORARIUM BIMBINGAN TEKNIS")
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

if __name__ == '__main__':
    modify_honorarium()
