# 📋 Quick Reference Sheet - Dashboard Controller

## ⚡ Quick Setup

```php
// 1. Import di Controller
use App\Models\Absen;
use App\Models\Pengajuan;
use App\Models\User;
use Carbon\Carbon;

// 2. Di method dashboard()
$now = Carbon::now('Asia/Jakarta');
$week = getWorkWeekRange($now);
$absensi = getAbsensiPerHari($week['start'], $week['end']);
```

---

## 📅 Minggu Kerja Logic

### Kapan Reset Terjadi?

| Hari | Range Minggu | Reset? |
|------|--------------|--------|
| **Senin** | Senin-Jumat minggu ini | ✅ Reset (minggu baru) |
| **Selasa** | Senin-Jumat minggu ini | ❌ No |
| **Rabu** | Senin-Jumat minggu ini | ❌ No |
| **Kamis** | Senin-Jumat minggu ini | ❌ No |
| **Jumat** | Senin-Jumat minggu ini | ❌ No |
| **Sabtu** | Senin-Jumat minggu lalu | ❌ No (view prev week) |
| **Minggu** | Senin-Jumat minggu lalu | ❌ No (view prev week) |

### Code Logic
```php
$dayOfWeek = $now->dayOfWeek; // 0=Sun, 1=Mon, ..., 6=Sat

if ($dayOfWeek == 0 || $dayOfWeek == 6) {
    // Weekend: ambil minggu lalu
    $start = $now->copy()->subWeeks(1)->startOfWeek(Carbon::MONDAY);
} else {
    // Weekday: ambil minggu ini
    $start = $now->copy()->startOfWeek(Carbon::MONDAY);
}

$end = $start->copy()->addDays(4); // Friday
```

---

## 🔍 Query Patterns

### Pattern 1: Count Distinct User per Day
```php
Absen::selectRaw("DAYNAME(tanggal) as day, COUNT(DISTINCT user_id) as count")
    ->whereBetween('tanggal', [$start, $end])
    ->groupBy('day')
    ->pluck('count', 'day');

// Result: ['Monday' => 25, 'Tuesday' => 28, ...]
```

### Pattern 2: Count by Status
```php
Pengajuan::selectRaw("DAYNAME(tanggal_mulai) as day, COUNT(*) as count")
    ->where('jenis', 'cuti')
    ->where('status', 'acc')
    ->whereBetween('tanggal_mulai', [$start, $end])
    ->groupBy('day')
    ->pluck('count', 'day');
```

### Pattern 3: Today's Attendance
```php
$total = User::where('role', 'karyawan')->count();
$present = Absen::whereDate('tanggal', today())
    ->distinct('user_id')
    ->count('user_id');
$absent = max(0, $total - $present);
```

### Pattern 4: Pending Approvals
```php
Pengajuan::where('status', 'pending')->count();
```

---

## 📊 Data Mapping untuk Chart.js

### Input dari Database
```php
$raw = [
    'Monday' => 25,
    'Tuesday' => 28,
    'Wednesday' => 24,
    'Thursday' => 27,
    'Friday' => 20
];
```

### Output untuk Chart.js
```php
// Method 1: Manual Array
$array = [
    $raw['Monday'] ?? 0,      // [0]
    $raw['Tuesday'] ?? 0,     // [1]
    $raw['Wednesday'] ?? 0,   // [2]
    $raw['Thursday'] ?? 0,    // [3]
    $raw['Friday'] ?? 0       // [4]
];

// Method 2: Collection Map
$collection = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])
    ->map(fn($day) => (int) ($raw[$day] ?? 0))
    ->values();

// Method 3: Helper Function
private function mapToChartData($dbResult)
{
    $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    return collect($daysOrder)
        ->map(fn($day) => (int) ($dbResult->get($day, 0) ?? 0))
        ->values();
}
```

---

## 🔔 Notifikasi Checklist

```php
// 1. Absensi tidak lengkap hari ini
if (isWeekday() && $absentCount > 0) {
    addNotification('✖', 'red', 'Absensi', "$absentCount belum absen");
}

// 2. Karyawan baru ditambah
$newToday = User::where('role', 'karyawan')
    ->whereDate('created_at', today())
    ->count();
if ($newToday > 0) {
    addNotification('👤', 'green', 'Karyawan Baru', "$newToday karyawan baru");
}

// 3. Pengajuan menunggu
$pending = Pengajuan::where('status', 'pending')->count();
if ($pending > 0) {
    addNotification('📋', 'yellow', 'Pengajuan', "$pending menunggu approval");
}

// 4. Cuti mulai hari ini
$cutiHariIni = Pengajuan::where('jenis', 'cuti')
    ->where('status', 'acc')
    ->whereDate('tanggal_mulai', today())
    ->count();
if ($cutiHariIni > 0) {
    addNotification('🏖', 'blue', 'Cuti', "$cutiHariIni karyawan cuti");
}
```

---

