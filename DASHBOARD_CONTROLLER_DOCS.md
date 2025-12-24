# Dokumentasi Dashboard Controller - Sistem Absensi

## 📋 Ringkasan

Controller ini menangani logika dashboard admin dengan fitur:
- ✅ Pembacaan data absensi hanya untuk minggu kerja (Senin-Jumat)
- ✅ Reset otomatis data setiap Senin untuk minggu baru
- ✅ Grafik absensi per hari menampilkan jumlah karyawan yang sudah absen
- ✅ Donut chart sudah absen vs belum absen untuk hari ini
- ✅ Tracking data cuti per hari dalam minggu kerja
- ✅ Menggunakan Carbon untuk range tanggal yang akurat

---

## 🔧 Method Utama

### `dashboard()`
**Fungsi:** Menampilkan dashboard admin dengan data absensi mingguan.

**Logic Minggu Kerja:**
```
Jika hari ini adalah:
- Senin-Jumat (1-5): Ambil data dari Senin hingga Jumat minggu berjalan
- Sabtu-Minggu (6-0): Ambil data dari Senin hingga Jumat minggu sebelumnya
```

**Keuntungan:** Data otomatis "mereset" setiap Senin pukul 00:00, minggu sebelumnya tidak ditampilkan.

**Data yang Dikembalikan:**

| Variabel | Tipe | Deskripsi |
|----------|------|-----------|
| `$totalKaryawan` | Integer | Total karyawan dengan role 'karyawan' |
| `$sudahAbsen` | Integer | Jumlah karyawan yang sudah absen hari ini |
| `$belumAbsen` | Integer | Jumlah karyawan yang belum absen hari ini |
| `$absensiHarian` | Collection | [0-4] = Jumlah absen per hari (Sen-Jum) |
| `$cuti` | Collection | [0-4] = Jumlah cuti per hari (Sen-Jum) |
| `$notifikasi` | Array | Daftar notifikasi dinamis |

---

## 🛠️ Helper Methods

### `getAbsensiPerHari($startOfWeek, $endOfWeek)`

**Fungsi:** Menghitung jumlah **karyawan unik** yang melakukan absensi per hari.

**Query:**
```sql
SELECT DAYNAME(tanggal) as day_name, COUNT(DISTINCT user_id) as count
FROM absensi
WHERE tanggal BETWEEN $startOfWeek AND $endOfWeek
GROUP BY day_name
```

**Return:**
```php
Collection {
  [0] => 25, // Senin: 25 karyawan absen
  [1] => 28, // Selasa: 28 karyawan absen
  [2] => 24, // Rabu: 24 karyawan absen
  [3] => 27, // Kamis: 27 karyawan absen
  [4] => 20  // Jumat: 20 karyawan absen (libur sebelum harinya)
}
```

**Keunikan:** Menggunakan `COUNT(DISTINCT user_id)` untuk menghindari double count jika ada multiple check-in.

---

### `getCutiPerHari($startOfWeek, $endOfWeek)`

**Fungsi:** Menghitung jumlah pengajuan cuti yang disetujui per hari dalam minggu kerja.

**Query:**
```sql
SELECT DAYNAME(tanggal_mulai) as day_name, COUNT(*) as count
FROM pengajuan
WHERE jenis = 'cuti' 
  AND status = 'acc'
  AND tanggal_mulai BETWEEN $startOfWeek AND $endOfWeek
GROUP BY day_name
```

**Filter:**
- `jenis = 'cuti'`: Hanya tipe pengajuan cuti
- `status = 'acc'`: Hanya yang sudah disetujui (tidak pending/ditolak)
- `tanggal_mulai`: Menggunakan tanggal mulai cuti

**Return:** Sama seperti `getAbsensiPerHari()` - Collection [0-4]

---

## 📊 Chart.js Integration (di Blade)

### Absensi Chart (Bar)
```javascript
const labels = ['Sen','Sel','Rab','Kam','Jum'];
const absensiData = @json($absensiHarian->values());

new Chart(document.getElementById('absensiChart'), {
  type: 'bar',
  data: {
    labels,
    datasets: [{ 
      data: absensiData, 
      backgroundColor: '#fde68a'
    }]
  }
});
```

**Data:** Langsung dari `$absensiHarian` yang sudah berformat array.

---

### Donut Chart (Hari Ini)
```javascript
new Chart(document.getElementById('absenDonut'), {
  type: 'doughnut',
  data: {
    labels: ['Sudah Absen', 'Belum Absen'],
    datasets: [{
      data: [{{ $sudahAbsen }}, {{ $belumAbsen }}],
      backgroundColor: ['#374151','#d1d5db']
    }]
  }
});
```

**Data:** `$sudahAbsen` dan `$belumAbsen` dihitung khusus untuk hari ini.

---

