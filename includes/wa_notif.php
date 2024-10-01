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

$resi = '8994171101289'; 

$sql = "SELECT contact_penerima FROM resi WHERE no_resi = '$resi'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $recipientNumber = $row['contact_penerima'];

        $messageContent = "Anda memiliki paket terdaftar dengan resi: $resi. Silakan cek sistem untuk detail lebih lanjut.";

        try {
            $message = $twilio->messages
                              ->create("whatsapp:$recipientNumber", 
                                       [
                                           "from" => "whatsapp:+14155238886", 
                                           "body" => $messageContent
                                       ]
                              );
            echo "Pesan berhasil dikirim ke $recipientNumber! SID: " . $message->sid;
        } catch (Exception $e) {
            echo "Pesan gagal dikirim ke $recipientNumber: " . $e->getMessage();
        }
    }
} else {
    echo "Nomor resi tidak ditemukan.";
}

$conn->close();
?>