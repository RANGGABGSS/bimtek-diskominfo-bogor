import React, { useState, useRef, useEffect } from 'react';
import { 
  Upload, 
  Trash2, 
  RotateCcw, 
  Image as ImageIcon,
  Plus,
  Minus,
  Check
} from 'lucide-react';

export default function ResizableLogoCrud({ 
  defaultSrc = "/images/logo_diskominfo_bogorkab.png", 
  alt = "Logo Instansi", 
  initialWidth = 115, 
  isEditable = true,
  onLogoChange = null
}) {
  const [logoSrc, setLogoSrc] = useState(defaultSrc);
  const [width, setWidth] = useState(initialWidth);
  const [isResizing, setIsResizing] = useState(false);
  const [isSelected, setIsSelected] = useState(false);
  const [isDraggingOver, setIsDraggingOver] = useState(false);
  
  const containerRef = useRef(null);
  const fileInputRef = useRef(null);

  // Resize drag handling (Corner handles like MS Word)
  useEffect(() => {
    const handleMouseMove = (e) => {
      if (!isResizing || !containerRef.current) return;
      const rect = containerRef.current.getBoundingClientRect();
      const newWidth = Math.round(e.clientX - rect.left);
      if (newWidth >= 35 && newWidth <= 600) {
        setWidth(newWidth);
      }
    };

    const handleMouseUp = () => {
      setIsResizing(false);
    };

    if (isResizing) {
      window.addEventListener('mousemove', handleMouseMove);
      window.addEventListener('mouseup', handleMouseUp);
    }

    return () => {
      window.removeEventListener('mousemove', handleMouseMove);
      window.removeEventListener('mouseup', handleMouseUp);
    };
  }, [isResizing]);

  // Click outside to deselect
  useEffect(() => {
    const handleClickOutside = (e) => {
      if (containerRef.current && !containerRef.current.contains(e.target)) {
        setIsSelected(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // DIRECT INSERT / UPDATE: Pick image from device without extra menus
  const handleFileUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
      processFile(file);
    }
  };

  const processFile = (file) => {
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = (event) => {
        const result = event.target?.result;
        if (result) {
          setLogoSrc(result);
          setIsSelected(true);
          if (onLogoChange) onLogoChange(result);
        }
      };
      reader.readAsDataURL(file);
    }
  };

  // DRAG & DROP SUPPORT (DROP IMAGE FROM EXPLORER DIRECTLY)
  const handleDragOver = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setIsDraggingOver(true);
  };

  const handleDragLeave = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setIsDraggingOver(false);
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setIsDraggingOver(false);
    const file = e.dataTransfer.files?.[0];
    if (file) {
      processFile(file);
    }
  };

  // DIRECT DELETE: Instantly remove logo (no modal, completely disappears)
  const handleDeleteLogo = (e) => {
    if (e) e.stopPropagation();
    setLogoSrc(null);
    setIsSelected(false);
    if (onLogoChange) onLogoChange(null);
  };

  // DIRECT RESET: Restore official logo
  const handleResetLogo = (e) => {
    if (e) e.stopPropagation();
    setLogoSrc(defaultSrc);
    setWidth(initialWidth);
    setIsSelected(true);
    if (onLogoChange) onLogoChange(defaultSrc);
  };

  // QUICK SIZE ADJUST
  const changeSize = (delta, e) => {
    if (e) e.stopPropagation();
    setWidth((prev) => Math.max(40, Math.min(500, prev + delta)));
  };

  // If logo is deleted / empty, render zero-width / hidden so it doesn't leave an empty block
  if (!logoSrc) {
    return isEditable ? (
      <div className="inline-flex shrink-0 print:hidden my-auto mr-3">
        <input 
          type="file" 
          ref={fileInputRef} 
          onChange={handleFileUpload} 
          accept="image/*" 
          className="hidden" 
        />
        <button
          type="button"
          onClick={() => fileInputRef.current?.click()}
          onDragOver={handleDragOver}
          onDragLeave={handleDragLeave}
          onDrop={handleDrop}
          className={`px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-900 border-2 border-dashed border-blue-400 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all shadow-xs cursor-pointer ${
            isDraggingOver ? 'ring-4 ring-blue-400 bg-blue-200' : ''
          }`}
          title="Klik untuk memilih file gambar / logo dari komputer atau drag file ke sini"
        >
          <ImageIcon className="w-4 h-4 text-blue-800" />
          <span>+ Sisipkan Logo</span>
        </button>
      </div>
    ) : null;
  }

  return (
    <div 
      ref={containerRef}
      className="relative inline-flex shrink-0 select-none align-middle group"
      onDragOver={handleDragOver}
      onDragLeave={handleDragLeave}
      onDrop={handleDrop}
    >
      {/* HIDDEN FILE INPUT */}
      <input 
        type="file" 
        ref={fileInputRef} 
        onChange={handleFileUpload} 
        accept="image/*" 
        className="hidden" 
      />

      {/* ACTIVE IMAGE CONTAINER WITH WORD-STYLE BOUNDING BOX */}
      <div 
        onClick={() => isEditable && setIsSelected(true)}
        onDoubleClick={() => isEditable && fileInputRef.current?.click()}
        className={`relative inline-flex shrink-0 transition-all cursor-pointer ${
          isSelected && isEditable 
            ? 'ring-2 ring-blue-600 ring-offset-2' 
            : 'hover:ring-2 hover:ring-blue-400/80 hover:ring-offset-1'
        } ${isDraggingOver ? 'ring-4 ring-amber-400 bg-amber-100/50' : ''}`}
        style={{ width: `${width}px` }}
        title={isEditable ? "Klik untuk opsi / Tarik sudut kanan bawah untuk resize / Dobel-klik untuk ganti file" : ""}
      >
        <img 
          src={logoSrc} 
          alt={alt} 
          className="w-full h-auto object-contain pointer-events-none" 
        />

        {/* MS WORD CORNER DRAG HANDLES */}
        {isEditable && (
          <>
            {/* Primary Bottom-Right Resize Handle */}
            <div 
              className="absolute bottom-0 right-0 w-3.5 h-3.5 bg-blue-600 border-2 border-white shadow-md cursor-nwse-resize z-20 print:hidden"
              style={{ 
                transform: 'translate(50%, 50%)',
                borderRadius: '2px'
              }}
              onMouseDown={(e) => {
                e.preventDefault();
                e.stopPropagation();
                setIsResizing(true);
              }}
              title="Tarik sudut ini untuk membesarkan / mengecilkan gambar"
            />

            {/* Top-Right corner point */}
            <div 
              className={`absolute top-0 right-0 w-2.5 h-2.5 bg-blue-600 border border-white z-10 print:hidden transition-opacity ${
                isSelected ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'
              }`}
              style={{ transform: 'translate(50%, -50%)', borderRadius: '1px' }}
            />

            {/* Top-Left corner point */}
            <div 
              className={`absolute top-0 left-0 w-2.5 h-2.5 bg-blue-600 border border-white z-10 print:hidden transition-opacity ${
                isSelected ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'
              }`}
              style={{ transform: 'translate(-50%, -50%)', borderRadius: '1px' }}
            />

            {/* Bottom-Left corner point */}
            <div 
              className={`absolute bottom-0 left-0 w-2.5 h-2.5 bg-blue-600 border border-white z-10 print:hidden transition-opacity ${
                isSelected ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'
              }`}
              style={{ transform: 'translate(-50%, 50%)', borderRadius: '1px' }}
            />
          </>
        )}
      </div>

      {/* PERSISTENT & INTUITIVE WORD FLOATING TOOLBAR ON HOVER OR SELECTION */}
      {isEditable && (
        <div 
          className={`absolute -top-9 left-0 bg-slate-900 text-white px-2 py-1 rounded-lg shadow-xl flex items-center gap-1.5 z-30 print:hidden text-[10px] whitespace-nowrap transition-all duration-150 ${
            isSelected ? 'opacity-100 pointer-events-auto scale-100' : 'opacity-0 group-hover:opacity-100 pointer-events-auto scale-95 group-hover:scale-100'
          }`}
          onClick={(e) => e.stopPropagation()}
        >
          {/* GANTI GAMBAR LANGSUNG */}
          <button
            type="button"
            onClick={() => fileInputRef.current?.click()}
            className="px-2 py-0.5 bg-blue-600 hover:bg-blue-500 rounded font-bold text-white flex items-center gap-1 cursor-pointer transition-colors shadow-xs"
            title="Pilih file gambar lain dari komputer"
          >
            <Upload className="w-3 h-3" />
            <span>Ganti Gambar</span>
          </button>

          <div className="h-3 w-px bg-slate-700"></div>

          {/* PERKECIL & PERBESAR CEPAT */}
          <button
            type="button"
            onClick={(e) => changeSize(-15, e)}
            className="p-1 hover:bg-slate-800 text-slate-300 hover:text-white rounded cursor-pointer"
            title="Perkecil Ukuran Gambar"
          >
            <Minus className="w-3 h-3" />
          </button>
          <span className="font-mono text-[9px] text-slate-400">{width}px</span>
          <button
            type="button"
            onClick={(e) => changeSize(15, e)}
            className="p-1 hover:bg-slate-800 text-slate-300 hover:text-white rounded cursor-pointer"
            title="Perbesar Ukuran Gambar"
          >
            <Plus className="w-3 h-3" />
          </button>

          <div className="h-3 w-px bg-slate-700"></div>

          {/* RESET LOGO DISKOMINFO */}
          <button
            type="button"
            onClick={handleResetLogo}
            className="p-1 hover:bg-slate-800 text-slate-300 hover:text-white rounded cursor-pointer"
            title="Reset ke Logo Bawaan Diskominfo"
          >
            <RotateCcw className="w-3 h-3" />
          </button>

          {/* HAPUS GAMBAR LANGSUNG (TIDAK WAJIB) */}
          <button
            type="button"
            onClick={handleDeleteLogo}
            className="p-1 hover:bg-rose-900/80 text-rose-400 hover:text-rose-200 rounded cursor-pointer transition-colors"
            title="Hapus Logo (Logo Langsung Hilang Bersih)"
          >
            <Trash2 className="w-3 h-3" />
          </button>
        </div>
      )}
    </div>
  );
}
