<?php

    try{
        $koneksi = new PDO("mysql:host=localhost;dbname=buku_perpustakaan",'root','Setiawan#123');
    }

    catch(PDOException $e) {    
        echo "koneksi gagal",$e->getMessage();
    }