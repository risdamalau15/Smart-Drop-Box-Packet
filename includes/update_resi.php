<?php
include './kon_baru.php';
require_once '../vendor/autoload.php';

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

if (isset($_POST['id']) && isset($_POST['activity']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $activity = $_POST['activity'];
    $status = $_POST['status'];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update activity_log table
    $sql = "UPDATE activity_log SET timestamp = CURRENT_TIMESTAMP, activity = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $activity, $status, $id);

    if ($stmt->execute()) {
        echo "Activity updated successfully.";

        // Get the no_resi associated with this activity
        $sqlGetResi = "SELECT no_resi FROM activity_log WHERE id = ?";
        $stmtGetResi = $conn->prepare($sqlGetResi);
        $stmtGetResi->bind_param("i", $id);
        $stmtGetResi->execute();
        $stmtGetResi->bind_result($resi);
        $stmtGetResi->fetch();
        $stmtGetResi->close();

        // Get the recipient contact number and name
        $sqlGetContact = "SELECT contact_penerima, nama_penerima FROM resi WHERE no_resi = ?";
        $stmtGetContact = $conn->prepare($sqlGetContact);
        $stmtGetContact->bind_param("s", $resi);
        $stmtGetContact->execute();
        $stmtGetContact->bind_result($recipientNumber, $recipientName);
        $stmtGetContact->fetch();
        $stmtGetContact->close();

        // Twilio credentials
        $sid = "ACb34d1f65e01f07fd9e5d7321f5928635";
        $token = "7f4691ae5612ed480b4842c9341fe5bc";
        $twilio = new Client($sid, $token);

        $messageContent = "Status paket dengan resi $resi atas nama $recipientName telah diubah menjadi $status.";

        try {
            $message = $twilio->messages
                              ->create("whatsapp:$recipientNumber", 
                                       [
                                           "from" => "whatsapp:+14155238886", 
                                           "body" => $messageContent
                                       ]
                              );
            echo "Pesan berhasil dikirim ke $recipientNumber! SID: " . $message->sid;
        } catch (TwilioException $e) {
            echo "Pesan gagal dikirim ke $recipientNumber: " . $e->getMessage();
        }

    } else {
        echo "Error updating activity: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid input.";
}
?>
