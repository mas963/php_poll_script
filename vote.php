<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = "";
$rightToVote  = false;
$dateNow = date("Y-m-d H:i:s");

if (isset($_GET['id'])) {
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $voters = $pdo->prepare('SELECT COUNT(*) as count FROM voters WHERE ip_adress = ? AND poll_id = ?');
    $voters->execute([$ip_address, $_GET['id']]);
    $votersResult = $voters->fetch(PDO::FETCH_ASSOC);

    if ($votersResult["count"] > 0) {
        $msg = "zaten oy kullanmışsın " . $ip_address;
        if (isset($_SESSION["email"])) {
            $rightToVote = true;
            $msg = '<div class="alert alert-primary" role="alert">Zaten oy vermişsiniz fakat yetkili olduğunuz için birden fazla oy kullanabilirsiniz.</div>';
        } else {
            $rightToVote = false;
        }
    } else {
        $rightToVote = true;
    }

    $stmt = $pdo->prepare('SELECT * FROM polls WHERE id = ? AND (end_date > ? OR end_date = "0000-00-00 00:00:00")');
    $stmt->execute([$_GET['id'], $dateNow]);
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($poll) {
        $stmt = $pdo->prepare('SELECT * FROM poll_answers WHERE poll_id = ?');
        $stmt->execute([$_GET['id']]);
        $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (isset($_POST['poll_answer']) && $rightToVote) {
            $stmt = $pdo->prepare('UPDATE poll_answers SET votes = votes + 1 WHERE id = ?');
            $stmt->execute([$_POST['poll_answer']]);

            $votersInsert = $pdo->prepare('INSERT INTO voters (ip_adress, poll_id) VALUES (?,?)');
            $votersInsert->execute([$ip_address, $_GET['id']]);

            header('Location: result.php?id=' . $_GET['id']);
            exit;
        }
    } else {
        exit('<h3>Böyle bir anket mevcut değil veya süresi dolmuş olabilir.</h3>');
    }
} else {
    exit('No poll ID specified.');
}
?>

<?= template_header($poll['title'] . ' Anketine oy kullan') ?>

<div class="content poll-vote">
    <h2><?= $poll['title'] ?></h2>
    <p><?= $poll['description'] ?></p>
    <form action="vote.php?id=<?= $_GET['id'] ?>" method="post">
        <?php for ($i = 0; $i < count($poll_answers); $i++) : ?>
            <label>
                <input type="radio" name="poll_answer" value="<?= $poll_answers[$i]['id'] ?>" <?= $i == 0 ? ' checked' : '' ?>>
                <?= $poll_answers[$i]['title'] ?>
            </label>
        <?php endfor; ?>
        <div>
            <?php
            if (isset($_SESSION["email"])) {
                echo $msg;
                echo '<input type="submit" value="Oyla">';
            } else {
                if ($rightToVote) {
                    echo '<input type="submit" value="Oyla">';
                } else {
                    echo '<div class="alert alert-warning" role="alert">
                    Sadece bir defa oy verebilirsiniz!
                  </div>';
                }
            }
            ?>
            <?php
            if (isset($_SESSION["email"])) {
                echo '<a href="result.php?id=' . $poll['id'] . '"><i class="fas fa-user-shield"></i> Sonuçları Göster</a>';
            }
            ?>
        </div>
    </form>
</div>

<?= template_footer() ?>