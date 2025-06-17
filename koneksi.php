<?php 
    $con = mysqli_connect("localhost", "root", "", "digikos");
    if(mysqli_connect_errno()){
        echo "Koneksi database gagal: " . mysqli_connect_error();
        exit();
    }
?>