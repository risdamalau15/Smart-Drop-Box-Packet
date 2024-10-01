<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah No Resi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="main p-4">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-4">Smart Dropbox</h1>
                <p class="lead">Selamat datang di dashboard Smart Dropbox. Kelola resi Anda dengan mudah dan cepat.</p>
                <p class="lead">Bagian kontak isi dengan format <em><strong>+62</strong></em>.</p>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h2 class="card-title">Tambah No Resi</h2>
                    <form action="../includes/send_data.php" method="post">
                        <div class="mb-3">
                            <label for="resiNumber" class="form-label">Nomor Resi</label>
                            <input type="text" class="form-control" id="resiNumber" name="resiNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="senderName" class="form-label">Nama Pengirim</label>
                            <input type="text" class="form-control" id="senderName" name="senderName" required>
                        </div>
                        <div class="mb-3">
                            <label for="senderContact" class="form-label">Kontak Pengirim</label>
                            <input type="text" class="form-control" id="senderContact" name="senderContact" required>
                        </div>
                        <div class="mb-3">
                            <label for="recipientName" class="form-label">Nama Penerima</label>
                            <input type="text" class="form-control" id="recipientName" name="recipientName" required>
                        </div>
                        <div class="mb-3">
                            <label for="recipientContact" class="form-label">Kontak Penerima</label>
                            <input type="text" class="form-control" id="recipientContact" name="recipientContact" required>
                        </div>
                        <div class="mb-3">
                            <label for="deliveryDate" class="form-label">Tanggal Pengiriman</label>
                            <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Resi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
