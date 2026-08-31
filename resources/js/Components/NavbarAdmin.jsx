import React, { useState, useRef, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { 
  Search, 
  LogOut, 
  ChevronDown, 
  Menu, 
  X,
  ShieldCheck,
  Award,
  User,
  CheckCircle,
  CreditCard,
  FileCheck,
  LayoutDashboard,
  Calendar,
  Camera,
  History,
  Users,
  Settings,
  MoreHorizontal,
  FileSpreadsheet,
  FileText,
  Sliders,
  ExternalLink
} from 'lucide-react';
import DiskominfoLogo from './DiskominfoLogo';

export default function NavbarAdmin() {
  const { auth } = usePage().props;
  const user = auth?.user || {};
  
  const [profileDropdownOpen, setProfileDropdownOpen] = useState(false);
  const [moreMenuOpen, setMoreMenuOpen] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');

  const moreMenuRef = useRef(null);
  const profileRef = useRef(null);

  // Close dropdowns on outside click
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (moreMenuRef.current && !moreMenuRef.current.contains(event.target)) {
        setMoreMenuOpen(false);
      }
      if (profileRef.current && !profileRef.current.contains(event.target)) {
        setProfileDropdownOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const handleLogout = (e) => {
    if (e) e.preventDefault();
    window.location.href = '/logout';
  };

  const handleQuickSwitch = (role) => {
    window.location.href = `/quick-switch/${role}`;
  };

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      window.location.href = `/events?search=${encodeURIComponent(searchQuery)}`;
    }
  };

  const currentPath = typeof window !== 'undefined' ? window.location.pathname : '';

  return (
    <header className="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-xs transition-all font-sans">
      <div className="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 gap-2 lg:gap-4">
          
          {/* 1. LEFT: OFFICIAL LOGO + ROLE BADGE */}
          <div className="flex items-center gap-2.5 lg:gap-3 shrink-0">
            <Link href="/dashboard" className="flex items-center gap-2 shrink-0 group">
              <DiskominfoLogo variant="light-bg" />
            </Link>
            <span className="hidden sm:inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-900 text-white shadow-xs">
              <ShieldCheck className="w-3 h-3 text-amber-400" />
              <span>Admin</span>
            </span>
          </div>

          {/* 2. CENTER: SLEEK PRIMARY NAVIGATION PILLS (NO OVERFLOW/OVERLAP) */}
          <nav className="hidden xl:flex items-center gap-1 font-bold text-xs">
            
            <Link 
              href="/dashboard" 
              className={`px-3 py-2 rounded-xl whitespace-nowrap flex items-center gap-1.5 transition-all ${
                currentPath === '/dashboard' 
                  ? 'bg-blue-900 text-white font-black shadow-xs' 
                  : 'text-slate-700 hover:bg-slate-100 hover:text-blue-900'
              }`}
            >
              <LayoutDashboard className="w-3.5 h-3.5" />
              <span>Beranda</span>
            </Link>

            <Link 
              href="/events" 
              className={`px-3 py-2 rounded-xl whitespace-nowrap flex items-center gap-1.5 transition-all ${
                currentPath.startsWith('/events') && !currentPath.includes('form-builder')
                  ? 'bg-blue-900 text-white font-black shadow-xs' 
                  : 'text-slate-700 hover:bg-slate-100 hover:text-blue-900'
              }`}
            >
              <Calendar className="w-3.5 h-3.5" />
              <span>Katalog</span>
            </Link>

            <Link 
              href="/admin/verifications" 
              className={`px-3 py-2 rounded-xl whitespace-nowrap flex items-center gap-1.5 transition-all ${
                currentPath.startsWith('/admin/verifications') 
                  ? 'bg-blue-900 text-white font-black shadow-xs' 
                  : 'text-slate-700 hover:bg-slate-100 hover:text-blue-900'
              }`}
            >
              <CheckCircle className="w-3.5 h-3.5 text-emerald-600 shrink-0" />
              <span>Verifikasi</span>
            </Link>

            <Link 
              href="/attendance/scan" 
              className={`px-3 py-2 rounded-xl whitespace-nowrap flex items-center gap-1.5 transition-all ${
                currentPath.startsWith('/attendance') 
                  ? 'bg-blue-900 text-white font-black shadow-xs' 
                  : 'text-slate-700 hover:bg-slate-100 hover:text-blue-900'
              }`}
            >
              <Camera className="w-3.5 h-3.5 text-purple-600 shrink-0" />
              <span>Presensi</span>
            </Link>

            <Link 
              href="/admin/payments" 
              className={`px-3 py-2 rounded-xl whitespace-nowrap flex items-center gap-1.5 transition-all ${
                currentPath.startsWith('/admin/payments') 
                  ? 'bg-blue-900 text-white font-black shadow-xs' 
                  : 'text-slate-700 hover:bg-slate-100 hover:text-blue-900'
              }`}
            >
              <CreditCard className="w-3.5 h-3.5 text-blue-600 shrink-0" />
              <span>Honor & Pajak</span>
            </Link>

            <Link 
              href="/admin/certificates" 
              className={`px-3 py-2 rounded-xl whitespace-nowrap flex items-center gap-1.5 transition-all ${
                currentPath.startsWith('/admin/certificates') 
                  ? 'bg-blue-900 text-white font-black shadow-xs' 
                  : 'text-slate-700 hover:bg-slate-100 hover:text-blue-900'
              }`}
            >
              <FileCheck className="w-3.5 h-3.5 text-indigo-600 shrink-0" />
              <span>Sertifikat</span>
            </Link>

            <Link 
              href="/admin/report-center" 
              className={`px-3 py-2 rounded-xl whitespace-nowrap flex items-center gap-1.5 transition-all ${
                currentPath.startsWith('/admin/report-center') 
                  ? 'bg-amber-400 text-blue-950 font-black ring-2 ring-amber-300 shadow-xs' 
                  : 'text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-200'
              }`}
            >
              <Award className="w-3.5 h-3.5 text-amber-700 shrink-0" />
              <span>Laporan</span>
            </Link>

            {/* MORE MENU DROPDOWN (PREVENTS ANY CLUTTER) */}
            <div className="relative" ref={moreMenuRef}>
              <button
                type="button"
                onClick={() => setMoreMenuOpen(!moreMenuOpen)}
                className={`px-2.5 py-2 rounded-xl whitespace-nowrap flex items-center gap-1 transition-all ${
                  moreMenuOpen ? 'bg-slate-100 text-blue-900' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'
                }`}
                title="Menu Administrasi Tambahan"
              >
                <span>Lainnya</span>
                <ChevronDown className={`w-3.5 h-3.5 transition-transform ${moreMenuOpen ? 'rotate-180' : ''}`} />
              </button>

              {moreMenuOpen && (
                <div className="absolute left-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 z-50 space-y-1 animate-in fade-in-50">
                  <div className="px-2 py-1 text-[10px] font-black uppercase tracking-wider text-slate-400">
                    Modul Tambahan
                  </div>
                  
                  <Link
                    href="/admin/tax-settings"
                    onClick={() => setMoreMenuOpen(false)}
                    className="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition-colors"
                  >
                    <Sliders className="w-4 h-4 text-blue-600" />
                    <span>Tarif Pajak PPh 21</span>
                  </Link>

                  <Link
                    href="/admin/speakers"
                    onClick={() => setMoreMenuOpen(false)}
                    className="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition-colors"
                  >
                    <Users className="w-4 h-4 text-purple-600" />
                    <span>Master Pembicara</span>
                  </Link>

                  <Link
                    href="/admin/event-history"
                    onClick={() => setMoreMenuOpen(false)}
                    className="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition-colors"
                  >
                    <History className="w-4 h-4 text-amber-600" />
                    <span>Riwayat BIMTEK</span>
                  </Link>

                  <div className="border-t border-slate-100 pt-1 mt-1">
                    <Link
                      href="/admin/reports/participants"
                      onClick={() => setMoreMenuOpen(false)}
                      className="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition-colors"
                    >
                      <FileSpreadsheet className="w-4 h-4 text-emerald-600" />
                      <span>Rekap Peserta</span>
                    </Link>
                    <Link
                      href="/admin/reports/speakers"
                      onClick={() => setMoreMenuOpen(false)}
                      className="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition-colors"
                    >
                      <FileText className="w-4 h-4 text-indigo-600" />
                      <span>Rekap Narasumber</span>
                    </Link>
                    <Link
                      href="/admin/reports/honorarium"
                      onClick={() => setMoreMenuOpen(false)}
                      className="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition-colors"
                    >
                      <FileText className="w-4 h-4 text-rose-600" />
                      <span>Cetak Honorarium</span>
                    </Link>
                  </div>
                </div>
              )}
            </div>

          </nav>

          {/* 3. RIGHT: PROFILE DROPDOWN */}
          <div className="flex items-center gap-2 sm:gap-3 shrink-0">

            {/* ADMIN PROFILE BUTTON */}
            <div className="relative" ref={profileRef}>
              <button
                type="button"
                onClick={() => setProfileDropdownOpen(!profileDropdownOpen)}
                className="flex items-center gap-2 p-1.5 pr-2.5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-900 text-xs font-semibold transition-all shadow-xs cursor-pointer"
              >
                <div className="w-8 h-8 rounded-xl bg-blue-900 text-white font-black flex items-center justify-center text-xs shadow-xs relative overflow-hidden shrink-0">
                  {user?.avatar ? (
                    <img src={user.avatar} alt={user?.name} className="w-full h-full object-cover" />
                  ) : (
                    <span>{user?.name?.charAt(0) || 'A'}</span>
                  )}
                  <span className="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-amber-400 text-blue-950 flex items-center justify-center border border-white">
                    <ShieldCheck className="w-2 h-2 text-blue-950 font-bold" />
                  </span>
                </div>
                <div className="hidden sm:block text-left leading-tight">
                  <strong className="block text-xs font-black text-slate-900 truncate max-w-[110px]">
                    {user?.name?.split(' ')[0] || 'Admin'}
                  </strong>
                  <span className="text-[9px] text-amber-700 font-extrabold uppercase">Diskominfo</span>
                </div>
                <ChevronDown className="w-3.5 h-3.5 text-slate-400" />
              </button>

              {profileDropdownOpen && (
                <div className="absolute right-0 mt-2 w-72 bg-white text-slate-900 border border-slate-200 rounded-2xl shadow-xl p-3 z-50 space-y-2 animate-in fade-in-50">
                  
                  {/* ADMIN HEADER */}
                  <div className="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1">
                    <div className="flex items-center gap-2.5">
                      <div className="w-10 h-10 rounded-xl bg-blue-900 text-white font-bold flex items-center justify-center text-sm shrink-0 overflow-hidden border border-slate-200">
                        {user?.avatar ? (
                          <img src={user.avatar} alt={user?.name} className="w-full h-full object-cover" />
                        ) : (
                          <span>{user?.name?.charAt(0) || 'A'}</span>
                        )}
                      </div>
                      <div className="overflow-hidden">
                        <strong className="block text-xs font-black text-slate-900 truncate">{user?.name || 'Administrator'}</strong>
                        <span className="text-[10px] text-amber-700 font-extrabold uppercase flex items-center gap-1">
                          <ShieldCheck className="w-3 h-3 text-amber-500" />
                          <span>Admin Diskominfo</span>
                        </span>
                      </div>
                    </div>
                  </div>

                  <Link 
                    href="/profile" 
                    onClick={() => setProfileDropdownOpen(false)}
                    className="p-2.5 rounded-xl text-xs font-bold text-blue-900 bg-blue-50/70 hover:bg-blue-100 flex items-center gap-2 transition-colors"
                  >
                    <User className="w-4 h-4 text-amber-500" />
                    <span>Profil Saya & Logo</span>
                  </Link>

                  <div className="pt-1 border-t border-slate-100 space-y-0.5 text-xs">
                    <Link 
                      href="/admin/tax-settings" 
                      onClick={() => setProfileDropdownOpen(false)}
                      className="block px-2.5 py-1.5 rounded-lg text-slate-700 hover:bg-slate-50 hover:text-blue-900 font-medium"
                    >
                      Pengaturan Tarif PPh 21
                    </Link>
                    <Link 
                      href="/admin/speakers" 
                      onClick={() => setProfileDropdownOpen(false)}
                      className="block px-2.5 py-1.5 rounded-lg text-slate-700 hover:bg-slate-50 hover:text-blue-900 font-medium"
                    >
                      Master Data Pembicara
                    </Link>
                    <Link 
                      href="/admin/event-history" 
                      onClick={() => setProfileDropdownOpen(false)}
                      className="block px-2.5 py-1.5 rounded-lg text-slate-700 hover:bg-slate-50 hover:text-blue-900 font-medium"
                    >
                      Riwayat BIMTEK
                    </Link>
                  </div>

                  <button
                    onClick={handleLogout}
                    className="w-full text-left p-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-1.5 pt-2 border-t border-slate-100 cursor-pointer"
                  >
                    <LogOut className="w-4 h-4" />
                    <span>Keluar Akun (Logout)</span>
                  </button>
                </div>
              )}
            </div>

            {/* HAMBURGER FOR MOBILE / TABLET */}
            <button
              onClick={() => setMobileOpen(!mobileOpen)}
              className="xl:hidden p-2 rounded-xl bg-slate-100 text-slate-800 border border-slate-200 hover:bg-slate-200 transition-colors"
              aria-label="Buka Menu"
            >
              {mobileOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
            </button>

          </div>

        </div>
      </div>

      {/* MOBILE DRAWER */}
      {mobileOpen && (
        <div className="xl:hidden bg-white border-b border-slate-200 p-4 space-y-2 text-slate-900 shadow-xl font-medium text-xs">
          <Link href="/dashboard" className="block px-3 py-2 rounded-xl hover:bg-slate-50 font-bold">🏠 Beranda</Link>
          <Link href="/events" className="block px-3 py-2 rounded-xl hover:bg-slate-50">📅 Katalog BIMTEK</Link>
          <Link href="/admin/verifications" className="block px-3 py-2 rounded-xl hover:bg-slate-50">🛡️ Verifikasi Data</Link>
          <Link href="/attendance/scan" className="block px-3 py-2 rounded-xl hover:bg-slate-50">📷 Presensi Hari-H</Link>
          <Link href="/admin/payments" className="block px-3 py-2 rounded-xl hover:bg-slate-50">💳 Honor & Pajak</Link>
          <Link href="/admin/certificates" className="block px-3 py-2 rounded-xl hover:bg-slate-50">📁 Sertifikat Digital</Link>
          <Link href="/admin/report-center" className="block px-3 py-2 rounded-xl hover:bg-slate-50">📊 Pusat Laporan</Link>
          <Link href="/admin/tax-settings" className="block px-3 py-2 rounded-xl hover:bg-slate-50">⚙️ Pengaturan PPh 21</Link>
          <Link href="/admin/speakers" className="block px-3 py-2 rounded-xl hover:bg-slate-50">🎤 Master Pembicara</Link>
          <Link href="/profile" className="block px-3 py-2 rounded-xl hover:bg-slate-50 font-bold text-blue-900">👤 Profil Saya</Link>
          
          <button onClick={handleLogout} className="w-full flex items-center gap-2 px-3 py-2.5 rounded-xl text-rose-600 bg-rose-50 border border-rose-200 mt-2 font-bold">
            <LogOut className="w-4 h-4" />
            <span>Keluar Akun (Logout)</span>
          </button>
        </div>
      )}
    </header>
  );
}
