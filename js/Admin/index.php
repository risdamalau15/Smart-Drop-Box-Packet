<?php
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../Login/index.php");
    exit;
}

include '../includes/kon_baru.php';

$total_paket_query = "SELECT COUNT(*) as count FROM resi";
$total_paket_result = mysqli_query($conn, $total_paket_query);
$total_paket = mysqli_fetch_assoc($total_paket_result)['count'];

$dalam_proses_query = "SELECT COUNT(*) as count FROM activity_log WHERE status = 'dalam_perjalanan'";
$dalam_proses_result = mysqli_query($conn, $dalam_proses_query);
$dalam_proses = mysqli_fetch_assoc($dalam_proses_result)['count'];

$selesai_query = "SELECT COUNT(*) as count FROM activity_log WHERE status = 'diterima' or status = 'diambil'";
$selesai_result = mysqli_query($conn, $selesai_query);
$selesai = mysqli_fetch_assoc($selesai_result)['count'];

$terbaru_query = "SELECT no_resi FROM resi ORDER BY created_at DESC LIMIT 1";
$terbaru_result = mysqli_query($conn, $terbaru_query);
$terbaru = mysqli_fetch_assoc($terbaru_result)['no_resi'];

$activities_query = "
    SELECT 
        activity_log.activity, 
        activity_log.status,
        activity_log.timestamp,
        resi.no_resi AS resi_number, 
        resi.nama_penerima AS customer_name,
        resi.contact_penerima AS customer_contact
    FROM 
        activity_log 
    LEFT JOIN 
        resi ON activity_log.resi_id = resi.id 
    ORDER BY 
        activity_log.timestamp DESC 
    LIMIT 
        5
";
$activities_result = mysqli_query($conn, $activities_query);

$packages_query = "
    SELECT 
        no_resi, 
        nama_pengirim AS sender_name, 
        contact_pengirim AS sender_contact, 
        tanggal_pengiriman AS delivery_date, 
        status
    FROM 
        resi 
    ORDER BY 
        created_at DESC 
    LIMIT 
        5
";
$packages_result = mysqli_query($conn, $packages_query);

$users_query = "
    SELECT 
        nama_penerima AS username, 
        contact_penerima AS email, 
        COUNT(id) as package_count 
    FROM 
        resi 
    GROUP BY 
        nama_pengirim
";
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-dashboard">
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
        </div>
        <div class="dashboard-content">
            <div class="summary-cards">
                <div class="card">
                    <h2>Paket Terdaftar</h2>
                    <p><?php echo $total_paket; ?></p>
                </div>
                <div class="card">
                    <h2>Paket Dalam Proses</h2>
                    <p><?php echo $dalam_proses; ?></p>
                </div>
                <div class="card">
                    <h2>Paket Selesai</h2>
                    <p><?php echo $selesai; ?></p>
                </div>
                <div class="card">
                    <h2>Paket Terbaru</h2>
                    <p>#<?php echo $terbaru; ?></p>
                </div>
            </div>
            <div class="recent-activities">
                <h2>Aktivitas Terbaru</h2>
                <ul>
                    <?php while ($activity = mysqli_fetch_assoc($activities_result)) { ?>
                        <li>
                            <?php echo htmlspecialchars($activity['activity']); ?>
                            (<?php echo htmlspecialchars($activity['status']); ?>),
                            Resi: <?php echo htmlspecialchars($activity['resi_number']); ?>,
                            Pemilik: <?php echo htmlspecialchars($activity['customer_name']); ?>,
                            Waktu: <?php echo htmlspecialchars($activity['timestamp']); ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="package-management">
                <h2>Pengelolaan Paket</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nomor Resi</th>
                            <th>Nama Pengirim</th>
                            <th>Kontak Pengirim</th>
                            <th>Tanggal Pengiriman</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($package = mysqli_fetch_assoc($packages_result)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($package['no_resi']); ?></td>
                                <td><?php echo htmlspecialchars($package['sender_name']); ?></td>
                                <td><?php echo htmlspecialchars($package['sender_contact']); ?></td>
                                <td><?php echo htmlspecialchars($package['delivery_date']); ?></td>
                                <td><?php echo htmlspecialchars($package['status']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="user-management">
                <h2>Pengelolaan Pemilik Paket</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Penerima</th>
                            <th>Kontak Penerima</th>
                            <th>Jumlah Paket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users_result)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['package_count']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="reports-analytics">
                <h2>Pelaporan dan Analitik</h2>
                <!-- Grafik atau laporan analitik lainnya -->
            </div>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
