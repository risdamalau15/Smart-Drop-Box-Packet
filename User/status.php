<?php
include '../includes/kon_baru.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$lastTenActivities = [];
$sqlLastTen = "SELECT * FROM activity_log ORDER BY id DESC LIMIT 10";
$resultLastTen = $conn->query($sqlLastTen);
if ($resultLastTen->num_rows > 0) {
    while ($row = $resultLastTen->fetch_assoc()) {
        $lastTenActivities[] = [
            'resiNumber' => $row['no_resi'],
            'customerName' => $row['nama_pengirim'],
            'customerContact' => $row['contact_pengirim'],
            'deliveryDate' => $row['tanggal_pengiriman'],
            'status' => $row['status'],
            'activity' => $row['activity'],
            'timestamp' => $row['timestamp']
        ];
    }
}

$sqlTotal = "SELECT COUNT(*) AS total FROM activity_log";
$resultTotal = $conn->query($sqlTotal);
$totalActivities = $resultTotal->fetch_assoc()['total'];

$conn->close();
?>

<div class="wrapper">
    <div class="main p-4">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-4">Smart Dropbox</h1>
                <p class="lead">Selamat datang di dashboard Smart Dropbox. Kelola riwayat aktivitas resi Anda.</p>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h2 class="card-title">Riwayat Pendaftaran</h2>
                    <p>Resi Terdaftar: <strong><?php echo $totalActivities; ?></strong></p>
                    <form class="d-flex mb-4" id="searchForm">
                        <input class="form-control me-2" type="search" name="search" placeholder="Cari Nomor Resi" aria-label="Search" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
                        <button class="btn btn-outline-success" type="submit">Cari</button>
                    </form>
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>No Resi</th>
                                <th>Nama Pengirim</th>
                                <th>Kontak</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Status</th>
                                <!-- <th>Aktivitas</th> -->
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php
                            // Menampilkan 10 data terakhir
                            foreach ($lastTenActivities as $activity) {
                                echo "<tr>";
                                echo "<td>" . (isset($activity['resiNumber']) ? $activity['resiNumber'] : '') . "</td>";
                                echo "<td>" . (isset($activity['customerName']) ? $activity['customerName'] : '') . "</td>";
                                echo "<td>" . (isset($activity['customerContact']) ? $activity['customerContact'] : '') . "</td>";
                                echo "<td>" . (isset($activity['deliveryDate']) ? $activity['deliveryDate'] : '') . "</td>";
                                echo "<td>" . (isset($activity['status']) ? $activity['status'] : '') . "</td>";
                                // echo "<td>" . (isset($activity['activity']) ? $activity['activity'] : '') . "</td>";
                                echo "<td>" . (isset($activity['timestamp']) ? $activity['timestamp'] : '') . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var searchResi = document.getElementsByName('search')[0].value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/get_data_search.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            var tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; // Clear existing table rows

            if (response.length > 0) {
                response.forEach(function(activity) {
                    var row = "<tr>";
                    row += "<td>" + activity.resiNumber + "</td>";
                    row += "<td>" + activity.customerName + "</td>";
                    row += "<td>" + activity.customerContact + "</td>";
                    row += "<td>" + activity.deliveryDate + "</td>";
                    row += "<td>" + activity.status + "</td>";
                    row += "<td>" + activity.activity + "</td>";
                    row += "<td>" + activity.timestamp + "</td>";
                    row += "</tr>";
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='7'>Aktivitas tidak ditemukan</td></tr>";
            }
        }
    };
    xhr.send('search=' + searchResi);
});
</script>
