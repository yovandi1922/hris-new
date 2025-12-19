<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Slip Gaji</title>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f3f3f3;
    }
    .container {
        margin-left: 260px;
        padding: 30px;
    }
    .sidebar {
        width: 240px;
        height: 100vh;
        background: linear-gradient(#f5f5f5, #e5e5e5);
        position: fixed;
        left: 0;
        top: 0;
        padding: 20px;
        box-sizing: border-box;
    }
    .sidebar h2 {
        margin: 0 0 30px;
    }
    .menu {
        margin-top: 20px;
    }
    .menu-item {
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 8px;
        cursor: pointer;
    }
    .menu-item.active {
        background: #e0e0e0;
    }
    .content-box {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }
    th {
        background: #d8e6fa;
        text-align: left;
    }
    .btn-download {
        padding: 10px 20px;
        background: white;
        border: 1px solid #cfcfcf;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 15px;
    }
</style>
</head>
<body>

<div class="sidebar">
    <h2>paradise.corp</h2>
    <div class="menu">
        <div class="menu-item">Dashboard</div>
        <div class="menu-item">Absensi</div>
        <div class="menu-item">Pengajuan</div>
        <div class="menu-item active">Gaji Gaji</div>
    </div>
</div>

<div class="container">
    <h1>Slip Gaji</h1>

    <div class="content-box">
        <h3>1 Agustus 2025 - 31 Agustus 2025</h3>
        <table>
            <tr>
                <th>Pendapatan</th>
                <th>Jumlah</th>
            </tr>
            <tr><td>Gaji Pokok</td><td>Rp. 3.000.000</td></tr>
            <tr><td>Tunjangan Transport</td><td>Rp. 300.000</td></tr>
            <tr><td>Tunjangan Makan</td><td>Rp. 250.000</td></tr>
            <tr><td>Lembur</td><td>Rp. 90.000</td></tr>
            <tr><th>Total Pendapatan</th><th>Rp. 3.640.000</th></tr>
        </table>

        <table style="margin-top:30px;">
            <tr>
                <th>Potongan</th>
                <th>Jumlah</th>
            </tr>
            <tr><td>BPJS Kesehatan</td><td>Rp. 120.000</td></tr>
            <tr><td>PPh 21 (Pajak)</td><td>Rp. 50.000</td></tr>
            <tr><td>Potongan Alfa (1 Hari)</td><td>Rp. 115.000</td></tr>
            <tr><td>Potongan Terlambat (3x)</td><td>Rp. 30.000</td></tr>
            <tr><th>Total Potongan</th><th>Rp. 315.000</th></tr>
            <tr><th>Total Diterima</th><th>Rp. 3.325.000</th></tr>
        </table>
    </div>

    <button class="btn-download">Download Slip Gaji</button>

    <div class="content-box">
        <h3>Riwayat Bon Gaji</h3>
        <table>
            <tr>
                <th>Bulan / Tahun</th>
                <th>Tanggal Bayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <tr>
                <td>2 September</td>
                <td>Rp 800.000</td>
                <td>Biaya anak sekolah</td>
                <td><button class="btn-download">Lihat Slip</button></td>
            </tr>
            <tr>
                <td>29 Agustus</td>
                <td>Rp 500.000</td>
                <td>Kebutuhan rumah</td>
                <td><button class="btn-download">Lihat Slip</button></td>
            </tr>
            <tr>
                <td>22 Agustus</td>
                <td>Rp 750.000</td>
                <td>Biaya hidup</td>
                <td><button class="btn-download">Lihat Slip</button></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
