<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
//include "config/koneksi.php";
//cek data

include('assets/PHPMailer/src/Exception.php');
include('assets/PHPMailer/src/PHPMailer.php');
include('assets/PHPMailer/src/SMTP.php');

function kirim_email()
{
  //mengeksekusi file koneksi.php
  include "config.php";
  //mengeksekusi library dompdf
  require_once("assets/dompdf/autoload.inc.php");
  session_start();
  $id_pelanggan = $_SESSION['id_pelanggan'];

  //membuat konstruktor
  $dompdf = new Dompdf();
  //membaca data dari database
  $query = mysqli_query($koneksi, "SELECT CONCAT(b.NAMA_BARANG,' ',b.ID_BARANG) AS NAMA_BARANG,a.JUMLAH_BELI,a.JUMLAH_BELI*b.HARGA_BARANG AS HARGA FROM detail a JOIN barang b ON a.ID_BARANG=b.ID_BARANG JOIN nota c ON c.ID_NOTA=a.ID_NOTA WHERE c.ID_PELANGGAN='$id_pelanggan'");
  //membuat script html
  $html = '<html><body>
<center>
<h3>Toko Alat Kesehatan<br>Laporan Belanja Anda</h3>
</center>
<br><br><br><br><hr><br>
<table border="1" width="100%">
<tr>
  <th>No</th>
  <th>Nama Produk</th>
  <th>Jumlah</th>
  <th>Harga</th>
</tr>';
  $no = 1;
  //menuliskan data pada script html
  while ($row = mysqli_fetch_array($query)) {
    $html .= "<tr>
  <td>" . $no . "</td>
  <td>" . $row['NAMA_BARANG'] . "</td>
  <td>" . $row['JUMLAH_BELI'] . "</td>
  <td>" . $row['HARGA'] . "</td>
  </tr>";
    $no++;
  }
  $sql = mysqli_query($koneksi, "SELECT TOTAL_TRANS FROM nota WHERE ID_PELANGGAN='$id_pelanggan'");
  $data2 = mysqli_fetch_array($sql);

  $html .= "<p>Total belanja (termasuk pajak) : " . $data2['TOTAL_TRANS'] . "</p></table></body></html>";
  $dompdf->loadHtml($html);
  //setting ukuran dan orientasi kertas
  $dompdf->setPaper('A4', 'portrait');
  //rendering dari HTML ke PDF
  $dompdf->render();
  //melakukan output ke file PDF
  $fileatt = $dompdf->output();

  $email_pengirim = "admin@example.com";
  $nama_pengirim = 'TEST';
  $email_penerima = "achmadbias24@gmail.com";

  $phpmailer = new PHPMailer();
  $phpmailer->isSMTP();
  $phpmailer->Host = 'smtp.mailtrap.io';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Port = 2525;
  $phpmailer->Username = '125ce9cc54f2ed';
  $phpmailer->Password = '18da1b6730daad';
  $phpmailer->SMTPDebug = 0;


  $phpmailer->setFrom($email_pengirim, $nama_pengirim);
  $phpmailer->addAddress($email_penerima);
  $phpmailer->Subject = "Laporan Belanja";
  $phpmailer->Body = "Berikut nota belanja alat kesehatan Anda. Terima kasih telah berbelanja";
  $phpmailer->isHTML(true);
  $phpmailer->addStringAttachment($fileatt, 'nota.pdf', 'base64', 'application/pdf');
  $phpmailer->send();
}
