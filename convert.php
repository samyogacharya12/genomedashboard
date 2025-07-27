<?php


$uploadFile = 'shshs/hshs.dat';
$savePath = '/sys';
$apiUrl = "https://shshs/convert";


// === Configuration ===
function convertFileThroughApi(string $uploadFile, string $savePath): bool
{
    global $http_response_header,$apiUrl;

    if (!file_exists($uploadFile)) {
        echo "Error: File '$uploadFile' does not exist.\n";
        return false;
    }

    $boundary = uniqid();
    $delimiter = '-------------' . $boundary;

    $fileContents = file_get_contents($uploadFile);
    $fileName = basename($uploadFile);

    $postData = "--$delimiter\r\n";
    $postData .= "Content-Disposition: form-data; name=\"file1\"; filename=\"$fileName\"\r\n";
    $postData .= "Content-Type: application/octet-stream\r\n\r\n";
    $postData .= $fileContents . "\r\n";
    $postData .= "--$delimiter--\r\n";

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: multipart/form-data; boundary=$delimiter\r\n" .
                        "Content-Length: " . strlen($postData) . "\r\n",
            'content' => $postData,
            'ignore_errors' => true,
        ]
    ]);

    $response = file_get_contents($apiUrl, false, $context);

    if ($http_response_header && strpos($http_response_header[0], "200") !== false) {
        file_put_contents($savePath, $response);
        echo "✅ File saved to: $savePath\n";
        return true;
    } else {
        echo "❌ Request failed:\n";
        print_r($http_response_header);
        echo "\nResponse:\n$response\n";
        return false;
    }
}

convertFileThroughApi($uploadFile, $savePath);
?>
