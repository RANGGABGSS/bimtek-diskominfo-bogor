import React from 'react';

export default function DiskominfoLogo({ variant = 'dark-bg', className = '' }) {
  return (
    <div className={`flex items-center shrink-0 ${className}`}>
      {/* SINGLE CLEAN OFFICIAL DISKOMINFO LOGO IMAGE */}
      <img 
        src="/images/logo_diskominfo_bogorkab.png" 
        alt="Logo Diskominfo Kabupaten Bogor" 
        className="h-10 md:h-11 object-contain shrink-0" 
      />
    </div>
  );
}
