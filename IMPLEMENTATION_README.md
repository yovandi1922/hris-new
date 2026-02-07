# 📊 Dashboard Absensi - Implementation Guide

## 🎯 Ringkasan Implementasi

Telah diimplementasikan sistem dashboard absensi dengan fitur-fitur berikut:

✅ **Logic Minggu Kerja**
- Data hanya menampilkan Senin-Jumat (hari kerja)
- Otomatis reset setiap Senin untuk minggu baru
- Jika hari Sabtu/Minggu, tampilkan minggu sebelumnya

✅ **Grafik Absensi**
- Bar chart menampilkan jumlah karyawan yang sudah absen per hari
- Menggunakan COUNT(DISTINCT user_id) untuk menghindari double-count

✅ **Donut Chart**
- Menampilkan "Sudah Absen vs Belum Absen" untuk hari ini
- Hanya aktif pada hari kerja (Senin-Jumat)

✅ **Data Cuti**
- Hitung pengajuan cuti yang approved per hari dalam minggu kerja
- Gunakan tanggal_mulai untuk grouping

✅ **Notifikasi Dinamis**
- Alert absensi (jika ada yang belum absen)
- Info karyawan baru ditambahkan
- Info pengajuan menunggu persetujuan
- Info cuti yang dimulai hari ini

---

## 📁 File-File yang Telah Diupdate

### 1. **AdminController.php** (Updated)
- **Method:** `dashboard()`
  - Logic untuk menghitung range minggu kerja
  - Query absensi dan cuti menggunakan Eloquent
  - Hitung sudah/belum absen hari ini
  - Generate notifikasi dinamis

- **Helper Methods:**
  - `getAbsensiPerHari()` - Hitung absensi per hari
  - `getCutiPerHari()` - Hitung cuti per hari

**Lokasi:** `app/Http/Controllers/AdminController.php`

### 2. **Model Pengajuan.php** (Updated)
- Tambah kolom ke fillable: `tanggal_mulai`, `tanggal_selesai`, `durasi`
- Untuk support query dengan tanggal cuti

**Lokasi:** `app/Models/Pengajuan.php`

### 3. **DashboardExamples.php** (New)
- File referensi dengan contoh implementasi
- Berbagai class helper untuk reusable code
- Usage examples

**Lokasi:** `app/Http/Controllers/DashboardExamples.php`

### 4. **DASHBOARD_CONTROLLER_DOCS.md** (New)
- Dokumentasi lengkap
- Penjelasan logic dan query
- Database schema
- Best practices

**Lokasi:** `DASHBOARD_CONTROLLER_DOCS.md`

---

## 🔧 Cara Menggunakan

### 1. Update Database (Jika diperlukan)
Jika kolom `tanggal_mulai`, `tanggal_selesai`, `durasi` belum ada di tabel `pengajuan`, jalankan:

```bash
php artisan migrate
```

Atau buat migration baru jika migration existing belum di-run:

```bash
php artisan make:migration add_columns_to_pengajuan_table
```

Lalu di dalam migration:
```php
Schema::table('pengajuan', function (Blueprint $table) {
    if (!Schema::hasColumn('pengajuan', 'tanggal_mulai')) {
        $table->date('tanggal_mulai')->nullable();
    }
    if (!Schema::hasColumn('pengajuan', 'tanggal_selesai')) {
        $table->date('tanggal_selesai')->nullable();
    }
    if (!Schema::hasColumn('pengajuan', 'durasi')) {
        $table->integer('durasi')->default(1);
    }
});
```

### 2. Clear Cache (Opsional tapi Recommended)
```bash
php artisan config:cache
php artisan view:cache
```

### 3. Test Dashboard
1. Login sebagai admin
2. Buka route: `http://localhost:8000/admin/`
3. Periksa apakah grafik menampilkan data dengan benar

---

## 📊 Data Flow Diagram

```
┌─────────────────────────────────────────┐
│         AdminController::dashboard()    │
└─────────────────────────────────────────┘
              ↓
    ┌─────────────────────────┐
    │ 1. Calculate Week Range │ (Carbon)
    │  - Mon-Fri (1-5)        │
    │  - Or Prev Week (0,6)   │
    └─────────────────────────┘
              ↓
    ┌─────────────────────────┐
    │ 2. Query Absensi        │
    │  COUNT(DISTINCT user_id)│
    │  per hari (Mon-Fri)     │
    └─────────────────────────┘
              ↓
    ┌─────────────────────────┐
    │ 3. Query Cuti           │
    │  WHERE jenis='cuti'     │
    │  AND status='acc'       │
    └─────────────────────────┘
              ↓
    ┌─────────────────────────┐
    │ 4. Hitung Hari Ini      │
    │  Sudah vs Belum Absen   │
    └─────────────────────────┘
              ↓
    ┌─────────────────────────┐
    │ 5. Generate Notifikasi  │
    │  - Absensi              │
    │  - Karyawan Baru        │
    │  - Pengajuan Pending    │
    │  - Cuti Hari Ini        │
    └─────────────────────────┘
              ↓
    ┌─────────────────────────┐
    │  Return to Blade View   │
    │  (admin.dashboard)      │
    └─────────────────────────┘
              ↓
    ┌─────────────────────────┐
    │  Chart.js Initialize    │
    │  - Absensi Bar Chart    │
    │  - Absen Donut Chart    │
    │  - Cuti Bar Chart       │
    │  - Live Clock & Date    │
    └─────────────────────────┘
```

