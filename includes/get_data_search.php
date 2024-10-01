<?php
include 'kon_baru.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchResi = $_POST['search'];

    $sql = "SELECT * FROM activity_log WHERE no_resi LIKE '%$searchResi%'";
    $result = $conn->query($sql);

    $activities = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $activities[] = [
                'resiNumber' => $row['no_resi'],
                'customerName' => $row['nama_pengirim'],
                'customerContact' => $row['contact_pengirim'],
                'deliveryDate' => $row['tanggal_pengiriman'],
                'status' => $row['status'],
                'activity' => $row['activity'],
                'timestamp' => $row['timestamp']
            ];
        }
        echo json_encode($activities);
    } else {
        echo json_encode([]);
    }
}

$conn->close();
?>
