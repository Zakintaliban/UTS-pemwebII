<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "siakad";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}