---

## 🧪 Testing Queries

### Test 1: Check Absensi Minggu Ini
```php
php artisan tinker

# Hitung distinct karyawan yang absen hari Senin
Absen::whereDate('tanggal', '2025-12-22')->distinct('user_id')->count('user_id');

# Lihat data raw dengan grouping
Absen::selectRaw("DAYNAME(tanggal) as day, COUNT(DISTINCT user_id) as count")
    ->whereBetween('tanggal', ['2025-12-22', '2025-12-26'])
    ->groupBy('day')
    ->get();
```

### Test 2: Check Cuti yang Disetujui
```php
# Lihat cuti dengan status 'acc'
Pengajuan::where('jenis', 'cuti')->where('status', 'acc')->get();

# Hitung per hari
Pengajuan::selectRaw("DAYNAME(tanggal_mulai) as day, COUNT(*) as count")
    ->where('jenis', 'cuti')
    ->where('status', 'acc')
    ->whereBetween('tanggal_mulai', ['2025-12-22', '2025-12-26'])
    ->groupBy('day')
    ->get();
```

### Test 3: Check Notifikasi
```php
# Pengajuan pending
Pengajuan::where('status', 'pending')->count();

# Karyawan baru hari ini
User::where('role', 'karyawan')->whereDate('created_at', now())->count();
```

---

## 🎨 Blade Template Usage

Di `resources/views/admin/dashboard.blade.php`, data sudah ready untuk dipakai:

```blade
<!-- Absensi Chart -->
<canvas id="absensiChart"></canvas>

<script>
const absensiData = @json($absensiHarian->values());
const labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];

new Chart(document.getElementById('absensiChart'), {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      data: absensiData,
      backgroundColor: '#fde68a'
    }]
  }
});
</script>

<!-- Donut Chart -->
<canvas id="absenDonut"></canvas>

<script>
new Chart(document.getElementById('absenDonut'), {
  type: 'doughnut',
  data: {
    labels: ['Sudah Absen', 'Belum Absen'],
    datasets: [{
      data: [{{ $sudahAbsen }}, {{ $belumAbsen }}],
      backgroundColor: ['#374151', '#d1d5db']
    }]
  }
});
</script>
```

---

## 🚀 Features yang Sudah Implemented

| Feature | Status | Details |
|---------|--------|---------|
| Range Minggu Kerja | ✅ | Otomatis reset Senin |
| Absensi Per Hari | ✅ | DISTINCT user count |
| Cuti Per Hari | ✅ | Filter status 'acc' |
| Sudah/Belum Absen Hari Ini | ✅ | Hanya hari kerja |
| Notifikasi Otomatis | ✅ | 4 tipe notifikasi |
| Live Clock | ✅ | Update tiap detik |
| Dark Mode Support | ✅ | Via Tailwind |
| Responsive Design | ✅ | Grid layout |

---

## 🔒 Security Checklist

- ✅ Authentication: `auth` middleware
- ✅ Authorization: `role:admin` middleware
- ✅ Input Validation: Date range validated by Carbon
- ✅ Timezone: Set to `Asia/Jakarta`
- ✅ Query Optimization: Using `selectRaw()` dan `groupBy()`
- ✅ SQL Injection Prevention: Using Eloquent/Query Builder

---

## 📝 Future Enhancements (Optional)

1. **Export Report**
   - Export absensi bulanan ke PDF/Excel
   
2. **Advanced Filtering**
   - Filter by department, employee status, etc.
   
3. **Real-time Updates**
   - Use WebSocket untuk real-time dashboard update
   
4. **Predictive Analytics**
   - Prediksi karyawan yang sering tidak hadir
   
5. **Mobile Responsive**
   - Optimize untuk mobile view

---

## 🐛 Troubleshooting

### Masalah: Grafik tidak menampilkan data
**Solusi:**
1. Cek apakah ada data di tabel `absensi` untuk minggu berjalan
2. Verify timezone setting di `.env` atau `config/app.php`
3. Cek browser console untuk error Chart.js

### Masalah: Notifikasi tidak muncul
**Solusi:**
1. Verify data di tabel `pengajuan` dengan status 'pending'
2. Check apakah `jenis` column ada dan terisi
3. Cek `created_at` timestamp karyawan baru

### Masalah: Donut chart selalu 0
**Solusi:**
1. Cek apakah hari ini adalah hari kerja (Senin-Jumat)
2. Verify ada data di tabel `absensi` untuk hari ini
3. Cek timezone: `Carbon::now('Asia/Jakarta')`

---

## 📚 Reference Dokumentasi

- [Laravel Carbon Documentation](https://carbon.nesbot.com/)
- [Laravel Eloquent Documentation](https://laravel.com/docs/eloquent)
- [Chart.js Documentation](https://www.chartjs.org/)
- [Tailwind CSS Documentation](https://tailwindcss.com/)

---

## 👨‍💻 Developer Info

- **Framework:** Laravel 11
- **Frontend:** Blade + Chart.js + Tailwind CSS
- **Database:** MySQL/MariaDB
- **Timezone:** Asia/Jakarta
- **Last Updated:** 2025-12-22

---

**Status:** ✅ Ready for Production

Semua fitur sudah tested dan siap digunakan. Jika ada issue atau pertanyaan, silakan buat issue atau hubungi tim development.
