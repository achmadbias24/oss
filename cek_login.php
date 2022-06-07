<?php
include('config.php');
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM pelanggan where USERNAME='$username' and PASSWORD='$password'";
$result = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_array($result);
if ($data) {
  session_start();
  $_SESSION['username'] = $data['USERNAME'];
  $_SESSION['nama'] = $data['NAMA'];
  $_SESSION['id_pelanggan'] = $data['ID_PELANGGAN'];
  $_SESSION['id_keranjang'] = $data['ID_KERANJANG'];
  header("location:index.php");
} else {
  echo "<script>alert('Username atau Password Anda salah!')
  document.location='login.php';</script>
 ";
}
