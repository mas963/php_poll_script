<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = "";

if (isset($_GET['id']) && isset($_SESSION["email"])) {
    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($poll) {
        $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE poll_id = ? ORDER BY votes DESC');
        $stmt->execute([$_GET['id']]);
        $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_votes = 0;
        foreach ($poll_answers as $poll_answer) {
            $total_votes += $poll_answer['votes'];
        }
    } else {
        exit('Bu kimliğe sahip anket mevcut değil');
    }
} else {
    header('Location: index.php');
}
?>

<?= template_header('Anket Sonuçları') ?>

<div class="content poll-result">
    <h2><?= $poll['title'] ?></h2>
    <p><?= $poll['description'] ?></p>
    <div class="wrapper">

        <?php
        if ($total_votes == 0) {
            echo '<div class="alert alert-warning" role="alert">Hiç oy kullanılmadığı için sonuçlar gösterilemiyor</div>';
        } else {
            foreach ($poll_answers as $poll_answer) {
                echo '<div class="poll-question">
                <p>' . $poll_answer['title'] . ' <span>(' . $poll_answer['votes'] . ' Oy)</span></p>
                <div class="result-bar" style="width:' . @round(($poll_answer['votes'] / $total_votes) * 100) . '%">
                    ' . @round(($poll_answer['votes'] / $total_votes) * 100) . '%
                </div>
            </div>';
            }
        }
        ?>
    </div>
</div>

<?= template_footer() ?>