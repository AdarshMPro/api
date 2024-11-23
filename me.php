<?php
// Allow CORSs
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Set content type for JSON
header('Content-Type: application/json');

// Path to the JSON file
$file_path = 'product.json';

// Check if the JSON file exists and has data
if (file_exists($file_path) && filesize($file_path) > 0) {
    // Read data from the file
    $data = file_get_contents($file_path);
    echo json_encode(json_decode($data), JSON_PRETTY_PRINT);
} else {
    // Initialize cURL
    $ch = curl_init();

    // Set the URL and options
    curl_setopt($ch, CURLOPT_URL, "https://www.meesho.com/api/v1/products/search");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'authority: www.meesho.com',
        'accept: application/json, text/plain, */*',
        'accept-language: en-US,en;q=0.9',
        'content-type: application/json',
    ]);

    // Set POST fields with limit set to 150
    $data = json_encode([
        "query" => "oversized t shirts for men",
        "type" => "text_search",
        "page" => 1,
        "offset" => 0,
        "limit" => 150,
        "cursor" => null,
        "isDevicePhone" => true,
        "isAutocorrectReverted" => false
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Execute cURL and fetch the response
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo "cURL error: " . curl_error($ch);
    } else {
        // Decode JSON response
        $decoded_response = json_decode($response, true);

        // Save the response to product.json
        file_put_contents($file_path, json_encode($decoded_response, JSON_PRETTY_PRINT));

        // Output the response
        echo json_encode($decoded_response, JSON_PRETTY_PRINT);
    }

    // Close cURL
    curl_close($ch);
}
