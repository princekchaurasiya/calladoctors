<?php

if (empty($_GET['namef1']) || empty($_GET['phonef1'])) {
    echo "No arguments Provided!";
    return false;
}

$name = htmlspecialchars(strip_tags($_GET['namef1']));
$email = htmlspecialchars(strip_tags($_GET['emailf1']));
$city = htmlspecialchars(strip_tags($_GET['cityf1']));
$message = htmlspecialchars(strip_tags($_GET['messagef1']));
$phone = "+91" . htmlspecialchars(strip_tags($_GET['phonef1']));

$formid = "HeaderForm";
$qs = htmlspecialchars(strip_tags($_GET['qsf1'] ?? ''));

$formq = "name=" . urlencode($name) . "&email=" . urlencode($email) ."&messaage=" . urlencode($message) . "&phone=" . urlencode($phone) . "&city=" . urlencode($city) . "&formid=" . urlencode($formid) . "&" . $qs;

$link = "https://hook.eu2.make.com/jqwgsccegnvg7dygbabyyyyh94optvfz?";
$flowq = $link . $formq;

function flowtrig($flowq) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $flowq);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);

    // Example headers, if required by the external service
    // $headers = [
    //     'Content-Type: application/x-www-form-urlencoded',
    //     'Authorization: Bearer YOUR_API_KEY'
    // ];
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if ($response === false) {
        error_log('cURL Error: ' . curl_error($ch));
        curl_close($ch);
        header('Location: ./form-submit-failed.php');
        exit();
    }

    curl_close($ch);

    // Check response content
    if (strpos($response, "Accepted") !== false) {
        header('Location: ./thank-you.php');
    } else {
        header('Location: ./form-submit-failed.php');
    }
    exit();
}

// Debug information
echo "Debug Information:<br>";
echo "Name: $name<br>";
echo "Email: $email<br>";
echo "Phone: $phone<br>";
echo "City: $city<br>";
echo "FormID: $formid<br>";
echo "Query String: $qs<br>";
echo "FlowQ: $flowq<br>";

flowtrig($flowq);

?>
