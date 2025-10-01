<?php
include "db.php";

// Ambil pemenang pertama
$stmt = $conn->query("SELECT winner FROM buzzer ORDER BY id ASC LIMIT 1");
$winner = $stmt->fetchColumn();

if ($winner) {
    // Jika sudah ada pemenang, cek apakah nama sama
    if (isset($_POST['name']) && $_POST['name'] === $winner) {
        echo json_encode([
            "message" => "Kamu adalah pemenang! 🎉",
            "winner" => true
        ]);
    } else {
        echo json_encode([
            "message" => $winner . " sudah lebih dulu menekan buzzer!",
            "winner" => false
        ]);
    }
    exit;
}

// Jika belum ada pemenang → simpan nama sebagai pemenang
if (isset($_POST['name'])) {
    $name = htmlspecialchars($_POST['name']);
    $stmt = $conn->prepare("INSERT INTO buzzer (winner) VALUES (:name)");
    $stmt->bindParam(":name", $name);
    $stmt->execute();

    echo json_encode([
        "message" => $name . " menekan buzzer terlebih dahulu!",
        "winner" => true
    ]);
} else {
    echo json_encode([
        "message" => "Belum ada yang menekan buzzer...",
        "winner" => false
    ]);
}
