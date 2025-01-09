<?php
    include "load.php";

    $email = $_POST['email'];
    $paswd = $_POST['password'];

    $dbh = $koneksi->query("select * from users where email = '".$email."' and active = 1");

        if($dbh->rowcount()==1)
        {
            $users = $dbh->fetch(PDO::FETCH_ASSOC);

            if(password_verify($paswd,$users['password']))
            {
                session_start();
                $_SESSION['username'] = $users['username'];
                $_SESSION['userid'] = $users['id'];
                $_SESSION['isLoggedIn']=true;
                header("location: home.php");
            }
            else
            {
                echo "Email atau password anda salah";
            }
        }
        else
         {
            echo "User tidak ditemukan";
        }