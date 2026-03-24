<div class="flex items-center gap-4 mb-10 text-left">
    <div class="w-2 h-8 bg-primary rounded-full"></div>
    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Pengaturan Keamanan</h3>
</div>

<div class="max-w-md mx-auto lg:mx-0" x-data="{ showOld: false, showNew: false, showConfirm: false }">
    <form action="update-handler.php?action=password" method="POST" class="space-y-6 text-left">

        <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Kata Sandi Saat Ini</label>
            <div class="relative group">
                <input :type="showOld ? 'text' : 'password'" name="current_password" required placeholder="••••••••"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-red-500/5 focus:border-red-200 outline-none transition-all placeholder:text-slate-300">
                <button type="button" @click="showOld = !showOld" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-primary transition-colors">
                    <i class="fas" :class="showOld ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="py-2 flex items-center gap-4">
            <div class="h-[1px] flex-grow bg-slate-100"></div>
            <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Sandi Baru</span>
            <div class="h-[1px] flex-grow bg-slate-100"></div>
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Kata Sandi Baru</label>
            <div class="relative group">
                <input :type="showNew ? 'text' : 'password'" name="new_password" required placeholder="••••••••"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all placeholder:text-slate-300">
                <button type="button" @click="showNew = !showNew" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-primary transition-colors">
                    <i class="fas" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Konfirmasi Kata Sandi Baru</label>
            <div class="relative group">
                <input :type="showConfirm ? 'text' : 'password'" name="confirm_password" required placeholder="••••••••"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all placeholder:text-slate-300">
                <button type="button" @click="showConfirm = !showConfirm" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-primary transition-colors">
                    <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="p-5 bg-blue-50/50 rounded-[24px] border border-blue-100/50">
            <div class="flex gap-3">
                <i class="fas fa-shield-alt text-blue-400 mt-0.5 text-xs"></i>
                <p class="text-[10px] font-bold text-blue-600/80 leading-relaxed italic">
                    Tips: Gunakan minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka untuk keamanan yang lebih maksimal.
                </p>
            </div>
        </div>

        <button type="submit" class="w-full py-5 bg-slate-900 text-white font-black rounded-[24px] hover:bg-primary transition-all active:scale-[0.98] shadow-xl shadow-slate-200 flex items-center justify-center gap-3">
            <i class="fas fa-key text-xs opacity-50"></i>
            <span>Perbarui Kata Sandi</span>
        </button>
    </form>
</div>