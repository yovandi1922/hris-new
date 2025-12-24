-- ========================================
-- SAMPLE TEST DATA - Dashboard Absensi
-- ========================================
-- Gunakan script ini untuk populate test data
-- dan verify queries di dashboard

-- ========================================
-- 1. SAMPLE DATA: USERS (Karyawan)
-- ========================================
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Budi Santoso', 'budi@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Siti Nurhaliza', 'siti@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Ahmad Hidayat', 'ahmad@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Rina Wijaya', 'rina@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Dimas Pratama', 'dimas@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Lina Kusuma', 'lina@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Raka Setiawan', 'raka@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Maya Insani', 'maya@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Joko Purnama', 'joko@example.com', 'hashed_password', 'karyawan', NOW(), NOW()),
('Nina Hermawan', 'nina@example.com', 'hashed_password', 'karyawan', NOW(), NOW());

-- Ambil user IDs
-- SELECT id, name FROM users WHERE role = 'karyawan';


-- ========================================
-- 2. SAMPLE DATA: ABSENSI (Minggu Kerja)
-- ========================================
-- Asumsikan minggu kerja: 22-26 Desember 2025 (Senin-Jumat)

-- Senin 22 Desember 2025
INSERT INTO absensi (user_id, tanggal, jam_masuk, jam_keluar, status, created_at, updated_at) VALUES
(1, '2025-12-22', '07:45:00', '16:30:00', 'hadir', NOW(), NOW()),
(2, '2025-12-22', '08:00:00', '16:45:00', 'hadir', NOW(), NOW()),
(3, '2025-12-22', '07:50:00', '16:25:00', 'hadir', NOW(), NOW()),
(4, '2025-12-22', '08:10:00', NULL, 'belum_pulang', NOW(), NOW()),
(5, '2025-12-22', '08:05:00', '16:40:00', 'hadir', NOW(), NOW());

-- Selasa 23 Desember 2025
INSERT INTO absensi (user_id, tanggal, jam_masuk, jam_keluar, status, created_at, updated_at) VALUES
(1, '2025-12-23', '07:40:00', '16:35:00', 'hadir', NOW(), NOW()),
(2, '2025-12-23', '07:55:00', '16:50:00', 'hadir', NOW(), NOW()),
(3, '2025-12-23', '08:15:00', '17:00:00', 'hadir', NOW(), NOW()),
(4, '2025-12-23', '07:45:00', '16:30:00', 'hadir', NOW(), NOW()),
(5, '2025-12-23', '08:00:00', '16:45:00', 'hadir', NOW(), NOW()),
(6, '2025-12-23', '07:50:00', '16:40:00', 'hadir', NOW(), NOW()),
(7, '2025-12-23', '08:05:00', '16:55:00', 'hadir', NOW(), NOW());

-- Rabu 24 Desember 2025
INSERT INTO absensi (user_id, tanggal, jam_masuk, jam_keluar, status, created_at, updated_at) VALUES
(1, '2025-12-24', '07:45:00', '16:30:00', 'hadir', NOW(), NOW()),
(2, '2025-12-24', '08:00:00', '16:45:00', 'hadir', NOW(), NOW()),
(3, '2025-12-24', '07:50:00', '16:25:00', 'hadir', NOW(), NOW()),
(4, '2025-12-24', '08:10:00', '16:55:00', 'hadir', NOW(), NOW()),
(5, '2025-12-24', '08:05:00', '16:40:00', 'hadir', NOW(), NOW()),
(6, '2025-12-24', '07:40:00', '16:35:00', 'hadir', NOW(), NOW());

-- Kamis 25 Desember 2025 (Hari Libur Nasional tapi tetap ada absensi)
INSERT INTO absensi (user_id, tanggal, jam_masuk, jam_keluar, status, created_at, updated_at) VALUES
(1, '2025-12-25', '07:45:00', '16:30:00', 'hadir', NOW(), NOW()),
(2, '2025-12-25', '08:00:00', '16:45:00', 'hadir', NOW(), NOW()),
(3, '2025-12-25', '07:50:00', '16:25:00', 'hadir', NOW(), NOW()),
(4, '2025-12-25', '08:10:00', '16:55:00', 'hadir', NOW(), NOW()),
(5, '2025-12-25', '08:05:00', '16:40:00', 'hadir', NOW(), NOW()),
(6, '2025-12-25', '07:40:00', '16:35:00', 'hadir', NOW(), NOW()),
(7, '2025-12-25', '08:15:00', '17:00:00', 'hadir', NOW(), NOW());

-- Jumat 26 Desember 2025
INSERT INTO absensi (user_id, tanggal, jam_masuk, jam_keluar, status, created_at, updated_at) VALUES
(1, '2025-12-26', '07:45:00', '16:30:00', 'hadir', NOW(), NOW()),
(2, '2025-12-26', '08:00:00', '16:45:00', 'hadir', NOW(), NOW()),
(4, '2025-12-26', '08:10:00', '16:55:00', 'hadir', NOW(), NOW()),
(5, '2025-12-26', '08:05:00', '16:40:00', 'hadir', NOW(), NOW()),
(8, '2025-12-26', '07:40:00', '16:35:00', 'hadir', NOW(), NOW());