## 📲 Blade Template Snippets

### Bar Chart
```blade
<canvas id="absensiChart"></canvas>

<script>
new Chart(document.getElementById('absensiChart'), {
  type: 'bar',
  data: {
    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
    datasets: [{
      data: @json($absensiHarian->values()),
      backgroundColor: '#fde68a'
    }]
  }
});
</script>
```

### Donut Chart
```blade
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
  },
  options: {
    cutout: '70%',
    plugins: { legend: { display: false } }
  }
});
</script>
```

### Notifikasi Loop
```blade
@forelse ($notifikasi as $n)
  <div class="flex gap-3 p-3 bg-gray-100 rounded">
    <span class="text-{{ $n['color'] }}-500">{{ $n['icon'] }}</span>
    <div>
      <p class="font-semibold">{{ $n['judul'] }}</p>
      <p class="text-sm text-gray-600">{{ $n['pesan'] }}</p>
      <p class="text-xs text-gray-400">{{ $n['waktu'] }}</p>
    </div>
  </div>
@empty
  <p class="text-center text-gray-500">Tidak ada notifikasi</p>
@endforelse
```

---

## 🛠️ Testing Commands

```bash
# Test absensi query
php artisan tinker
>>> Absen::whereDate('tanggal', '2025-12-22')->distinct('user_id')->count();
>>> Absen::selectRaw("DAYNAME(tanggal) as day, COUNT(DISTINCT user_id)")
    ->whereBetween('tanggal', ['2025-12-22', '2025-12-26'])
    ->groupBy('day')->get();

# Test cuti query
>>> Pengajuan::where('jenis', 'cuti')->where('status', 'acc')->get();

# Test karyawan
>>> User::where('role', 'karyawan')->count();
>>> User::where('role', 'karyawan')->whereDate('created_at', now())->count();

# Test pengajuan pending
>>> Pengajuan::where('status', 'pending')->count();
```

---

## 🎯 Variable Names Convention

| Variable | Type | Purpose |
|----------|------|---------|
| `$now` | Carbon | Current datetime |
| `$today` | string | Today date (Y-m-d) |
| `$dayOfWeek` | int | 0-6 (Sun-Sat) |
| `$startOfWeek` | Carbon | Monday |
| `$endOfWeek` | Carbon | Friday |
| `$totalKaryawan` | int | Total employees |
| `$sudahAbsen` | int | Present today |
| `$belumAbsen` | int | Absent today |
| `$absensiHarian` | Collection | [0-4] per day |
| `$cuti` | Collection | [0-4] per day |
| `$notifikasi` | array | Notification list |

---

## ⚠️ Common Mistakes

### ❌ Wrong: Tidak distinct user
```php
// Salah! Count all records (bisa double count)
$count = Absen::whereDate('tanggal', $today)->count();
```

### ✅ Right: Distinct user
```php
// Benar! Count unique users
$count = Absen::whereDate('tanggal', $today)
    ->distinct('user_id')
    ->count('user_id');
```

### ❌ Wrong: Timezone tidak konsisten
```php
// Salah!
$now = Carbon::now(); // Default UTC
```

### ✅ Right: Timezone Jakarta
```php
// Benar!
$now = Carbon::now('Asia/Jakarta');
```

### ❌ Wrong: Status typo
```php
// Salah! (status mungkin 'acc', bukan 'approved')
->where('status', 'approved')
```

### ✅ Right: Correct status
```php
// Benar!
->where('status', 'acc')
```

### ❌ Wrong: Tanggal field salah
```php
// Salah! (Pengajuan punya tanggal_mulai, bukan tanggal)
->whereDate('tanggal', $date)
```

### ✅ Right: Correct field
```php
// Benar!
->whereDate('tanggal_mulai', $date)
```

---

## 🚀 Performance Tips

1. **Gunakan selectRaw() untuk Group By**
   - Lebih cepat dari collection map
   - Query dijalankan di database, bukan PHP

2. **Cache Range Calculation**
   ```php
   $week = Cache::remember('work_week', 3600, function() {
       return getWorkWeekRange(Carbon::now('Asia/Jakarta'));
   });
   ```

3. **Index Database Columns**
   ```sql
   CREATE INDEX idx_absensi_tanggal_user ON absensi(tanggal, user_id);
   CREATE INDEX idx_pengajuan_status_jenis ON pengajuan(status, jenis);
   ```

4. **Limit Query Results**
   ```php
   Pengajuan::where('status', 'pending')->limit(100)->get();
   ```

---

## 📞 Need Help?

- **Logic Issue?** → Lihat DASHBOARD_CONTROLLER_DOCS.md
- **Query Issue?** → Lihat DashboardExamples.php
- **Implementation?** → Lihat IMPLEMENTATION_README.md
- **Blade Template?** → Lihat dashboard.blade.php

---

**Status:** ✅ Ready for Copy-Paste
Use this sheet untuk quick lookup saat development.
