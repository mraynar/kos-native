<?php
$user_id = $_SESSION['user_id'];

$q_history = mysqli_query(
    $conn,
    "SELECT bookings.*, rooms.room_number, room_types.image as type_image 
            FROM bookings 
            JOIN rooms ON bookings.room_id = rooms.id 
            JOIN room_types ON rooms.room_type_id = room_types.id 
            WHERE bookings.user_id = '$user_id' 
            ORDER BY bookings.created_at DESC"
);
?>

<div class="flex items-center gap-4 mb-10 text-left">
    <div class="w-2 h-8 bg-primary rounded-full"></div>
    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Penyewaan</h3>
</div>

<div class="space-y-6">
    <?php if (mysqli_num_rows($q_history) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($q_history)): ?>
            <div class="bg-white border border-slate-300 p-6 rounded-[35px] shadow-xl shadow-slate-200/50 flex flex-col md:flex-row items-center gap-6">
                <div class="w-full md:w-32 h-24 rounded-2xl overflow-hidden bg-slate-100 border border-primary-dark shadow-xl flex-shrink-0">
                    <img src="/sewa-kos/assets/img/room_types/<?= $row['type_image'] ?>" class="w-full h-full object-cover">
                </div>

                <div class="flex-grow text-left">
                    <h4 class="text-lg font-black text-slate-800 tracking-tight">Kamar No. <?= $row['room_number'] ?></h4>
                    <div class="flex items-center gap-4 mt-1 text-slate-400">
                        <span class="text-[11px] font-bold uppercase tracking-widest "><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                        <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                        <span class="text-sm font-black text-primary">Rp <?= number_format($row['total_price']) ?></span>
                    </div>
                </div>

                <div class="flex flex-col gap-2 w-full md:w-auto">
                    <?php if ($row['status'] === 'paid'): ?>
                        <span class="px-6 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase text-center border border-emerald-200">Lunas</span>

                        <a href="receipt.php?id=<?= $row['id'] ?>" target="_blank" class="px-6 py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase hover:bg-black transition-all text-center">
                            <i class="fas fa-print mr-2"></i> Unduh Nota
                        </a>
                    <?php else: ?>
                        <span class="px-6 py-2 bg-amber-50 text-amber-600 rounded-xl text-[10px] font-black uppercase text-center border border-amber-100">Pending</span>

                        <a href="../transaction/payment.php?id=<?= $row['id'] ?>" class="px-6 py-3 bg-primary text-white rounded-xl text-[10px] font-black uppercase text-center">
                            Bayar Sekarang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center py-20 bg-slate-50 rounded-[40px] border border-dashed border-slate-200">
            <p class="text-slate-400 font-bold italic">Belum ada riwayat penyewaan.</p>
        </div>
    <?php endif; ?>
</div>