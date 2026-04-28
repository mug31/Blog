<?php
$host     = "localhost";
$username = "root";
$password = "12345678";
$database = "db_blog";

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8mb4");
?>