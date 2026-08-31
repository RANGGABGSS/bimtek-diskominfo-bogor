import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { 
  Home, 
  BookOpen, 
  QrCode, 
  Award, 
  History,
  ShieldCheck,
  User
} from 'lucide-react';

export default function MobileBottomNav() {
  const { auth } = usePage().props;
  const user = auth?.user;
  const isAdmin = user?.role === 'admin';
  const currentPath = window.location.pathname;

  return (
    <div className="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-xl border-t border-slate-200/90 shadow-2xl px-3 py-1.5 flex items-center justify-around font-sans pb-safe print:hidden">
      
      {/* BERANDA */}
      <Link
        href="/dashboard"
        className={`flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all ${
          currentPath === '/dashboard' || currentPath === '/'
            ? 'text-blue-900 font-black'
            : 'text-slate-500 hover:text-slate-900 font-medium'
        }`}
      >
        <Home className={`w-5 h-5 ${currentPath === '/dashboard' || currentPath === '/' ? 'text-blue-900 scale-110' : 'text-slate-400'}`} />
        <span className="text-[10px] mt-0.5 tracking-tight">Beranda</span>
      </Link>

      {/* KATALOG */}
      <Link
        href="/events"
        className={`flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all ${
          currentPath.startsWith('/events')
            ? 'text-blue-900 font-black'
            : 'text-slate-500 hover:text-slate-900 font-medium'
        }`}
      >
        <BookOpen className={`w-5 h-5 ${currentPath.startsWith('/events') ? 'text-blue-900 scale-110' : 'text-slate-400'}`} />
        <span className="text-[10px] mt-0.5 tracking-tight">Katalog</span>
      </Link>

      {/* FLOATING SCAN QR ACTION BUTTON */}
      <Link
        href="/attendance/scan"
        className="flex flex-col items-center justify-center -mt-5 transition-transform active:scale-95"
      >
        <div className="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-950 via-blue-900 to-amber-500 text-white flex items-center justify-center shadow-lg border-2 border-white">
          <QrCode className="w-6 h-6 text-amber-300" />
        </div>
        <span className={`text-[10px] mt-0.5 ${currentPath.startsWith('/attendance') ? 'text-blue-900 font-black' : 'text-slate-600 font-bold'}`}>
          Presensi
        </span>
      </Link>

      {/* SERTIFIKAT */}
      <Link
        href={isAdmin ? '/admin/report-center' : '/my-certificates'}
        className={`flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all ${
          currentPath.startsWith('/my-certificates') || currentPath.startsWith('/admin/report-center')
            ? 'text-blue-900 font-black'
            : 'text-slate-500 hover:text-slate-900 font-medium'
        }`}
      >
        <Award className={`w-5 h-5 ${currentPath.startsWith('/my-certificates') || currentPath.startsWith('/admin/report-center') ? 'text-blue-900 scale-110' : 'text-slate-400'}`} />
        <span className="text-[10px] mt-0.5 tracking-tight">Sertifikat</span>
      </Link>

      {/* RIWAYAT / PROFIL */}
      {isAdmin ? (
        <Link
          href="/admin/event-history"
          className={`flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all ${
            currentPath.startsWith('/admin/event-history')
              ? 'text-blue-900 font-black'
              : 'text-slate-500 hover:text-slate-900 font-medium'
          }`}
        >
          <History className={`w-5 h-5 ${currentPath.startsWith('/admin/event-history') ? 'text-blue-900 scale-110' : 'text-slate-400'}`} />
          <span className="text-[10px] mt-0.5 tracking-tight">Riwayat</span>
        </Link>
      ) : (
        <Link
          href="/profile"
          className={`flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all ${
            currentPath === '/profile'
              ? 'text-blue-900 font-black'
              : 'text-slate-500 hover:text-slate-900 font-medium'
          }`}
        >
          <User className={`w-5 h-5 ${currentPath === '/profile' ? 'text-blue-900 scale-110' : 'text-slate-400'}`} />
          <span className="text-[10px] mt-0.5 tracking-tight">Profil</span>
        </Link>
      )}

    </div>
  );
}
