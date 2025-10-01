<?php
include "db.php";

// Ambil siapa yang pertama tekan
$stmt = $conn->query("SELECT winner FROM buzzer ORDER BY id ASC LIMIT 1");
$winner = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Buzzer + Soal Carousel</title>
  <style>
    body {
      text-align: center;
      font-family: Arial, sans-serif;
      background: #222;
      color: #fff;
      margin: 40px;
    }
    h1 { font-size: 40px; }
    #winner {
      font-size: 28px;
      color: #FFD700;
      margin-top: 20px;
    }
    .question-box {
      margin: 40px auto;
      background: #333;
      padding: 20px;
      border-radius: 8px;
      width: 70%;
      text-align: left;
    }
    .question {
      font-size: 22px;
      margin-bottom: 20px;
    }
    .option {
      margin: 8px 0;
      font-size: 20px;
    }
    .controls {
      margin-top: 20px;
    }
    .controls button {
      padding: 10px 20px;
      font-size: 18px;
      margin: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h1>Dashboard Buzzer</h1>
  <div id="winner"><?= $winner ?: "Belum ada yang menekan buzzer..." ?></div>

  <!-- Tempat Soal -->
  <div class="question-box">
    <div id="question" class="question"></div>
    <div id="options"></div>
  </div>

  <div class="controls">
  <button onclick="prevQuestion()">⬅ Sebelumnya</button>
  <button onclick="nextQuestion()">Berikutnya ➡</button>
  <button onclick="toggleAnswer()">🔑 Tampilkan Kunci</button>
</div>


  <button onclick="resetGame()">Reset Buzzer</button>

  <script>
    // 30 Soal Pilihan Ganda
   const questions = [
  { q: "Apa kepanjangan dari OOP?", a: ["Object Oriented Programming", "Order Of Processing", "Overload Object Program", "Online Operation Process"], answer: "A" },
  { q: "Keyword untuk membuat class di PHP adalah?", a: ["function", "def", "class", "object"], answer: "C" },
  { q: "Di OOP, class digunakan untuk?", a: ["Blueprint objek", "Menyimpan data sementara", "Fungsi utama", "Menjalankan server"], answer: "A" },
  { q: "Objek adalah?", a: ["Instansiasi dari class", "Sebuah fungsi", "Database", "Framework"], answer: "A" },
  { q: "Metode dalam OOP berarti?", a: ["Fungsi dalam class", "Variabel global", "Koneksi server", "Array data"], answer: "A" },
  { q: "Property dalam class berarti?", a: ["Variabel dalam class", "Fungsi global", "File konfigurasi", "Server lokal"], answer: "A" },
  { q: "Encapsulation berarti?", a: ["Menyembunyikan detail internal", "Menduplikasi kode", "Menghapus data", "Menambahkan fungsi"], answer: "A" },
  { q: "Inheritance berarti?", a: ["Pewarisan class", "Penghapusan fungsi", "Membuat variabel baru", "Menghubungkan ke database"], answer: "A" },
  { q: "Polymorphism berarti?", a: ["Kemampuan method berbeda dengan nama sama", "Pewarisan class", "Membuat objek baru", "Menyimpan data"], answer: "A" },
  { q: "Keyword untuk mengakses property dalam objek adalah?", a: ["->", ".", ":", "::"], answer: "A" },
  { q: "Constructor di PHP ditulis dengan?", a: ["__construct()", "function()", "__init__", "constructor()"], answer: "A" },
  { q: "Destructor di PHP ditulis dengan?", a: ["__destruct()", "__end__", "destroy()", "unset()"], answer: "A" },
  { q: "Visibilitas private berarti?", a: ["Hanya bisa diakses dalam class itu sendiri", "Bisa diakses di semua class", "Bisa diakses dari luar class", "Hanya bisa diakses di subclass"], answer: "A" },
  { q: "Visibilitas public berarti?", a: ["Bisa diakses dari mana saja", "Hanya dalam class", "Hanya dalam file tertentu", "Hanya untuk admin"], answer: "A" },
  { q: "Visibilitas protected berarti?", a: ["Hanya class & subclass bisa akses", "Semua orang bisa akses", "Tidak bisa dipakai", "Hanya di database"], answer: "A" },
  { q: "Static method dipanggil dengan?", a: ["::", "->", ".", "#"], answer: "A" },
  { q: "Interface di PHP digunakan untuk?", a: ["Menentukan kontrak method", "Menyimpan data", "Menghubungkan database", "Membuat UI"], answer: "A" },
  { q: "Abstract class berarti?", a: ["Tidak bisa diinstansiasi langsung", "Hanya class biasa", "Harus final", "Class kosong"], answer: "A" },
  { q: "Keyword 'final' pada class berarti?", a: ["Tidak bisa diwarisi", "Hanya bisa satu objek", "Tidak bisa dipanggil", "Selalu static"], answer: "A" },
  { q: "Apa itu PDO di PHP?", a: ["PHP Data Objects", "Program Database Online", "Public Data Object", "Private Data Operation"], answer: "A" },
  { q: "Fungsi utama PDO adalah?", a: ["Abstraksi database", "Desain UI", "Pengelolaan file", "Menjalankan server"], answer: "A" },
  { q: "Kelebihan PDO dibanding MySQLi adalah?", a: ["Mendukung banyak database", "Lebih lambat", "Hanya untuk MySQL", "Tidak bisa prepared statement"], answer: "A" },
  { q: "Untuk membuat koneksi PDO digunakan?", a: ["new PDO()", "mysqli_connect()", "db.open()", "PDO::connect()"], answer: "A" },
  { q: "Prepared statement berguna untuk?", a: ["Mencegah SQL Injection", "Mempercepat server", "Menghapus database", "Mengatur session"], answer: "A" },
  { q: "Method untuk menyiapkan query di PDO adalah?", a: ["prepare()", "exec()", "query()", "bind()"], answer: "A" },
  { q: "Method untuk mengeksekusi query PDO adalah?", a: ["execute()", "run()", "start()", "open()"], answer: "A" },
  { q: "bindParam() digunakan untuk?", a: ["Mengikat variabel ke parameter query", "Menjalankan query langsung", "Menyimpan data ke array", "Menghapus data"], answer: "A" },
  { q: "fetch() digunakan untuk?", a: ["Mengambil 1 baris hasil query", "Menjalankan query", "Menghapus tabel", "Menyimpan file"], answer: "A" },
  { q: "fetchAll() digunakan untuk?", a: ["Mengambil semua hasil query", "Menjalankan query update", "Menyimpan semua data ke file", "Menghapus data"], answer: "A" },
  { q: "fetch(PDO::FETCH_ASSOC) menghasilkan?", a: ["Array asosiatif", "Array numerik", "Object JSON", "File CSV"], answer: "A" },
];


   let current = 0;
let showAnswer = false;

function showQuestion(index) {
  const qBox = document.getElementById("question");
  const optBox = document.getElementById("options");
  qBox.innerText = `(${index+1}) ${questions[index].q}`;
  optBox.innerHTML = "";

  questions[index].a.forEach((opt, i) => {
    const letter = String.fromCharCode(65+i);
    const correct = (letter === questions[index].answer && showAnswer);
    optBox.innerHTML += `<div class='option' style='${correct ? "color: #00FF00; font-weight:bold;" : ""}'>
      ${letter}. ${opt} ${correct ? "✅" : ""}
    </div>`;
  });
}

function nextQuestion() {
  if (current < questions.length - 1) {
    current++;
    showAnswer = false;
    resetBuzzer();   // reset setiap kali pindah soal
    showQuestion(current);
  }
}

function prevQuestion() {
  if (current > 0) {
    current--;
    showAnswer = false;
    resetBuzzer();   // reset setiap kali pindah soal
    showQuestion(current);
  }
}

// fungsi untuk reset buzzer via fetch
function resetBuzzer() {
  fetch("reset.php")
    .then(res => res.text())
    .then(msg => {
      console.log(msg); // debug di console
      document.getElementById("winner").innerText = "Belum ada yang menekan buzzer...";
    })
    .catch(err => console.error("Error reset: " + err));
}

function toggleAnswer() {
  showAnswer = !showAnswer;
  showQuestion(current);
}


    // Tampilkan soal pertama saat halaman dibuka
    showQuestion(current);

    // Update winner secara otomatis
    setInterval(() => {
      fetch("check.php")
        .then(res => res.text())
        .then(data => {
          document.getElementById("winner").innerText = data;
        });
    }, 2000);

    function resetGame() {
      fetch("reset.php")
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          document.getElementById("winner").innerText = "Belum ada yang menekan buzzer...";
        });
    }
  </script>
</body>
</html>
