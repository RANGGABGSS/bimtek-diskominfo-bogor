import React, { useState, useRef, useEffect } from 'react';
import { Search, ChevronDown, CheckCircle2, X } from 'lucide-react';

// ============================================================================
// DAFTAR LENGKAP SELURUH BANK DI INDONESIA (BUMN, BPD, SWASTA, DIGITAL, BPR)
// ============================================================================
export const BANK_LIST = [
  // === BANK PEMERINTAH (BUMN) ===
  'Bank Rakyat Indonesia (BRI)',
  'Bank Mandiri',
  'Bank Negara Indonesia (BNI)',
  'Bank Tabungan Negara (BTN)',
  'Bank Syariah Indonesia (BSI)',

  // === BANK PEMBANGUNAN DAERAH (BPD SELURUH INDONESIA) ===
  'Bank BJB (Jawa Barat & Banten)',
  'Bank BJB Syariah',
  'Bank DKI',
  'Bank DKI Syariah',
  'Bank Jateng (Jawa Tengah)',
  'Bank Jateng Syariah',
  'Bank Jatim (Jawa Timur)',
  'Bank Jatim Syariah',
  'Bank DIY (DI Yogyakarta)',
  'Bank DIY Syariah',
  'Bank BPD Bali',
  'Bank BPD Banten',
  'Bank Sumut (Sumatera Utara)',
  'Bank Sumut Syariah',
  'Bank Nagari (Sumatera Barat)',
  'Bank Nagari Syariah',
  'Bank Riau Kepri Syariah',
  'Bank Jambi',
  'Bank Jambi Syariah',
  'Bank Sumsel Babel (Sumatera Selatan & Bangka Belitung)',
  'Bank Sumsel Babel Syariah',
  'Bank Bengkulu',
  'Bank Lampung',
  'Bank Aceh Syariah',
  'Bank Kalbar (Kalimantan Barat)',
  'Bank Kalbar Syariah',
  'Bank Kalteng (Kalimantan Tengah)',
  'Bank Kalsel (Kalimantan Selatan)',
  'Bank Kalsel Syariah',
  'Bank Kaltimtara (Kalimantan Timur & Kalimantan Utara)',
  'Bank Kaltimtara Syariah',
  'Bank Sulselbar (Sulawesi Selatan & Sulawesi Barat)',
  'Bank Sulselbar Syariah',
  'Bank SulutGo (Sulawesi Utara & Gorontalo)',
  'Bank Sulteng (Sulawesi Tengah)',
  'Bank Sulteng Syariah',
  'Bank Sultra (Sulawesi Tenggara)',
  'Bank NTB Syariah',
  'Bank NTT (Nusa Tenggara Timur)',
  'Bank Maluku Malut (Maluku & Maluku Utara)',
  'Bank Papua (BPD Papua)',

  // === BANK SWASTA NASIONAL & INTERNASIONAL ===
  'Bank Central Asia (BCA)',
  'Bank Central Asia Syariah (BCA Syariah)',
  'Bank CIMB Niaga',
  'Bank CIMB Niaga Syariah',
  'Bank Danamon Indonesia',
  'Bank Danamon Syariah',
  'Bank Permata',
  'Bank Permata Syariah',
  'Bank OCBC Indonesia (d/h OCBC NISP)',
  'Bank OCBC Syariah',
  'Bank Panin',
  'Bank Panin Dubai Syariah',
  'Bank Mega',
  'Bank Mega Syariah',
  'Bank Bukopin (KB Bank)',
  'Bank KB Bukopin Syariah',
  'Bank Sinarmas',
  'Bank Sinarmas Syariah',
  'Bank MNC Internasional (MNC Bank)',
  'Bank Maybank Indonesia',
  'Bank Maybank Syariah Indonesia',
  'Bank UOB Indonesia',
  'Bank BTPN',
  'Bank BTPN Syariah',
  'Bank Muamalat Indonesia',
  'Bank Victoria International',
  'Bank Victoria Syariah',
  'Bank Artha Graha Internasional',
  'Bank Ganesha',
  'Bank Ina Perdana',
  'Bank J Trust Indonesia',
  'Bank Capital Indonesia',
  'Bank Woori Saudara Indonesia 1906 (Bank BWS)',
  'Bank QNB Indonesia',
  'Bank KEB Hana Indonesia (LINE Bank)',
  'Bank Commonwealth',
  'Bank Maspion',
  'Bank Amar Indonesia',
  'Bank Nobu (Nationalnobu)',
  'Bank Oke Indonesia',
  'Bank China Construction Bank Indonesia (CCB Indonesia)',
  'Bank Resona Perdania',
  'Bank Mizuho Indonesia',
  'Bank HSBC Indonesia',
  'Standard Chartered Bank Indonesia',
  'Citibank Indonesia',
  'Deutsche Bank Indonesia',
  'Bank ANZ Indonesia',
  'Bank DBS Indonesia (Digibank)',
  'Bank BNP Paribas Indonesia',
  'Bank Sahabat Sampoerna',
  'Bank Index Selindo',
  'Bank IBK Indonesia',
  'Bank Multiarta Sentosa (Bank MAS)',
  'Bank Mestika Dharma',
  'Bank Shinhan Indonesia',
  'Bank ICBC Indonesia',
  'Bank Mayapada Internasional',
  'Bank Bumi Arta',

  // === BANK DIGITAL INDONESIA ===
  'Bank Neo Commerce (Neobank)',
  'Bank Digital BCA (Blu by BCA Digital)',
  'Bank Jago',
  'Bank Jago Syariah',
  'Allo Bank Indonesia',
  'Bank Raya Indonesia (BRI Agro)',
  'Superbank (d/h Bank Fama)',
  'SeaBank Indonesia',
  'Krom Bank Indonesia',

  // === BANK PERKREDITAN RAKYAT & LAINNYA ===
  'Bank Perkreditan Rakyat (BPR)',
  'Bank Pembiayaan Rakyat Syariah (BPRS)',
  'Bank Lainnya',
];

