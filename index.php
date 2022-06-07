<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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

  <!-- Content -->
  <div class="row mx-auto">
    <h3 class="text-center">Product Page</h3>
    <div class="col-md-10">
      <div class="row mx-auto">
        <?php
        $sql = mysqli_query($koneksi, "SELECT * FROM barang");
        while ($data = mysqli_fetch_array($sql)) :
        ?>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body img-fluid">
                <h5><?= $data['NAMA_BARANG'] . " " . $data['ID_BARANG']; ?></h5>
                <div class="text-center">
                  <img src="assets/img/<?= $data['GAMBAR'] ?>" class="display">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <button type="button" class="btn btn-info btn-block" data-bs-toggle="modal" data-bs-target="#myModal<?= $data['ID_BARANG'] ?>">view</button>
                  </div>
                  <div class="col-md-6">
                    <a href="#" class="btn btn-success btn-block">buy</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- The Modal -->
          <div class="modal" id="myModal<?= $data['ID_BARANG'] ?>">
            <div class="modal-dialog">
              <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title"><?= $data['NAMA_BARANG'] . " " . $data['ID_BARANG']; ?></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="text-center">
                  <img src="assets/img/<?= $data['GAMBAR'] ?>" class="display mt-3">
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                  Harga : <?= rupiah($data['HARGA_BARANG']); ?><br>
                  Deskripsi : <?= $data['KETERANGAN']; ?>
                  <form action="" method="POST">
                    <input type="hidden" value="<?= $data['ID_BARANG']; ?>" name="id_barang" id="id_barang">
                    <input type="hidden" value="<?= $data['HARGA_BARANG']; ?>" name="harga_barang" id="harga_barang">
                    <div class="mb-3">
                      <label for="jml" class="form-label">Jumlah Beli</label>
                      <input type="number" class="form-control" id="jml" name="jml" min="1" max="100">
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                  <button type="submit" name="bsimpan" class="btn btn-success btn-block" value="<?= $data['ID_BARANG']; ?>">Buy</button>
                  <button type="button" class="btn btn-danger btn-block" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <!-- end modal -->
          </form>
        <?php endwhile; ?>
      </div>
    </div>
    <div class="col-md-2">
      <?php
      if (empty($_SESSION['username'])) {
        echo ('');
      } else {
        echo ('<a href="keranjang.php" class="btn btn-warning btn-block mb-4">
          <i class="fa-solid fa-cart-shopping"></i>
          Keranjang
        </a>');
      }
      ?>

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Kategori</h5>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
              Alat
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
              Obat
            </label>
          </div>
          <button class="btn btn-success">Search</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End Content -->

  <!-- php -->
  <?php
  if (isset($_POST['bsimpan'])) {
    $subTotal = $_POST['jml'] * $_POST['harga_barang'];
    $query = mysqli_query($koneksi, "INSERT INTO detail_keranjang VALUES ('$id_keranjang','$_POST[id_barang]','$_POST[jml]','$subTotal')");
    if ($query) {
      $totalKeranjang = mysqli_query($koneksi, "SELECT SUM(SUB_TOTAL) AS sub FROM detail_keranjang WHERE ID_KERANJANG='$id_keranjang'");
      $data1 = mysqli_fetch_array($totalKeranjang);
      $update_kerajang = mysqli_query($koneksi, "UPDATE keranjang SET TOTAL_KERANJANG = '$data1[sub]' WHERE ID_KERANJANG= '$id_keranjang'");
      echo ('<script>alert("Barang berhasil ditambahkan ke keranjang")</script>');
    }
  }

  function rupiah($angka)
  {
    $hasil_rupiah = "Rp" . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
  }
  ?>
  ?>
  <!-- end php -->


</body>

</html>