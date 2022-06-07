<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="assets/css/style_login.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Toko Alat Kesehatan</title>
</head>

<body>
  <?php
  include 'config.php';
  include 'modul/report_pdf.php';
  kirim_email();
  ?>
  <nav class="navbar navbar-light bg-light mb-3">
    <div class="container-fluid d-flex">
      <a class="navbar-brand" href="index.php">
        <img src="https://images.squarespace-cdn.com/content/v1/5df7ea83e9ee344914a8b6bf/1609528844584-UAJB1838Y6S2XPVY7OAI/health-e-commerce-logo.jpg" class="d-inline-block align-text-top logo">
      </a>
      <div class="d-flex">
        <?php
        if (empty($_SESSION['username'])) {
          echo ('<a href="login.php" class="btn btn-primary">Login</a>');
        } else {
          $id_keranjang = $_SESSION['id_keranjang'];
          $id_pelanggan = $_SESSION['id_pelanggan'];
          echo ('<p class="my-auto mr-3">Welcome, ' . $_SESSION['nama'] . '</p><a href="logout.php" class="btn btn-danger">Logout</a>');
        }
        ?>
      </div>
    </div>
  </nav>
  <div class="container">
    <h3 class="text-center">Toko Alat Kesehatan<br>Laporan Belanja Anda</h3>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <?php
            $sql = mysqli_query($koneksi, "SELECT a.ID_PELANGGAN,a.NAMA,a.ALAMAT,a.HP,b.TGL_TRAN,a.PAYPAL,b.NAMA_BANK,b.METODE FROM pelanggan a JOIN nota b ON a.ID_PELANGGAN=b.ID_PELANGGAN WHERE a.ID_PELANGGAN='$id_pelanggan'");
            while ($data = mysqli_fetch_array($sql)) :
            ?>
              <div class="mb-3">
                <label for="userID" class="form-label">User ID</label>
                <input type="text" class="form-control" value="<?= $data['ID_PELANGGAN'] ?>" readonly>
              </div>
              <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" value="<?= $data['NAMA'] ?>" readonly>
              </div>
              <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" value="<?= $data['ALAMAT'] ?>" readonly>
              </div>
              <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" class="form-control" value="<?= $data['HP'] ?>" readonly>
              </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="tanggal" class="form-label">Tanggal</label>
              <input type="text" class="form-control" value="<?= $data['TGL_TRAN'] ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="paypalID" class="form-label">Paypal ID</label>
              <input type="text" class="form-control" value="<?= $data['PAYPAL'] ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="bank" class="form-label">Nama Bank</label>
              <input type="text" class="form-control" value="<?= $data['NAMA_BANK'] ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="metode" class="form-label">Metode</label>
              <input type="text" class="form-control" value="<?= $data['METODE'] ?>" readonly>
            </div>
          <?php
            endwhile;
          ?>
          </div>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">Nama Produk</th>
              <th scope="col">Jumlah</th>
              <th scope="col">Harga</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $sql = mysqli_query($koneksi, "SELECT CONCAT(b.NAMA_BARANG,' ',b.ID_BARANG) AS NAMA_BARANG,a.JUMLAH_BELI,a.JUMLAH_BELI*b.HARGA_BARANG AS HARGA FROM detail a JOIN barang b ON a.ID_BARANG=b.ID_BARANG JOIN nota c ON c.ID_NOTA=a.ID_NOTA WHERE c.ID_PELANGGAN='$id_pelanggan'");
            while ($data1 = mysqli_fetch_array($sql)) :
            ?>
              <tr>
                <th scope="row"><?= $no; ?></th>
                <td><?= $data1['NAMA_BARANG']; ?></td>
                <td><?= $data1['JUMLAH_BELI']; ?></td>
                <td><?= rupiah($data1['HARGA']); ?></td>
              </tr>
            <?php
              $no++;
            endwhile
            ?>
          </tbody>
        </table>
        <?php
        $sql = mysqli_query($koneksi, "SELECT TOTAL_TRANS FROM nota WHERE ID_PELANGGAN='$id_pelanggan'");
        $data2 = mysqli_fetch_array($sql);
        ?>
        <p>Total belanja (termasuk pajak) : <?= rupiah($data2['TOTAL_TRANS']) ?></p>
        <a href="modul/report_pdf.php" class="btn btn-danger">ekspor pdf</a>
        <br>
        <p style="text-align: right;"><u>TANDATANGAN TOKO<u></p>
      </div>
    </div>
  </div>

  <?php
  function rupiah($angka)
  {
    $hasil_rupiah = "Rp" . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
  }
  ?>
</body>

</html>