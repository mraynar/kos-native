<div class="flex items-center gap-4 mb-10 text-left">
    <div class="w-2 h-8 bg-primary rounded-full"></div>
    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Informasi Profil</h3>
</div>

<form action="update-handler.php?action=edit" method="POST" class="space-y-10 text-left">

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-id-card text-primary text-xs"></i>
                <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Identitas Terverifikasi</h4>
            </div>
            <?php if (($user['is_verified'] ?? '') === 'verified'): ?>
                <span class="text-[9px] font-black px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg uppercase tracking-widest border border-emerald-100">
                    <i class="fas fa-check-circle mr-1"></i> Verified
                </span>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-slate-50 rounded-[40px] border border-slate-100 shadow-inner">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Nama Lengkap (KTP)</label>
                <input type="text" value="<?= $user['full_name_ktp'] ?: 'Belum Melakukan Verifikasi' ?>" disabled
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-400 cursor-not-allowed">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Jenis Kelamin</label>
                <input type="text" value="<?= $user['gender'] ?: '-' ?>" disabled
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-400 cursor-not-allowed">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Tanggal Lahir</label>
                <input type="text" value="<?= (!empty($user['birth_date']) && $user['birth_date'] != '0000-00-00') ? date('d M Y', strtotime($user['birth_date'])) : '-' ?>" disabled
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-400 cursor-not-allowed">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Email Utama</label>
                <input type="text" value="<?= $user['email'] ?>" disabled
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-400 cursor-not-allowed">
            </div>

            <div class="md:col-span-2 pt-2 border-t border-slate-200/50">
                <p class="text-[9px] text-slate-400 font-bold flex items-center gap-2">
                    <i class="fas fa-shield-check text-primary/50"></i>
                    Data identitas di atas terkunci demi keamanan akun. Hubungi Admin Griya Asri jika terdapat kesalahan data.
                </p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="flex items-center gap-2">
            <i class="fas fa-user-circle text-primary text-xs"></i>
            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Informasi Kontak & Domisili</h4>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Nama Panggilan</label>
                <input type="text" name="nickname" value="<?= $user['nickname'] ?>" required placeholder="Masukkan nama panggilan..."
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all placeholder:text-slate-300">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Nomor WhatsApp</label>
                <input type="text" name="phone" value="<?= $user['phone'] ?>" required placeholder="Contoh: 08123456789"
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all placeholder:text-slate-300">
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Alamat Asal (Sesuai KTP)</label>
                <textarea name="address" required placeholder="Masukkan alamat lengkap asal Anda..."
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none h-32 transition-all placeholder:text-slate-300"><?= $user['address'] ?? '' ?></textarea>
            </div>
        </div>
    </div>

    <div class="pt-6 border-t border-slate-100">
        <button type="submit" class="group flex items-center gap-3 px-12 py-5 bg-primary text-white font-black rounded-[28px] shadow-xl shadow-primary/20 hover:bg-slate-900 hover:scale-[1.02] active:scale-95 transition-all tracking-tight">
            <span>Perbarui Data Profil</span>
            <i class="fas fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </button>
    </div>
</form>