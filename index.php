<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();
$stmt = $pdo->query('SELECT p.*, GROUP_CONCAT(pa.title ORDER BY pa.id) AS answers FROM polls p LEFT JOIN poll_answers pa ON pa.poll_id = p.id GROUP BY p.id');
$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= template_header('Anketler') ?>

<div class="content home">
    <?php
    if (!isset($_SESSION["email"])) {
        echo '<div class="alert alert-info mt-3">
        <strong>Admin bilgileri!</strong> Email: yasar@gmail.com / Şifre: 123456 
    </div>';
    }
    ?>

    <h2>Anketler</h2>
    <?php
    if (isset($_SESSION["email"])) {
        echo '<h2>' . $_SESSION["email"] . ' yönetici girişi yaptınız</h2>
            <a href="create.php" class="create-poll">Yeni Anket Oluştur</a>';
    }
    ?>
    <table>
        <thead>
            <tr>
                <td>#</td>
                <td>Soru</td>
                <td>Şıklar</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($polls as $poll) : ?>
                <tr>
                    <td><?= $poll['id'] ?></td>
                    <td><?= $poll['title'] ?></td>
                    <td><?= $poll['answers'] ?></td>
                    <td class="actions">
                        <a href="vote.php?id=<?= $poll['id'] ?>" class="view" title="Anketi Göster"><i class="fas fa-eye fa-xs"></i></a>

                        <?php
                        if (isset($_SESSION["email"])) {
                            echo '<a href="edit.php?id=' . $poll['id'] . '" class="edit" title="Anketi Düzenle"><i class="fas fa-edit fa-xs"></i></a>

                            <a href="result.php?id=' . $poll['id'] . '" class="edit" title="Anket Sonuçları"><i class="fas fa-poll-h fa-xs"></i></a>

                            <a href="delete.php?id=' . $poll['id'] . '" class="trash" title="Anketi Sil"><i class="fas fa-trash fa-xs"></i></a>';
                        }

                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= template_footer() ?>