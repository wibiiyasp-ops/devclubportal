<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "db_devclub");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal!"]);
    exit();
}
$action = $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'daftar') {
    $input = json_decode(file_get_contents('php://input'), true);
    $nis   = trim($input['nis'] ?? '');
    $nama  = trim($input['nama'] ?? '');
    $divisi= trim($input['divisi'] ?? '');
    $email = trim($input['email'] ?? '');
    if (empty($nis) || empty($nama) || empty($divisi) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "Semua field formulir wajib diisi!"]);
        exit();
    }
    // Safe Database Query using Prepared Statement
    $stmt = $conn->prepare("INSERT INTO pendaftaran (nis, nama, divisi, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nis, $nama, $divisi, $email);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Selamat! Data pendaftaran berhasil disimpan."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan! NIS mungkin sudah terdaftar."]);
    }
    $stmt->close();
}

$conn->close();
?>