### Cuti Chart (Bar)
```javascript
const cutiData = @json($cuti->values());

new Chart(document.getElementById('cutiChart'), {
  type: 'bar',
  data: {
    labels,
    datasets: [{ 
      label: 'Cuti',
      data: cutiData, 
      backgroundColor: '#6b7280'
    }]
  }
});
```

**Data:** Dari `$cuti` yang sudah dikelompokkan per hari.

---

## 🔔 Sistem Notifikasi Otomatis

Notifikasi yang muncul di dashboard secara dinamis:

### 1. Alert Absensi
```php
if ($currentDayOfWeek >= 1 && $currentDayOfWeek <= 5 && $belumAbsenHariIni > 0) {
    // Tampilkan notif jika ada karyawan belum absen (hanya hari kerja)
}
```

### 2. Karyawan Baru
```php
$karyawanBaru = User::where('role', 'karyawan')
    ->whereDate('created_at', $today)
    ->count();
```

### 3. Pengajuan Menunggu
```php
$pengajuanMenunggu = Pengajuan::where('status', 'pending')->count();
```

---

## 📅 Contoh Skenario

### Scenario 1: Hari Senin, 25 Nov 2025 (15:00)
- **Range minggu:** 24-28 Nov 2025 (Senin-Jumat minggu ini)
- **Absensi hari ini:** Karyawan yang masuk 25 Nov
- **Donut:** Membandingkan sudah vs belum absen hari Senin
- **Grafik:** Data dari 4 hari sebelumnya (24 Nov) + hari ini

### Scenario 2: Hari Sabtu, 27 Nov 2025 (10:00)
- **Range minggu:** 24-28 Nov 2025 (Senin-Jumat minggu kemarin)
- **Absensi hari ini:** 0 (Sabtu bukan hari kerja)
- **Donut:** Menampilkan 0 sudah absen, total karyawan belum absen
- **Grafik:** Data dari minggu sebelumnya (Mon-Fri 24-28 Nov)

### Scenario 3: Hari Senin, 1 Des 2025 (10:00)
- **Range minggu:** 1-5 Des 2025 (Senin-Jumat minggu baru)
- **Data lama (Nov):** Tidak ditampilkan (otomatis direset)
- **Fresh start:** Grafik menampilkan minggu baru

---

## 🗄️ Database Requirements

### Tabel: `absensi`
```sql
CREATE TABLE absensi (
  id BIGINT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  tanggal DATE NOT NULL,
  jam_masuk TIME,
  jam_keluar TIME,
  status VARCHAR(50),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabel: `pengajuan`
```sql
CREATE TABLE pengajuan (
  id BIGINT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  jenis VARCHAR(50), -- 'cuti', 'lembur', etc
  tanggal_mulai DATE,
  tanggal_selesai DATE,
  status ENUM('pending', 'acc', 'ditolak'),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabel: `users`
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  role VARCHAR(50), -- 'admin' or 'karyawan'
  created_at TIMESTAMP
);
```

---

## 💡 Best Practices Implementasi

1. **Timezone Konsisten:** Selalu gunakan `Asia/Jakarta` untuk consistency
   ```php
   $now = Carbon::now('Asia/Jakarta');
   ```

2. **Cek Role:** Middleware `auth` dan `role:admin` melindungi akses
   ```php
   Route::middleware(['auth', 'role:admin'])->group(...)
   ```

3. **Query Optimized:** Menggunakan `selectRaw()` dan `groupBy()` untuk efficient grouping
   ```php
   Absen::selectRaw("DAYNAME(tanggal) as day_name, COUNT(DISTINCT user_id)")
   ```

4. **Null Handling:** Default value 0 untuk hari tanpa data
   ```php
   $rawAbsensi->get($dayName, 0) ?? 0
   ```

5. **Locale Support:** Gunakan `locale('id_ID')` untuk format tanggal Indonesia
   ```php
   $now->locale('id_ID')->translatedFormat('d F Y')
   ```

---

## 🧪 Testing

### Test Query Absensi Mingguan
```bash
php artisan tinker

# Cek data absensi minggu ini
>>> Absen::whereDate('tanggal', '2025-11-24')->get();

# Cek distinct karyawan
>>> Absen::whereDate('tanggal', '2025-11-24')->distinct('user_id')->count('user_id');
```

### Test Notifikasi
```bash
# Cek pengajuan pending
>>> Pengajuan::where('status', 'pending')->count();

# Cek karyawan baru hari ini
>>> User::where('role', 'karyawan')->whereDate('created_at', now())->count();
```

---

## 📝 Changelog

- **v1.0** (2025-12-22): Initial implementation
  - Logika minggu kerja (Senin-Jumat)
  - Auto-reset setiap Senin
  - Grafik absensi per hari
  - Donut chart sudah/belum absen hari ini
  - Tracking cuti per hari
  - Sistem notifikasi otomatis

---

## 🤝 Support

Untuk pertanyaan atau issue, silakan buat issue di repo atau hubungi tim development.
