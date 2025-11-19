<?php

    $username = "root";
    $password = "";
    $hostname = "localhost";
    $database = "bimbel";

    $conn = mysqli_connect($hostname, $username, $password, $database);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

?>