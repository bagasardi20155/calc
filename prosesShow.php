<?php

    include "koneksi.php";
    $koneksi = new koneksi();

    $data = $koneksi->show_history();

    echo json_encode($data);
    exit;
?>