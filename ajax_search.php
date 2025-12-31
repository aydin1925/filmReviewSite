<?php


require_once 'config/db.php';

if (isset($_POST['query'])) {
    $inpText = trim($_POST['query']);

    if (!empty($inpText)) {
        // LIMIT 5: Listeyi boğmamak için en fazla 5 sonuç göster
        $sql = "SELECT movie_id, title, image_url, release_year FROM movies WHERE title LIKE :query OR director LIKE :query LIMIT 5";
        $stmt = $db->prepare($sql);
        $stmt->execute(['query' => "%$inpText%"]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as $row) {
                echo '<a href="detay.php?id=' . $row['movie_id'] . '" class="list-group-item list-group-item-action d-flex align-items-center">';
                echo '<img src="' . $row['image_url'] . '" class="rounded me-3" width="40" height="60" style="object-fit:cover;">';
                echo '<div>';
                echo '<h6 class="mb-0 text-dark" style="font-size:14px;">' . htmlspecialchars($row['title']) . '</h6>';
                echo '<small class="text-muted" style="font-size:11px;">' . $row['release_year'] . '</small>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo '<p class="list-group-item border-0 text-muted p-3">Sonuç bulunamadı.</p>';
        }
    }
}
?>