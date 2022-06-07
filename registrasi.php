<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/style_login.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Registrasi</title>
</head>

<body>
  <nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img src="https://images.squarespace-cdn.com/content/v1/5df7ea83e9ee344914a8b6bf/1609528844584-UAJB1838Y6S2XPVY7OAI/health-e-commerce-logo.jpg" class="d-inline-block align-text-top logo">
      </a>
    </div>
  </nav>
  <!-- card -->
  <div class="container">
    <div class="row">
      <h3 class="text-center mb-3">Form Registrasi</h3>
      <div class="card">
        <div class="card-body">
          <form method="POST" action="">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Retype Password</label>
              <input type="password" class="form-control">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
              <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
              <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
            </div>
            <div class="mb-3">
              <label for="gender" class="form-label">Gender</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="gender" value="L">
                <label class="form-check-label" for="gender">
                  Male
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="gender" value="M">
                <label class="form-check-label" for="gender">
                  Female
                </label>
              </div>
            </div>
            <div class="mb-3">
              <label for="alamat" class="form-label">Address</label>
              <textarea name="alamat" id="alamat" cols="133" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="kota" class="form-label">City</label>
              <select class="form-select" aria-label="Default select example" name="kota" required>
                <option selected></option>
                <option value="Jakarta">Jakarta</option>
                <option value="Surabaya">Surabaya</option>
                <option value="Bandung">Bandung</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="no_hp" class="form-label">Contact No</label>
              <input type="text" class="form-control" id="no_hp" name="no_hp" required>
            </div>
            <div class="mb-3">
              <label for="paypal" class="form-label">Paypal ID</label>
              <input type="text" class="form-control" id="paypal" name="paypal" required>
            </div>
            <button type="submit" name="bsimpan" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- php -->
  <?php
  include 'config.php';
  if (isset($_POST['bsimpan'])) {
    $sql = ("INSERT INTO pelanggan VALUES('',NULL,'$_POST[username]','$_POST[password]','$_POST[email]','$_POST[nama]','$_POST[tgl_lahir]','$_POST[gender]','$_POST[alamat]','$_POST[kota]','$_POST[no_hp]','$_POST[paypal]')");
    $query = mysqli_query($koneksi, $sql);
    if ($query) {
      $keranjang = mysqli_query($koneksi, "SELECT ID_PELANGGAN FROM pelanggan where USERNAME='$_POST[username]'");
      $data = mysqli_fetch_array($keranjang);
      $input_keranjang = mysqli_query($koneksi, "INSERT INTO KERANJANG VALUES('','$data[ID_PELANGGAN]',NULL)");
      if ($input_keranjang) {
        $select_keranjang = mysqli_query($koneksi, "SELECT ID_KERANJANG FROM keranjang WHERE ID_PELANGGAN='$data[ID_PELANGGAN]'");
        $data1 = mysqli_fetch_array($select_keranjang);
        $update_pelanggan = mysqli_query($koneksi, "UPDATE pelanggan SET ID_KERANJANG = '$data1[ID_KERANJANG]' WHERE ID_PELANGGAN= '$data[ID_PELANGGAN]'");
        if ($update_pelanggan) {
          echo "<script>alert('Registrasi berhasil, silakan login');
        document.location='login.php';</script>";
        }
      }
    }
  }
  ?>
  <!-- end php -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>