<div class="wrapper">
    <div class="main p-4">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-4">Smart Dropbox</h1>
                <p class="lead">Selamat datang di dashboard Smart Dropbox. Kelola resi Anda dengan mudah dan cepat.</p>
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
                                <th>Kontak Penerima</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data resi akan ditampilkan di sini -->
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
    xhr.open('POST', '../includes/get_new_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            var tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; 

            if (response.length > 0) {
                response.forEach(function(waybill) {
                    var row = "<tr>";
                    row += "<td>" + waybill.resiNumber + "</td>";
                    row += "<td>" + waybill.customerName + "</td>";
                    row += "<td>" + waybill.customerContact + "</td>";
                    row += "<td>" + waybill.deliveryDate + "</td>";
                    row += "<td>" + waybill.status + "</td>";
                    row += "<td>" + waybill.timestamp + "</td>";
                    row += "</tr>";
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='6'>Nomor resi tidak ditemukan</td></tr>";
            }
        }
    };
    xhr.send('search=' + searchResi);
});
</script>
