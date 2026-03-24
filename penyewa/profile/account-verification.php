<?php
$status = isset($user['is_verified']) ? trim($user['is_verified']) : '';
?>

<div class="flex items-center gap-4 mb-10 text-left">
    <div class="w-2 h-8 bg-primary rounded-full"></div>
    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Verifikasi Identitas</h3>
</div>

<div class="space-y-8 text-left">

    <?php if ($status === 'verified'): ?>
        <div class="bg-emerald-50 border border-emerald-100 p-10 rounded-[40px] text-center">
            <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-emerald-500 shadow-sm mx-auto mb-6">
                <i class="fas fa-check-double text-3xl"></i>
            </div>
            <h4 class="text-xl font-black text-emerald-800 mb-2">Akun Terverifikasi!</h4>
            <p class="text-sm font-bold text-emerald-600/70 mb-8 max-w-xs mx-auto">Luar biasa! Identitas Anda telah dikonfirmasi secara otomatis. Sekarang Anda dapat memesan kamar manapun di Griya Asri Kos.</p>
            <a href="../dashboard.php" class="inline-block px-10 py-4 bg-emerald-500 text-white font-black rounded-2xl shadow-xl shadow-emerald-200 hover:scale-105 transition-all">Mulai Booking Sekarang</a>
        </div>

    <?php elseif ($status === 'pending'): ?>
        <div class="bg-blue-50 border border-blue-100 p-8 rounded-[32px] flex items-center gap-6 shadow-sm">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm flex-shrink-0">
                <i class="fas fa-clock text-2xl animate-pulse"></i>
            </div>
            <div>
                <h4 class="text-md font-black text-slate-800 uppercase tracking-tight">Status: Menunggu Peninjauan</h4>
                <p class="text-xs font-bold text-slate-500 leading-relaxed italic">Proses ini biasanya memakan waktu 1-24 jam. Kami akan memberi tahu Anda setelah selesai!</p>
            </div>
        </div>

    <?php else: ?>
        <div class="bg-amber-50 border border-amber-100 p-6 rounded-[32px] flex items-center gap-5 mb-8">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-amber-500 shadow-sm flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-xl"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight">Status: Belum Verifikasi</h4>
                <p class="text-[11px] font-bold text-slate-500 leading-relaxed italic">Silakan lengkapi formulir di bawah ini untuk memverifikasi identitas Anda.</p>
            </div>
        </div>

        <form action="index.php?tab=verification&action=verify" method="POST" enctype="multipart/form-data" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap (Sesuai KTP)</label>
                    <input type="text" name="full_name_ktp" required placeholder="Contoh: Muhammad Raynar Hammam" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold focus:ring-4 focus:ring-primary/5 outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                    <input type="date" name="birth_date" required class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold focus:ring-4 focus:ring-primary/5 outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                    <div class="relative">
                        <select name="gender" required onchange="this.style.color='#1e293b'" style="color: #94a3b8;" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold focus:ring-4 focus:ring-primary/5 outline-none appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" style="color: #1e293b;">Laki-laki</option>
                            <option value="Perempuan" style="color: #1e293b;">Perempuan</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                    <textarea name="address" required placeholder="Alamat lengkap sesuai domisili saat ini..." class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold focus:ring-4 focus:ring-primary/5 outline-none h-28"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-center block">Foto KTP / Kartu Identitas</label>
                    <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[30px] p-2 text-center group hover:border-primary transition-all relative min-h-[160px] flex items-center justify-center overflow-hidden">
                        <input type="file" name="ktp_photo" id="ktpUpload" class="hidden" accept="image/*" required onchange="previewImage(this, 'previewKtp', 'placeholderKtp')">
                        <label for="ktpUpload" class="cursor-pointer block w-full h-full p-6">
                            <div id="placeholderKtp" class="space-y-2">
                                <i class="fas fa-id-card text-2xl text-slate-300 group-hover:text-primary transition-colors"></i>
                                <p class="text-[10px] font-black text-slate-500 uppercase">Pilih Berkas</p>
                            </div>
                            <img id="previewKtp" class="hidden w-full h-full max-h-[140px] object-cover rounded-2xl shadow-md">
                        </label>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-center block">Foto Selfie Dengan KTP</label>
                    <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[30px] p-2 text-center group hover:border-primary transition-all relative min-h-[160px] flex items-center justify-center overflow-hidden">
                        <input type="file" name="selfie_photo" id="selfieUpload" class="hidden" accept="image/*" required onchange="previewImage(this, 'previewSelfie', 'placeholderSelfie')">
                        <label for="selfieUpload" class="cursor-pointer block w-full h-full p-6">
                            <div id="placeholderSelfie" class="space-y-2">
                                <i class="fas fa-camera text-2xl text-slate-300 group-hover:text-primary transition-colors"></i>
                                <p class="text-[10px] font-black text-slate-500 uppercase">Pilih Berkas</p>
                            </div>
                            <img id="previewSelfie" class="hidden w-full h-full max-h-[140px] object-cover rounded-2xl shadow-md">
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-primary text-white font-black rounded-3xl shadow-xl shadow-primary/20 hover:scale-[1.01] transition-all tracking-tight">
                Simpan & Verifikasi Identitas Saya
            </button>
        </form>
    <?php endif; ?>
</div>

<script>
    function previewImage(input, previewId, placeholderId) {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>