<?php
session_start();
include 'functions.php';
$msg = '';

if (isset($_GET['id']) && isset($_SESSION["email"])) {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare('SELECT p.*, GROUP_CONCAT(pa.title ORDER BY pa.id) AS answers FROM polls p LEFT JOIN poll_answers pa ON pa.poll_id = p.id WHERE p.id = ? GROUP BY p.id;');
    $stmt->execute([$_GET['id']]);
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);
    $answersNewLine = str_replace(",", "\n", $poll["answers"]);

    if (!empty($_POST)) {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
        $stmt = $pdo->prepare('UPDATE polls SET title = ?, description = ?, end_date = ? WHERE id = ?');
        $stmt->execute([$title, $description, $end_date, $id]);
        $poll_id = $pdo->prepare('DELETE  FROM poll_answers WHERE poll_id = ?');
        $poll_id->execute([$id]);
        $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';
        foreach ($answers as $answer) {
            if (empty($answer)) continue;
            $stmt = $pdo->prepare('INSERT INTO poll_answers (poll_id, title) VALUES (?, ?)');
            $stmt->execute([$id, $answer]);
        }
        $msg = 'Anket güncelleme işlemi başarılı';
        header('Location: vote.php?id=' . $id);
    }
} else {
    header('Location: index.php');
}

?>

<?= template_header('Anketi Düzenle') ?>

<div class="content update">
    <h2>Anketi Düzenle</h2>
    <form method="post">
        <input type="hidden" value="<?php echo $poll['id'] ?>" name="id" id="id">
        <label for="title">Soru</label>
        <input type="text" name="title" id="title" placeholder="Soru" value="<?php echo $poll['title'] ?>" required>
        <label for="description">Açıklama (varsa)</label>
        <input type="text" name="description" id="description" placeholder="Açıklama" value="<?php echo $poll['description'] ?>">
        <label for="answers">Şıklar (her satır birer şık)</label>
        <textarea name="answers" id="answers" placeholder="Şıklar" required><?php echo $answersNewLine ?></textarea>

        <label for="end_date">Bitiş Tarihi (girilmediği taktirde süresiz olacaktır)</label>
        <input type="text" name="end_date" id="end_date" placeholder="Bitiş tarihi (Örn: 2023-01-07 23:59:59)" value="<?php echo $poll['end_date'] ?>">

        <input type="submit" value="Anketi Güncelle">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>