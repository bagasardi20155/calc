<?php

include "koneksi.php";
$koneksi = new koneksi();

// POST Data dari ajax
$data['perhitungan'] = $_POST['perhitungan'];
$data['hasil'] = $_POST['hasil'];

$berhasilInsert = $koneksi->insert_history($data['perhitungan'], $data['hasil']);

if($berhasilInsert) {
    echo json_encode($data);
}

?>