-- ========================================
-- 3. SAMPLE DATA: PENGAJUAN (Cuti/Lembur)
-- ========================================

-- Cuti Budi pada Senin 22 Desember (disetujui)
INSERT INTO pengajuan (user_id, jenis, tanggal_mulai, tanggal_selesai, durasi, status, created_at, updated_at) VALUES
(1, 'cuti', '2025-12-22', '2025-12-22', 1, 'acc', NOW(), NOW());

-- Cuti Siti pada Rabu 24 Desember (disetujui)
INSERT INTO pengajuan (user_id, jenis, tanggal_mulai, tanggal_selesai, durasi, status, created_at, updated_at) VALUES
(2, 'cuti', '2025-12-24', '2025-12-25', 2, 'acc', NOW(), NOW());

-- Lembur Ahmad pada Selasa 23 Desember (disetujui)
INSERT INTO pengajuan (user_id, jenis, tanggal, jam_lembur, nominal, status, created_at, updated_at) VALUES
(3, 'lembur', '2025-12-23', 2, 100000, 'acc', NOW(), NOW());

-- Pengajuan cuti Dimas (pending - belum disetujui)
INSERT INTO pengajuan (user_id, jenis, tanggal_mulai, tanggal_selesai, durasi, status, created_at, updated_at) VALUES
(5, 'cuti', '2025-12-29', '2025-12-31', 3, 'pending', NOW(), NOW());

-- Pengajuan sakit Nina (pending)
INSERT INTO pengajuan (user_id, jenis, tanggal, status, created_at, updated_at) VALUES
(10, 'sakit', DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'pending', NOW(), NOW());


-- ========================================
-- 4. VERIFICATION QUERIES
-- ========================================

-- Check: Absensi per hari dengan distinct user
-- Expected Senin: 5, Selasa: 7, Rabu: 6, Kamis: 7, Jumat: 5
SELECT 
    DAYNAME(tanggal) as hari,
    DATE(tanggal) as tanggal,
    COUNT(DISTINCT user_id) as jumlah_karyawan
FROM absensi
WHERE tanggal BETWEEN '2025-12-22' AND '2025-12-26'
GROUP BY tanggal, hari
ORDER BY tanggal ASC;

-- Check: Cuti yang disetujui per hari
SELECT 
    DAYNAME(tanggal_mulai) as hari,
    DATE(tanggal_mulai) as tanggal,
    COUNT(*) as jumlah_cuti
FROM pengajuan
WHERE jenis = 'cuti' 
  AND status = 'acc'
  AND tanggal_mulai BETWEEN '2025-12-22' AND '2025-12-26'
GROUP BY tanggal_mulai, hari
ORDER BY tanggal_mulai ASC;

-- Check: Pengajuan pending
SELECT COUNT(*) as pengajuan_pending FROM pengajuan WHERE status = 'pending';

-- Check: Absensi hari ini (bila hari ini adalah 22 Desember)
SELECT 
    COUNT(DISTINCT user_id) as sudah_absen,
    (SELECT COUNT(*) FROM users WHERE role = 'karyawan') - COUNT(DISTINCT user_id) as belum_absen
FROM absensi
WHERE DATE(tanggal) = CURDATE();

-- Check: Total karyawan
SELECT COUNT(*) as total_karyawan FROM users WHERE role = 'karyawan';

-- Check: Karyawan baru hari ini
SELECT COUNT(*) as karyawan_baru FROM users 
WHERE role = 'karyawan' AND DATE(created_at) = CURDATE();


-- ========================================
-- 5. CLEANUP (Jika ingin reset)
-- ========================================
-- DELETE FROM pengajuan WHERE tanggal_mulai >= '2025-12-22';
-- DELETE FROM absensi WHERE tanggal >= '2025-12-22';
-- DELETE FROM users WHERE role = 'karyawan' AND email LIKE '%@example.com%';


-- ========================================
-- 6. NOTES
-- ========================================
/*
- Data di atas adalah untuk testing minggu kerja 22-26 Desember 2025
- Senin 22: 5 absensi (1 belum pulang)
- Selasa 23: 7 absensi
- Rabu 24: 6 absensi (1 cuti - Budi)
- Kamis 25: 7 absensi (2 cuti - Budi & Siti)
- Jumat 26: 5 absensi
- Cuti tersetujui: Budi (22), Siti (24-25)
- Pengajuan pending: Dimas (cuti 29-31), Nina (sakit)

Expected Dashboard Results:
- Total Karyawan: 10
- Absensi Minggu: [5, 7, 6, 7, 5]
- Cuti Minggu: [1, 0, 1, 1, 0]
- Sudah Absen Hari Ini: 5 (if today = 22)
- Belum Absen Hari Ini: 5 (if today = 22)
- Notifikasi Pending: 2 items
*/