export default function SearchableBankSelect({ 
  value, 
  onChange, 
  error, 
  placeholder = "Pilih / Cari Nama Bank...",
  required = false,
  className = "" 
}) {
  const [isOpen, setIsOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const dropdownRef = useRef(null);
  const searchInputRef = useRef(null);

  // Close when clicking outside
  useEffect(() => {
    function handleClickOutside(event) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsOpen(false);
        setSearchTerm('');
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // Auto focus input when opened
  useEffect(() => {
    if (isOpen && searchInputRef.current) {
      setTimeout(() => {
        searchInputRef.current?.focus();
      }, 50);
    }
  }, [isOpen]);

  const filteredBanks = BANK_LIST.filter((bank) =>
    bank.toLowerCase().includes(searchTerm.toLowerCase().trim())
  );

  const handleSelect = (bank) => {
    onChange(bank);
    setIsOpen(false);
    setSearchTerm('');
  };

  return (
    <div className={`relative ${className}`} ref={dropdownRef}>
      {/* Trigger Button */}
      <button
        type="button"
        onClick={() => setIsOpen(!isOpen)}
        className={`w-full p-2.5 bg-slate-50 border rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none transition-all cursor-pointer flex items-center justify-between gap-2 text-left
          ${isOpen ? 'border-blue-900 ring-2 ring-blue-900/10 bg-white' : 'border-slate-300 hover:border-slate-400'}
          ${error ? 'border-rose-500 bg-rose-50/20' : ''}`}
      >
        <span className={`truncate ${!value ? 'text-slate-400 font-normal' : 'text-slate-900'}`}>
          {value || placeholder}
        </span>
        <div className="flex items-center gap-1 shrink-0">
          {value && (
            <span 
              onClick={(e) => {
                e.stopPropagation();
                onChange('');
              }}
              className="p-0.5 hover:bg-slate-200 rounded-full text-slate-400 hover:text-slate-600 transition-colors"
              title="Hapus pilihan"
            >
              <X className="w-3 h-3" />
            </span>
          )}
          <ChevronDown className={`w-3.5 h-3.5 text-slate-400 transition-transform duration-200 ${isOpen ? 'rotate-180 text-blue-900' : ''}`} />
        </div>
      </button>

      {/* Dropdown Panel */}
      {isOpen && (
        <div className="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden animate-in fade-in slide-in-from-top-2 duration-150">
          {/* Search Header */}
          <div className="p-2.5 bg-slate-50/80 backdrop-blur-xs border-b border-slate-100 sticky top-0 z-10">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" />
              <input
                ref={searchInputRef}
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Ketik nama bank (contoh: BCA, BRI, BJB)..."
                className="w-full pl-8.5 pr-8 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:border-blue-900 focus:ring-1 focus:ring-blue-900 shadow-xs"
              />
              {searchTerm && (
                <button
                  type="button"
                  onClick={() => setSearchTerm('')}
                  className="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-0.5"
                >
                  <X className="w-3.5 h-3.5" />
                </button>
              )}
            </div>
            <div className="mt-1.5 px-1 flex items-center justify-between text-[10px] text-slate-400 font-medium">
              <span>{filteredBanks.length} bank ditemukan</span>
              <span className="text-blue-900/60">Klik untuk memilih</span>
            </div>
          </div>

          {/* List of Banks */}
          <div className="max-h-60 overflow-y-auto divide-y divide-slate-50 py-1">
            {filteredBanks.length > 0 ? (
              filteredBanks.map((bank) => {
                const isSelected = value === bank;
                return (
                  <button
                    key={bank}
                    type="button"
                    onClick={() => handleSelect(bank)}
                    className={`w-full text-left px-3.5 py-2.5 text-xs transition-colors cursor-pointer flex items-center justify-between gap-2
                      ${isSelected 
                        ? 'bg-blue-50/80 text-blue-950 font-bold border-l-4 border-blue-900' 
                        : 'text-slate-700 hover:bg-slate-50 hover:text-blue-900 font-medium'
                      }`}
                  >
                    <span className="truncate">{bank}</span>
                    {isSelected && (
                      <CheckCircle2 className="w-3.5 h-3.5 text-blue-900 shrink-0" />
                    )}
                  </button>
                );
              })
            ) : (
              <div className="p-6 text-center space-y-2">
                <p className="text-xs text-slate-500 font-medium">
                  Bank "<span className="font-bold text-slate-800">{searchTerm}</span>" tidak ditemukan.
                </p>
                <button
                  type="button"
                  onClick={() => handleSelect(searchTerm ? `Bank ${searchTerm}` : 'Bank Lainnya')}
                  className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-900 text-xs font-bold transition-colors cursor-pointer"
                >
                  <span>Gunakan "{searchTerm || 'Bank Lainnya'}"</span>
                </button>
              </div>
            )}
          </div>
        </div>
      )}

      {error && <p className="text-xs text-rose-600 font-bold mt-1">{error}</p>}
    </div>
  );
}
