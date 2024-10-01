<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Dropbox System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
            text-align: center;
        }
        .btn {
            margin: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    include "../includes/kon_baru.php";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $resiNumber = $_POST['resiNumber'];
    $senderName = $_POST['senderName'];
    $senderContact = $_POST['senderContact'];
    $recipientName = $_POST['recipientName'];
    $recipientContact = $_POST['recipientContact'];
    $deliveryDate = $_POST['deliveryDate'];

    $conn->begin_transaction();

    try {
        $sql = "INSERT INTO resi (no_resi, nama_pengirim, contact_pengirim, nama_penerima, contact_penerima, tanggal_pengiriman, status) 
            VALUES ('$resiNumber', '$senderName', '$senderContact', '$recipientName', '$recipientContact', '$deliveryDate', 'terdaftar')";
        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;

            $activity_sql = "INSERT INTO activity_log (resi_id, activity, no_resi, nama_pengirim, contact_pengirim, nama_penerima, contact_penerima, tanggal_pengiriman, status) 
                            VALUES ($last_id, 'Inserted', '$resiNumber', '$senderName', '$senderContact', '$recipientName', '$recipientContact', '$deliveryDate', 'terdaftar')";
            if ($conn->query($activity_sql) === TRUE) {
                $conn->commit();
                echo "<h4 class='text-success'>New record created successfully</h4>";
                echo "<p>Jika ingin menerima notifikasi, silahkan daftar pada WhatsApp melalui tombol yang tersedia.</p>";
                echo "<p>Kemudian ketik <strong><em>join movement-necessary</em></strong>.</p>";
                echo '<a href="../User/index.php?page=dashboard" class="btn btn-primary">Kembali ke Halaman Utama</a>';
                echo '<a href="https://wa.me/+14155238886?text=join%20movement-necessary%20" class="btn btn-success">Kirim Notifikasi WhatsApp</a>';
            } else {
                throw new Exception("Error: " . $activity_sql . "<br>" . $conn->error);
            }
        } else {
            throw new Exception("Error: " . $sql . "<br>" . $conn->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "<h4 class='text-danger'>".$e->getMessage()."</h4>";
    }

    $conn->close();
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
