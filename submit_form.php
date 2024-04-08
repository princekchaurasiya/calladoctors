<?php

function submitToGoogleForm($formData) {
    $googleFormsURL = "https://docs.google.com/forms/d/e/1FAIpQLSeCHqDyUpJVupeiMq2-KIpDhuICa7cYDhysykSO6ZGvjo_AEg/formResponse";
    $googleFormsURL .= '?' . http_build_query($formData);

    $ch = curl_init($googleFormsURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if ($response === false) {
        // Handle cURL error
        $error = "cURL Error: " . curl_error($ch);
        logError($error);
        // You might want to throw an exception or handle the error appropriately
        exit();
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode;
}

function logError($message) {
    $logFilePath = 'error_log.txt';
    $logMessage = "[" . date("Y-m-d H:i:s") . "] " . $message . PHP_EOL;
    file_put_contents($logFilePath, $logMessage, FILE_APPEND);
}

echo "Debug Output:<pre>";
print_r($_GET);
echo "</pre>";

// Update the keys to match the actual keys in the $_GET array
if (empty($_GET['entry_1550794543']) || empty($_GET['entry_1137748630']) || empty($_GET['entry_1483314809']) ) {
    echo "No arguments provided!";
    return false;
}

$name = rawurldecode($_GET['entry_1550794543']);
$phone = rawurldecode($_GET['entry_1137748630']); // Use rawurldecode here
$city = rawurldecode($_GET['entry_1483314809']);


$formData = [
    "entry.1550794543" => $name,
    "entry.1137748630" => $phone,
    "entry.1483314809" => $city,
];

try {
    $httpCode = submitToGoogleForm($formData);

    if ($httpCode == 200) {
        // Successful submission
        header('Location: ./thank-you.php'); // Update with your actual domain
        exit();
    } else {
        // Submission failed
        header('Location: ./form-submit-failed.php'); // Update with your actual domain
        exit();
    }
} catch (Exception $e) {
    // Log the exception
    logError("Exception: " . $e->getMessage());
    // Handle the exception or redirect to an error page
    header('Location: ./form-submit-failed.php'); // Update with your actual domain
    exit();
}
?>
