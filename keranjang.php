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
  session_start();
  ?>
  <!-- Header -->
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
  <!-- End Header -->
  <div class="container">
    <div class="row">
      <h3 class="text-center">Keranjang Belanja</h3>
      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead class="text-center">
              <tr>
                <th scope="col">No.</th>
                <th scope="col">Nama Produk</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Harga</th>
              </tr>
            </thead>
            <tbody class="text-center">
              <?php
              $sql = mysqli_query($koneksi, 'SELECT CONCAT(b.NAMA_BARANG," ",b.ID_BARANG) AS NAMA_BARANG,a.JUMLAH,a.JUMLAH*b.HARGA_BARANG AS HARGA FROM detail_keranjang a JOIN barang b ON a.ID_BARANG=b.ID_BARANG');
              $no = 1;
              while ($data = mysqli_fetch_array($sql)) :
              ?>
                <tr>
                  <th scope="row"><?= $no; ?></th>
                  <td><?= $data['NAMA_BARANG']; ?></td>
                  <td><?= $data['JUMLAH']; ?></td>
                  <td><?= rupiah($data['HARGA']); ?></td>
                </tr>
              <?php
                $no++;
              endwhile;
              ?>
            </tbody>
          </table>
          <br>
          <?php
          $sql = mysqli_query($koneksi, "SELECT TOTAL_KERANJANG FROM keranjang WHERE ID_KERANJANG='$id_keranjang'");
          $data1 = mysqli_fetch_array($sql);
          ?>
          <p>Total belanja (termasuk pajak) : <?= rupiah($data1['TOTAL_KERANJANG']) ?></p>
          <form action="" method="POST">
            <div class="mb-3">
              <label for="metode" class="form-label">Metode Pembayaran</label>
              <select class="form-select" aria-label="Default select example" name="metode">
                <option selected></option>
                <option value="Prepaid">Prepaid</option>
                <option value="Postpaid">Postpaid</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="bank" class="form-label">Bank</label>
              <select class="form-select" aria-label="Default select example" name="bank">
                <option selected></option>
                <option value="BCA">Bank Central Asia</option>
                <option value="BRI">Bank Rakyat Indonesia</option>
                <option value="Mandiri">Bank Mandiri</option>
              </select>
            </div>
            <!-- <a href="nota.php" class="btn btn-primary btn-block">Check Out</a> -->
            <button type="submit" name="bsimpan" class="btn btn-primary btn-block">Check Out</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- php -->
  <?php
  if (isset($_POST['bsimpan'])) {
    $tgl_trans = date('Y-m-d H:i:s');
    $selectTotalKeranjang = mysqli_query($koneksi, "SELECT TOTAL_KERANJANG FROM keranjang WHERE ID_KERANJANG='$id_keranjang'");
    $data1 = mysqli_fetch_array($selectTotalKeranjang);

    $insertNota = mysqli_query($koneksi, "INSERT INTO nota VALUES ('','$id_pelanggan','$tgl_trans','$data1[TOTAL_KERANJANG]','$_POST[metode]','$_POST[bank]')");

    if ($insertNota) {
      $selectDetailKeranjang = mysqli_query($koneksi, "SELECT ID_BARANG, JUMLAH FROM detail_keranjang WHERE ID_KERANJANG='$id_keranjang'");

      $selectNota = mysqli_query($koneksi, "SELECT ID_NOTA FROM nota WHERE ID_PELANGGAN='$id_pelanggan'");
      $id_nota = mysqli_fetch_array($selectNota);

      while ($data2 = mysqli_fetch_array($selectDetailKeranjang)) {
        $insertDetail = mysqli_query($koneksi, "INSERT INTO detail VALUES ('$id_nota[ID_NOTA]','$data2[ID_BARANG]','$data2[JUMLAH]')");
      }
      $deleteDetailKeranjang = mysqli_query($koneksi, "DELETE FROM detail_keranjang WHERE ID_KERANJANG='$id_keranjang'");

      $update_kerajang = mysqli_query($koneksi, "UPDATE keranjang SET TOTAL_KERANJANG=NULL WHERE ID_KERANJANG='$id_keranjang'");

      echo ("<script>alert('Check Out Berhasil!');
      document.location='nota.php';</script>");
    }
  }

  function rupiah($angka)
  {
    $hasil_rupiah = "Rp" . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
  }
  ?>
  <!-- end php -->
</body>

</html>