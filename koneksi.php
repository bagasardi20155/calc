<?php

class koneksi{
    var $host = "localhost";
    var $user = "root";
    var $pass = "";
    var $db = "kalkulator";
    var $conn = "";

    function __construct()
    {
       $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
    }

    function show_history()
    {
        $data = mysqli_query($this->conn, "SELECT * FROM histories");
        while($history = mysqli_fetch_array($data))
        {
            $result[] = $history;
        }

        return $result;
    }

    function insert_history($perhitungan, $hasil)
    {
        mysqli_query($this->conn, "INSERT INTO histories VALUES ('', '$perhitungan', '$hasil')");
    }
}

?>