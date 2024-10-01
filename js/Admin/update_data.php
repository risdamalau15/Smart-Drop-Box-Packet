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
            'id' => $row['id'], 
            'resiNumber' => $row['no_resi'],
            'customerName' => $row['nama_penerima'],
            'customerContact' => $row['contact_penerima'],
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
                    <h2 class="card-title">Status Perjalanan Paket</h2>
                    <form class="d-flex mb-4" id="searchForm">
                        <input class="form-control me-2" type="search" name="search" placeholder="Cari Nomor Resi" aria-label="Search" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
                        <button class="btn btn-outline-success" type="submit">Cari</button>
                    </form>
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>No Resi</th>
                                <th>Nama Penerima</th>
                                <th>Kontak</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Status</th>
                                <th>Aktivitas</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php
                            foreach ($lastTenActivities as $activity) {
                                echo "<tr id='row_" . $activity['id'] . "'>";
                                echo "<td>" . (isset($activity['resiNumber']) ? $activity['resiNumber'] : '') . "</td>";
                                echo "<td>" . (isset($activity['customerName']) ? $activity['customerName'] : '') . "</td>";
                                echo "<td>" . (isset($activity['customerContact']) ? $activity['customerContact'] : '') . "</td>";
                                echo "<td>" . (isset($activity['deliveryDate']) ? $activity['deliveryDate'] : '') . "</td>";
                                echo "<td>" . (isset($activity['status']) ? $activity['status'] : '') . "</td>";
                                echo "<td>" . (isset($activity['activity']) ? $activity['activity'] : '') . "</td>";
                                echo "<td>" . (isset($activity['timestamp']) ? $activity['timestamp'] : '') . "</td>";
                                echo "<td>";
                                echo "<button class='btn btn-sm btn-primary' onclick='editActivity(" . json_encode($activity) . ")'>Edit</button> ";
                                echo "<button class='btn btn-sm btn-danger' onclick='deleteActivity(" . $activity['id'] . ")'>Delete</button>";
                                echo "</td>";
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

<!-- Edit data -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label for="editActivity" class="form-label">Activity</label>
                        <select class="form-select" name="activity" id="editActivity">
                            <option value="Inserted">Inserted</option>
                            <option value="Updated">Updated</option>
                            <option value="Deleted">Deleted</option>
                            <option value="Status Changed">Status Changed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-select" name="status" id="editStatus">
                            <option value="terdaftar">Terdaftar</option>
                            <option value="dalam_perjalanan">Dalam Perjalanan</option>
                            <option value="diterima">Diterima</option>
                            <option value="diambil">Diambil</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
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
            tableBody.innerHTML = ''; 

            if (response.length > 0) {
                response.forEach(function(activity) {
                    var row = "<tr id='row_" + activity.id + "'>";
                    row += "<td>" + activity.resiNumber + "</td>";
                    row += "<td>" + activity.customerName + "</td>";
                    row += "<td>" + activity.customerContact + "</td>";
                    row += "<td>" + activity.deliveryDate + "</td>";
                    row += "<td>" + activity.status + "</td>";
                    row += "<td>" + activity.activity + "</td>";
                    row += "<td>" + activity.timestamp + "</td>";
                    row += "<td>";
                    row += "<button class='btn btn-sm btn-primary' onclick='editActivity(" + JSON.stringify(activity) + ")'>Edit</button> ";
                    row += "<button class='btn btn-sm btn-danger' onclick='deleteActivity(" + activity.id + ")'>Delete</button>";
                    row += "</td>";
                    row += "</tr>";
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='8'>No activities found</td></tr>";
            }
        }
    };
    xhr.send('search=' + searchResi);
});

function editActivity(activity) {
    document.getElementById('editId').value = activity.id;
    document.getElementById('editActivity').value = activity.activity;
    document.getElementById('editStatus').value = activity.status;
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var id = document.getElementById('editId').value;
    var activity = document.getElementById('editActivity').value;
    var status = document.getElementById('editStatus').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../includes/update_resi.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Activity updated successfully.');
            location.reload(); 
        }
    };
    xhr.send('id=' + id + '&activity=' + activity + '&status=' + status);
});

function deleteActivity(id) {
    if (confirm('Are you sure you want to delete this activity?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../includes/delete_resi.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log('Activity deleted successfully.');
                var rowToRemove = document.getElementById('row_' + id);
                if (rowToRemove) {
                    rowToRemove.parentNode.removeChild(rowToRemove);
                }
            }
        };
        xhr.send('id=' + id);
    }
}
</script>