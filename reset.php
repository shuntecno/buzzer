<?php
include "db.php";
$conn->query("TRUNCATE TABLE buzzer");
echo "Buzzer sudah direset!";
