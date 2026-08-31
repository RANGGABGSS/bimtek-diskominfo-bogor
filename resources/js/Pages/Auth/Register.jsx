import React from 'react';
import { useForm, Link } from '@inertiajs/react';
import { ArrowRight, Eye, EyeOff } from 'lucide-react';

export default function Register() {
  const { data, setData, post, processing, errors } = useForm({
    name: '',
    email: '',
    no_hp: '',
    password: '',
    password_confirmation: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    post('/register/peserta');
  };

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 font-sans selection:bg-blue-600 selection:text-white">
      
      <div className="w-full max-w-md bg-white border border-slate-200/80 rounded-3xl shadow-xl p-8 sm:p-10 space-y-6">
        
        {/* LOGO KEDINASAN HEADER */}
        <div className="flex items-center gap-3">
          <img 
            src="/images/logo_diskominfo_bogorkab.png" 
            alt="Logo Diskominfo" 
            className="h-10 object-contain" 
          />
        </div>

        {/* TITLE & SUBTITLE */}
        <div className="space-y-1 pt-1">
          <h1 className="text-2xl font-black text-slate-900 tracking-tight">Daftar Akun</h1>
          <p className="text-xs text-slate-500 font-medium">Lengkapi data diri Anda untuk membuat akun baru</p>
        </div>

        {/* SIMPLE REGISTRATION FORM */}
        <form onSubmit={handleSubmit} className="space-y-4">
          
          <div className="space-y-1.5">
            <label className="block text-xs font-extrabold text-slate-700">Nama Lengkap *</label>
            <input
              type="text"
              required
              value={data.name}
              onChange={(e) => setData('name', e.target.value)}
              placeholder="Masukkan nama lengkap Anda"
              className="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100 transition-all"
            />
            {errors.name && <p className="text-xs text-rose-600 font-bold">{errors.name}</p>}
          </div>

          <div className="space-y-1.5">
            <label className="block text-xs font-extrabold text-slate-700">Email *</label>
            <input
              type="email"
              required
              value={data.email}
              onChange={(e) => setData('email', e.target.value)}
              placeholder="nama@email.com"
              className="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100 transition-all"
            />
            {errors.email && <p className="text-xs text-rose-600 font-bold">{errors.email}</p>}
          </div>

          <div className="space-y-1.5">
            <label className="block text-xs font-extrabold text-slate-700">No. WhatsApp / HP *</label>
            <input
              type="text"
              required
              value={data.no_hp}
              onChange={(e) => setData('no_hp', e.target.value)}
              placeholder="081234567890"
              className="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100 transition-all"
            />
          </div>

          <div className="space-y-1.5">
            <label className="block text-xs font-extrabold text-slate-700">Password *</label>
            <input
              type="password"
              required
              value={data.password}
              onChange={(e) => setData('password', e.target.value)}
              placeholder="Minimal 8 karakter"
              className="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100 transition-all"
            />
            {errors.password && <p className="text-xs text-rose-600 font-bold">{errors.password}</p>}
          </div>

          <div className="space-y-1.5">
            <label className="block text-xs font-extrabold text-slate-700">Konfirmasi Password *</label>
            <input
              type="password"
              required
              value={data.password_confirmation}
              onChange={(e) => setData('password_confirmation', e.target.value)}
              placeholder="Ulangi password"
              className="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100 transition-all"
            />
          </div>

          <button
            type="submit"
            disabled={processing}
            className="w-full py-3.5 px-4 bg-[#3b49df] hover:bg-[#2f3ab7] text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 cursor-pointer mt-2"
          >
            Daftar Akun
          </button>
        </form>

        <div className="text-center pt-2 text-xs font-semibold">
          <p className="text-slate-600">
            Sudah memiliki akun?{' '}
            <Link href="/login" className="text-blue-900 font-extrabold hover:underline">
              Masuk
            </Link>
          </p>
        </div>

      </div>
    </div>
  );
}
