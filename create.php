<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (!empty($_POST)) {
    if (isset($_SESSION["email"])) {
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
        $stmt = $pdo->prepare('INSERT INTO polls (title, description,end_date) VALUES (?, ?, ?)');
        $stmt->execute([$title, $description, $end_date]);
        $poll_id = $pdo->lastInsertId();
        $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';
        foreach ($answers as $answer) {
            if (empty($answer)) continue;
            $stmt = $pdo->prepare('INSERT INTO poll_answers (poll_id, title) VALUES (?, ?)');
            $stmt->execute([$poll_id, $answer]);
        }
        $msg = 'Anket oluşturma işlemi başarılı';
    } else {
        header("location:index.php");
    }
}
?>

<?= template_header('Anket Oluştur') ?>

<div class="content update">
    <h2>Yeni Anket</h2>
    <form method="post">
        <label for="title">Soru</label>
        <input type="text" name="title" id="title" placeholder="Soru" required>
        <label for="description">Açıklama (varsa)</label>
        <input type="text" name="description" id="description" placeholder="Açıklama">
        <label for="answers">Şıklar (her satır birer şık)</label>
        <textarea name="answers" id="answers" placeholder="Şıklar" required></textarea>

        <label for="end_date">Bitiş Tarihi (girilmediği taktirde süresiz olacaktır)</label>
        <input type="text" name="end_date" id="end_date" placeholder="Bitiş tarihi (Örn: 2023-01-07 23:59:59)">

        <input type="submit" value="Anket Oluştur">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>