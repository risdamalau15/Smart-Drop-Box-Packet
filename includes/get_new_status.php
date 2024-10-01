<?php
include 'kon_baru.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchResi = $_POST['search'];

    $sql = "SELECT r.no_resi, r.nama_penerima, r.contact_penerima, r.tanggal_pengiriman,
    CASE
        WHEN a.activity = 'Inserted' THEN 'terdaftar'
        WHEN a.activity = 'Updated' THEN 'dalam perjalanan'
        WHEN a.activity = 'Status Changed' THEN 'sudah sampai'
        ELSE ''
    END AS status,
    a.timestamp
    FROM activity_log r
    LEFT JOIN (
    SELECT resi_id, activity, timestamp
    FROM activity_log
    WHERE (resi_id, timestamp) IN (
        SELECT resi_id, MAX(timestamp)
        FROM activity_log
        GROUP BY resi_id
    )   
    ) a ON r.id = a.resi_id
    WHERE r.no_resi = '$searchResi'";

    
    $result = $conn->query($sql);

    $waybills = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $waybills[] = [
                'resiNumber' => $row['no_resi'],
                'customerName' => $row['nama_penerima'],
                'customerContact' => $row['contact_penerima'],
                'deliveryDate' => $row['tanggal_pengiriman'],
                'status' => $row['status'],
                'timestamp' => $row['timestamp']
            ];
        }
        echo json_encode($waybills);
    } else {
        echo json_encode([]);
    }
}

$conn->close();
?>