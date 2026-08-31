import React, { useState } from 'react';
import { useForm, Link } from '@inertiajs/react';
import { ArrowRight, Eye, EyeOff } from 'lucide-react';

export default function RegisterPeserta() {
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);

  const { data, setData, post, processing, errors } = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    nip_nik: '',
    instansi: '',
    jabatan: '',
    no_hp: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    post('/register/peserta');
  };

  return (
    <div className="min-h-screen bg-slate-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans selection:bg-blue-900 selection:text-white">
      
      {/* HEADER LOGO */}
      <div className="sm:mx-auto sm:w-full sm:max-w-xl text-center space-y-2">
        <Link href="/" className="inline-block">
          <img 
            src="/images/logo_diskominfo_bogorkab.png" 
            alt="Logo Diskominfo Kabupaten Bogor" 
            className="h-14 md:h-16 object-contain mx-auto drop-shadow-xs" 
          />
        </Link>

        <h2 className="text-xl font-black text-slate-900 tracking-tight uppercase">
          MANAJEMEN BIMBINGAN TEKNIS
        </h2>
        <p className="text-xs font-extrabold text-blue-900 uppercase tracking-wider">
          Formulir Pendaftaran Akun Peserta
        </p>
      </div>

      {/* FORM CONTAINER (GENERAL USER & OFFICE WORKER FRIENDLY) */}
      <div className="mt-6 sm:mx-auto sm:w-full sm:max-w-xl px-4 sm:px-0">
        <div className="bg-white border border-slate-200 shadow-xl rounded-3xl p-6 sm:p-10 space-y-6">
          
          <form className="space-y-4" onSubmit={handleSubmit}>
            
            {/* BARIS 1: NAMA LENGKAP & NO. KTP / NIK */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div className="space-y-1.5">
                <label className="block text-xs font-extrabold text-slate-800">Nama Lengkap *</label>
                <div className="relative">
                  <input
                    type="text"
                    required
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20 transition-all"
                    placeholder="Rangga"
                  />
                </div>
                {errors.name && <p className="text-xs text-rose-600 font-bold">{errors.name}</p>}
              </div>

              <div className="space-y-1.5">
                <label className="block text-xs font-extrabold text-slate-800">No. KTP / NIK (Opsional)</label>
                <div className="relative">
                  <input
                    type="text"
                    value={data.nip_nik}
                    onChange={(e) => setData('nip_nik', e.target.value)}
                    className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20 transition-all"
                    placeholder="3201011656525520"
                  />
                </div>
                {errors.nip_nik && <p className="text-xs text-rose-600 font-bold">{errors.nip_nik}</p>}
              </div>
            </div>

            {/* BARIS 2: EMAIL AKTIF & NO WHATSAPP */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div className="space-y-1.5">
                <label className="block text-xs font-extrabold text-slate-800">Email Aktif *</label>
                <div className="relative">
                  <input
                    type="email"
                    required
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20 transition-all"
                    placeholder="rangga@gmail.com"
                  />
                </div>
                {errors.email && <p className="text-xs text-rose-600 font-bold">{errors.email}</p>}
              </div>

              <div className="space-y-1.5">
                <label className="block text-xs font-extrabold text-slate-800">Nomor WhatsApp / HP *</label>
                <div className="relative">
                  <input
                    type="text"
                    required
                    value={data.no_hp}
                    onChange={(e) => setData('no_hp', e.target.value)}
                    className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20 transition-all"
                    placeholder="088215651665"
                  />
                </div>
              </div>
            </div>

            {/* BARIS 3: PERUSAHAAN / KANTOR & PEKERJAAN / JABATAN */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div className="space-y-1.5">
                <label className="block text-xs font-extrabold text-slate-800">Perusahaan / Kantor / Umum</label>
                <div className="relative">
                  <input
                    type="text"
                    value={data.instansi}
                    onChange={(e) => setData('instansi', e.target.value)}
                    className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20 transition-all"
                    placeholder="PT Utama / Kantor / Umum"
                  />
                </div>
              </div>

              <div className="space-y-1.5">
                <label className="block text-xs font-extrabold text-slate-800">Pekerjaan / Jabatan</label>
                <div className="relative">
                  <input
                    type="text"
                    value={data.jabatan}
                    onChange={(e) => setData('jabatan', e.target.value)}
                    className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20 transition-all"
                    placeholder="Staff / Karyawan / Umum"
                  />
                </div>
              </div>
            </div>

            {/* BARIS 4: PASSWORD & KONFIRMASI PASSWORD */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div className="space-y-1.5">
                <label className="block text-xs font-extrabold text-slate-800">Password *</label>
                <div className="relative">
                  <input
                    type={showPassword ? "text" : "password"}
                    required
                    value={data.password}
                    onChange={(e) => setData('password', e.target.value)}
                    className="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20 transition-all"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700"
                  >
                    {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                  </button>
                </div>
                {errors.password && <p className="text-xs text-rose-600 font-bold">{errors.password}</p>}
              </div>

              <div className="space-y-1.5">
                <label className="block text-xs font-extrabold text-slate-800">Konfirmasi Password *</label>
                <div className="relative">
                  <input
                    type={showConfirmPassword ? "text" : "password"}
                    required
                    value={data.password_confirmation}
                    onChange={(e) => setData('password_confirmation', e.target.value)}
                    className="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 outline-none focus:bg-white focus:border-blue-900 focus:ring-2 focus:ring-blue-900/20 transition-all"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700"
                  >
                    {showConfirmPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                  </button>
                </div>
              </div>
            </div>

            {/* BUTTON SUBMIT DAFTAR AKUN PESERTA */}
            <div className="pt-2">
              <button
                type="submit"
                disabled={processing}
                className="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-xs font-black text-white bg-[#006838] hover:bg-[#00522c] active:scale-98 shadow-md transition-all cursor-pointer"
              >
                <span>Daftar Akun Peserta</span>
                <ArrowRight className="w-4 h-4" />
              </button>
            </div>

            <div className="pt-3 text-center border-t border-slate-100">
              <Link href="/login" className="text-xs font-bold text-blue-900 hover:underline inline-flex items-center gap-1">
                <span>&larr; Kembali ke Halaman Login</span>
              </Link>
            </div>

          </form>

        </div>
      </div>
    </div>
  );
}
