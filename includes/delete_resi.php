<?php
include '../includes/kon_baru.php';
require_once '../vendor/autoload.php';

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->begin_transaction();

    try {
        // Get the resi number, recipient name and contact before deletion
        $sqlGetResi = "SELECT resi.no_resi, resi.nama_penerima, resi.contact_penerima FROM resi 
                       INNER JOIN activity_log ON resi.no_resi = activity_log.no_resi 
                       WHERE activity_log.id = ?";
        $stmtGetResi = $conn->prepare($sqlGetResi);
        $stmtGetResi->bind_param("i", $id);
        $stmtGetResi->execute();
        $stmtGetResi->bind_result($resi, $recipientName, $recipientContact);
        $stmtGetResi->fetch();
        $stmtGetResi->close();

        // Delete from activity_log table
        $sqlActivityLog = "DELETE FROM activity_log WHERE id = ?";
        $stmtActivityLog = $conn->prepare($sqlActivityLog);
        $stmtActivityLog->bind_param("i", $id);
        $stmtActivityLog->execute();
        $stmtActivityLog->close();

        // Delete from resi table
        $sqlResi = "DELETE FROM resi WHERE no_resi = ?";
        $stmtResi = $conn->prepare($sqlResi);
        $stmtResi->bind_param("s", $resi);
        $stmtResi->execute();
        $stmtResi->close();

        $conn->commit();

        echo "Activity and Resi deleted successfully.";

        // Twilio credentials
        $sid = "ACb34d1f65e01f07fd9e5d7321f5928635";
        $token = "7f4691ae5612ed480b4842c9341fe5bc";
        $twilio = new Client($sid, $token);

        $messageContent = "Paket anda dengan resi $resi atas nama $recipientName telah dihapus dari daftar.";

        try {
            $message = $twilio->messages
                              ->create("whatsapp:$recipientContact", 
                                       [
                                           "from" => "whatsapp:+14155238886", 
                                           "body" => $messageContent
                                       ]
                              );
            echo "Pesan berhasil dikirim ke $recipientContact! SID: " . $message->sid;
        } catch (TwilioException $e) {
            echo "Pesan gagal dikirim ke $recipientContact: " . $e->getMessage();
        }

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error deleting activity and resi: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "No id provided.";
}
?>
