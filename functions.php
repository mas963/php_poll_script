<?php
date_default_timezone_set("Europe/Istanbul");
function pdo_connect_mysql()
{
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'phpanketodevi';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
        exit('db connection error!');
    }
}

function template_header($title)
{
    $loginLogout = '';

    if (isset($_SESSION["email"])) {
        $loginLogout = '<a href="logout.php"><i class="fas fa-user"></i>Çıkış Yap</a>';
    } else {
        $loginLogout = '<a href="login.php"><i class="fas fa-sign-in-alt"></i>Giriş Yap</a>';
    }

    echo '
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>' . $title . '</title>
            <link href="assets/style.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        </head>
        <body>
        <nav class="navtop">
            <div>
                <h1>Anket Sistemi</h1>
                <a href="index.php"><i class="fas fa-poll-h"></i>Anketler</a>
                ' . $loginLogout . '
            </div>
        </nav>';
}

function template_footer()
{
    // DO NOT INDENT THE PHP CODE
    echo <<<EOT
            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        </body>
    </html>
    EOT;
}
