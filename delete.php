<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the poll ID exists
if (isset($_GET['id']) && isset($_SESSION["email"])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$poll) {
        exit('Aranan id de anket bulunamadı');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM polls WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            // We also need to delete the answers for that poll
            $stmt = $pdo->prepare('DELETE FROM poll_answers WHERE poll_id = ?');
            $stmt->execute([$_GET['id']]);
            // Output msg
            $msg = 'Anket silme işlemi başarılı';
        } else {
            // User clicked the "No" button, redirect them back to the home/index page
            header('Location: index.php');
            exit;
        }
    }
} else {
    header('Location: index.php');
}
?>

<?= template_header('Delete') ?>

<div class="content delete">
    <h2>Delete Poll #<?= $poll['id'] ?></h2>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php else : ?>
        <p>#<?= $poll['id'] ?> Nolu anketi silmek istediğinizden emin misiniz?</p>
        <div class="yesno">
            <a href="delete.php?id=<?= $poll['id'] ?>&confirm=yes">Evet</a>
            <a href="delete.php?id=<?= $poll['id'] ?>&confirm=no">Hayır</a>
        </div>
    <?php endif; ?>
</div>

<?= template_footer() ?>