<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_mahasiswa";
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

/*
    STRUKTUR TABEL YANG BENAR SESUAI GAMBAR:

    CREATE TABLE `data_mahasiswa` (
      `NIM` int(9) NOT NULL AUTO_INCREMENT,
      `Nama` varchar(100) NOT NULL,
      `Jurusan` varchar(50) NOT NULL,
      `Angkatan` int(3) NOT NULL,
      PRIMARY KEY (`NIM`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/

// Routing sederhana
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Helper untuk sanitasi input
function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Proses CRUD
$notif = '';
if ($page == 'mahasiswa') {
    // Tambah Data
    if (isset($_POST['tambah'])) {
        $NIM      = trim($_POST['nim']);
        $Nama     = trim($_POST['nama']);
        $Jurusan  = trim($_POST['jurusan']);
        $Angkatan = trim($_POST['angkatan']);
        if ($NIM && $Nama && $Jurusan && $Angkatan) {
            $sql = "INSERT INTO data_mahasiswa (NIM, Nama, Jurusan, Angkatan) VALUES ('$NIM', '$Nama', '$Jurusan', '$Angkatan')";
            if (mysqli_query($conn, $sql)) {
                $notif = '<div class="alert alert-success shadow-sm">Data berhasil ditambahkan.</div>';
            } else {
                $notif = '<div class="alert alert-danger shadow-sm">Gagal menambah data: ' . esc(mysqli_error($conn)) . '</div>';
            }
        } else {
            $notif = '<div class="alert alert-warning shadow-sm">Semua field wajib diisi.</div>';
        }
    }
    // Edit Data
    if (isset($_POST['update'])) {
        $NIM      = trim($_POST['NIM']);
        $Nama     = trim($_POST['Nama']);
        $Jurusan  = trim($_POST['Jurusan']);
        $Angkatan = trim($_POST['Angkatan']);
        if ($NIM && $Nama && $Jurusan && $Angkatan) {
            $sql = "UPDATE data_mahasiswa SET Nama='$Nama', Jurusan='$Jurusan', Angkatan='$Angkatan' WHERE NIM='$NIM'";
            if (mysqli_query($conn, $sql)) {
                $notif = '<div class="alert alert-success shadow-sm">Data berhasil diupdate.</div>';
            } else {
                $notif = '<div class="alert alert-danger shadow-sm">Gagal update data: ' . esc(mysqli_error($conn)) . '</div>';
            }
        } else {
            $notif = '<div class="alert alert-warning shadow-sm">Semua field wajib diisi.</div>';
        }
    }
    // Hapus Data
    if (isset($_GET['hapus'])) {
        $NIM = $_GET['hapus'];
        $sql = "DELETE FROM data_mahasiswa WHERE NIM='$NIM'";
        if (mysqli_query($conn, $sql)) {
            $notif = '<div class="alert alert-success shadow-sm">Data berhasil dihapus.</div>';
        } else {
            $notif = '<div class="alert alert-danger shadow-sm">Gagal menghapus data: ' . esc(mysqli_error($conn)) . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Data Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            min-height: 100vh;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 0;
            text-align: center;
            margin-top: 40px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.04);
        }
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 4px solid #4f8cff;
            box-shadow: 0 2px 10px rgba(79,140,255,0.15);
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(79,140,255,0.08);
        }
        .card-custom {
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(79,140,255,0.10);
            border: none;
        }
        .table thead th {
            background: linear-gradient(90deg, #4f8cff 0%, #6ee7b7 100%);
            color: #fff;
            border: none;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f3f8ff;
        }
        .btn-custom {
            border-radius: 20px;
            font-weight: 500;
            transition: 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 2px 8px rgba(79,140,255,0.12);
        }
        .form-control:focus {
            border-color: #4f8cff;
            box-shadow: 0 0 0 0.2rem rgba(79,140,255,.15);
        }
        .jumbotron {
            background: linear-gradient(120deg, #4f8cff 0%, #6ee7b7 100%);
            color: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(79,140,255,0.10);
        }
        .section-title {
            font-weight: 700;
            color: #4f8cff;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }
        .table td, .table th {
            vertical-align: middle !important;
        }
        .icon-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #4f8cff 0%, #6ee7b7 100%);">
    <a class="navbar-brand font-weight-bold" href="?page=home"><i class="fa-solid fa-graduation-cap"></i> Dashboard Mahasiswa</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item<?php if($page=='home') echo ' active'; ?>">
                <a class="nav-link" href="?page=home"><i class="fa-solid fa-house"></i> Home</a>
            </li>
            <li class="nav-item<?php if($page=='mahasiswa') echo ' active'; ?>">
                <a class="nav-link" href="?page=mahasiswa"><i class="fa-solid fa-users"></i> Data Mahasiswa</a>
            </li>
            <li class="nav-item<?php if($page=='about') echo ' active'; ?>">
                <a class="nav-link" href="?page=about"><i class="fa-solid fa-user"></i> About</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-4 mb-5">
<?php
if ($page == 'home') {
?>
    <div class="jumbotron shadow-sm px-5 py-4">
        <h1 class="display-4 mb-3"><i class="fa-solid fa-graduation-cap"></i> Selamat Datang di Dashboard Data Mahasiswa</h1>
        <p class="lead">Website ini digunakan untuk mengelola data mahasiswa secara mudah dan efisien. Anda dapat menambah, mengedit, dan menghapus data mahasiswa melalui menu Data Mahasiswa.</p>
        <hr class="my-4" style="border-top:2px solid #fff;">
        <p>Gunakan menu navigasi di atas untuk mengakses fitur yang tersedia.</p>
    </div>
<?php
} elseif ($page == 'mahasiswa') {
    echo $notif;
    // Form Edit
    if (isset($_GET['edit'])) {
        $nim_edit = $_GET['edit'];
        $q = mysqli_query($conn, "SELECT * FROM data_mahasiswa WHERE NIM='$nim_edit'");
        $data = mysqli_fetch_assoc($q);
        if ($data) {
?>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <h3 class="section-title"><i class="fa-solid fa-pen-to-square"></i> Edit Data Mahasiswa</h3>
                    <form method="post">
                        <input type="hidden" name="NIM" value="<?php echo esc($data['NIM']); ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label><i class="fa-solid fa-id-card"></i> NIM</label>
                                <input type="text" class="form-control" value="<?php echo esc($data['NIM']); ?>" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label><i class="fa-solid fa-user"></i> Nama</label>
                                <input type="text" name="Nama" class="form-control" value="<?php echo esc($data['Nama']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label><i class="fa-solid fa-building-columns"></i> Jurusan</label>
                                <select name="Jurusan" class="form-control" required>
                                    <?php
                                    $jurusan_list = [
                                        "Teknik Informatika",
                                        "Teknik Komputer",
                                        "Teknologi Rekayasa Komputer",
                                        "Teknik Informatika Multimedia"
                                    ];
                                    foreach ($jurusan_list as $j) {
                                        $sel = ($data['Jurusan'] == $j) ? 'selected' : '';
                                        echo "<option $sel>$j</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label><i class="fa-solid fa-calendar"></i> Angkatan</label>
                                <input type="number" name="Angkatan" class="form-control" value="<?php echo esc($data['Angkatan']); ?>" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="update" class="btn btn-success btn-custom icon-btn"><i class="fa-solid fa-save"></i> Update</button>
                            <a href="?page=mahasiswa" class="btn btn-secondary btn-custom icon-btn"><i class="fa-solid fa-arrow-left"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
        }
    } else {
?>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <h3 class="section-title"><i class="fa-solid fa-user-plus"></i> Tambah Data Mahasiswa</h3>
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label><i class="fa-solid fa-id-card"></i> NIM</label>
                                <input type="number" name="nim" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label><i class="fa-solid fa-user"></i> Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label><i class="fa-solid fa-building-columns"></i> Jurusan</label>
                                <select name="jurusan" class="form-control" required>
                                    <option value="">Pilih Jurusan</option>
                                    <option>Teknik Informatika</option>
                                    <option>Teknik Komputer</option>
                                    <option>Teknologi Rekayasa Komputer</option>
                                    <option>Teknik Informatika Multimedia</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label><i class="fa-solid fa-calendar"></i> Angkatan</label>
                                <input type="number" name="angkatan" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <button type="submit" name="tambah" class="btn btn-primary btn-block btn-custom icon-btn w-100"><i class="fa-solid fa-plus"></i> Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    }
    // Tabel Data Mahasiswa
    $result = mysqli_query($conn, "SELECT * FROM data_mahasiswa ORDER BY NIM ASC");
?>
    <div class="card card-custom">
        <div class="card-body">
            <h3 class="section-title"><i class="fa-solid fa-list"></i> Daftar Mahasiswa</h3>
            <div class="table-responsive">
            <table class="table table-bordered table-striped shadow-sm">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-id-card"></i> NIM</th>
                        <th><i class="fa-solid fa-user"></i> Nama</th>
                        <th><i class="fa-solid fa-building-columns"></i> Jurusan</th>
                        <th><i class="fa-solid fa-calendar"></i> Angkatan</th>
                        <th style="width:120px;"><i class="fa-solid fa-gear"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo esc($row['NIM']); ?></td>
                        <td><?php echo esc($row['Nama']); ?></td>
                        <td><?php echo esc($row['Jurusan']); ?></td>
                        <td><?php echo esc($row['Angkatan']); ?></td>
                        <td>
                            <a href="?page=mahasiswa&edit=<?php echo esc($row['NIM']); ?>" class="btn btn-sm btn-warning btn-custom icon-btn mb-1"><i class="fa-solid fa-pen"></i> Edit</a>
                            <a href="?page=mahasiswa&hapus=<?php echo esc($row['NIM']); ?>" class="btn btn-sm btn-danger btn-custom icon-btn mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fa-solid fa-trash"></i> Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
<?php
} elseif ($page == 'about') {
?>
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card card-custom p-4">
                <img src="WhatsApp Image 2024-02-12 at 10.07.51_af2970f9.jpg" alt="Foto Developer" class="profile-img mx-auto d-block">
                <h3 class="mt-3 mb-2" style="color:#4f8cff;">Agung Rizky Putra Pratama</h3>
                <p>Halo! Saya <b>Agung Rizky Putra Pratama</b>, seorang web developer yang suka membangun aplikasi sederhana dan bermanfaat. Website ini dibuat sebagai latihan CRUD dan tampilan dashboard menggunakan Bootstrap 4.</p>
                <div class="mt-3">
                    <a href="https://github.com/agungpratama" target="_blank" class="btn btn-dark btn-custom icon-btn mr-2"><i class="fab fa-github"></i> Github</a>
                    <a href="mailto:agung@email.com" class="btn btn-info btn-custom icon-btn"><i class="fa-solid fa-envelope"></i> Email</a>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
</div>
<div class="footer">
    &copy; <?php echo date('Y'); ?> Dashboard Data Mahasiswa. All rights reserved.
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>
