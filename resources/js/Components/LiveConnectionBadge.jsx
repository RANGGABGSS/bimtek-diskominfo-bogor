import React from 'react';
import { Wifi, WifiOff } from 'lucide-react';

export default function LiveConnectionBadge({ isConnected = true, className = '' }) {
  return (
    <div 
      className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold border transition-all ${
        isConnected
          ? 'bg-emerald-50 text-emerald-800 border-emerald-300 shadow-xs dark:bg-emerald-950/80 dark:text-emerald-300 dark:border-emerald-700'
          : 'bg-amber-50 text-amber-800 border-amber-300 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-700'
      } ${className}`}
      title={isConnected ? 'Koneksi real-time aktif (SSE / WebSocket)' : 'Koneksi terputus. Mencoba menghubungkan kembali...'}
    >
      <span className="relative flex h-2 w-2">
        {isConnected ? (
          <>
            <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
          </>
        ) : (
          <span className="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
        )}
      </span>
      <span>{isConnected ? 'Live • Terhubung' : 'Offline • Menghubungkan...'}</span>
    </div>
  );
}
