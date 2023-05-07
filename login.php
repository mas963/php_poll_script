<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (isset($_POST["login"])) {
    if (empty($_POST["email"]) || empty($_POST["password"])) {
        $msg = '<label>Tüm alanları doldurmalısınız</label>';
    } else {
        $query = "SELECT * FROM admins WHERE email = :email AND password = :password";
        $statement = $pdo->prepare($query);
        $statement->execute(
            array(
                'email'     =>     $_POST["email"],
                'password'     =>     $_POST["password"]
            )
        );
        $count = $statement->rowCount();
        if ($count > 0) {
            $_SESSION["email"] = $_POST["email"];
            header("location:index.php");
        } else {
            $msg = '<label>Email veya şifre hatalı</label>';
        }
    }
}


?>

<?= template_header('Giriş Yap') ?>

<div class="content update">
    <h2>Admin Giriş Sayfası</h2>
    <form method="post">
        <label for="email">Email</label>
        <input type="mail" name="email" id="email" placeholder="Email Adresi" required>
        <label for="password">Şifre</label>
        <input type="password" name="password" id="password" placeholder="Şifre">
        <input type="submit" name="login" value="Giriş Yap">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>