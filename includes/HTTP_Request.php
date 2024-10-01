<?php
include 'kon_baru.php';
require_once '../vendor/autoload.php';

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

$sid    = "ACb34d1f65e01f07fd9e5d7321f5928635";
$token  = "7f4691ae5612ed480b4842c9341fe5bc";
$twilio = new Client($sid, $token);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['barcode'])) {
        $barcode = trim($_POST['barcode']);

        $stmt = $conn->prepare("SELECT * FROM resi WHERE no_resi = ?");
        $stmt->bind_param("s", $barcode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $recipientNumber = $row['contact_penerima'];

            $stmtUpdate = $conn->prepare("UPDATE resi SET status = 'diterima' WHERE no_resi = ?");
            $stmtUpdate->bind_param("s", $barcode);
            $stmtUpdate->execute();
            $stmtUpdate->close();

            $messageContent = "Status paket dengan resi $barcode telah diubah menjadi diterima.";

            try {
                $message = $twilio->messages
                                  ->create("whatsapp:$recipientNumber", 
                                           [
                                               "from" => "whatsapp:+14155238886", 
                                               "body" => $messageContent
                                           ]
                                  );
                echo "Status updated to diterima for barcode: " . $barcode;
                echo "Pesan berhasil dikirim ke $recipientNumber! SID: " . $message->sid;
            } catch (Exception $e) {
                echo "Status updated to diterima for barcode: " . $barcode;
                echo "Pesan gagal dikirim ke $recipientNumber: " . $e->getMessage();
            }
        } else {
            echo "data tidak ada";
        }

        $stmt->close();
    } else {
        echo "No barcode provided";
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>
