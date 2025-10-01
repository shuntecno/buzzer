<?php
include "db.php";
$stmt = $conn->query("SELECT winner FROM buzzer ORDER BY id DESC LIMIT 1");
$winner = $stmt->fetchColumn();
echo $winner ? $winner . " menekan buzzer terlebih dahulu!" : "Belum ada yang menekan buzzer...";
