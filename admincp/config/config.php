
<?php
$mysqli = new mysqli("localhost", "root", "", "web_nienluan2");

if ($mysqli->connect_errno) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}
?>