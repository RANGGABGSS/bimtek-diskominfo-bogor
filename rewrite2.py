import re

def modify_honorarium_reset():
    filepath = r"C:\Users\User\.gemini\antigravity\scratch\bimtek-diskominfo-bogor\resources\js\Pages\Admin\Reports\HonorariumReport.jsx"
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Fix useEffect
    content = content.replace(
        "useEffect(() => {\n    setTableRows(buildInitialRows(currentEvent));\n    setHasUnsavedChanges(false);\n  }, [currentEvent, template]);",
        "useEffect(() => {\n    setTableRows(buildInitialRows(currentEvent));\n    setHasUnsavedChanges(false);\n  }, [currentEvent, template, payments]);"
    )

    # Fix handleResetHeader
    reset_logic = """  const handleResetHeader = () => {
    if (confirm('Apakah Anda ingin me-reset format header dan data peserta ke bawaan kegiatan?')) {
      handleApplyPreset(PRESETS[0]);
      
      const resetRows = payments.map((p, idx) => ({
          id: p.id || idx + 1,
          no: idx + 1,
          name: p.user?.name || 'Penerima Honor',
          komponen: `${p.component_type} - ${p.volume} ${p.unit}`,
          pajak: `${formatRupiah(p.tax_amount)} (${p.tax_rate_percent}%) / Bersih: ${formatRupiah(p.net_amount)}`,
          isNew: false
      }));
      setTableRows(resetRows.length > 0 ? resetRows : []);
      setHasUnsavedChanges(true);
    }
  };"""
    
    # Replace handleResetHeader using regex
    pattern = r"const handleResetHeader = \(\) => \{[\s\S]*?setHasUnsavedChanges\(true\);\s*\}\s*\};"
    content = re.sub(pattern, reset_logic.strip(), content)

    # Fix presets title to Tanda Terima Honorarium
    content = content.replace("DAFTAR HADIR BIMBINGAN TEKNIS", "TANDA TERIMA HONORARIUM")
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

if __name__ == '__main__':
    modify_honorarium_reset()
