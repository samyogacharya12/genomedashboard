<?php


$uploadFile = '/home/samyog/samyog/genome dashboard/wri.dat';
$savePath = '/home/samyog/samyog/genome dashboard/output.bw';
$apiUrl = "http://127.0.0.1:5000/convert";


// === Configuration ===
function convertFileThroughApi(string $uploadFile, string $savePath): void
{
    global $http_response_header,$apiUrl;

    if (!file_exists($uploadFile)) {
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
    } else {
    }
}

convertFileThroughApi($uploadFile, $savePath);
?>
