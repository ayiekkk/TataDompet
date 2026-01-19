<?php 

$conn = mysqli_connect("localhost", "root", "", "db_tatadompet");

if(!$conn) {
    die ("Gagal terhubung dengan database: " . mysqli_connect_error());
}

?>