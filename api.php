<?php
// Simple REST proxy that accepts the described payload, converts it, posts to remote API,
// and returns the remote API response.

header('Content-Type: application/json');

// Define allowed domains for Codespaces environment
$allowedOrigins = [
    'https://*.github.dev',
    'https://*.app.github.dev',
    'http://localhost:3000',
    'http://127.0.0.1:3000',
    'http://localhost:8000',
    'http://127.0.0.1:8000',
    'http://localhost:8080',
    'http://127.0.0.1:8080',
    'https://*.preview.app.github.dev'
];

// Get the request origin
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Check if the origin is allowed
$isAllowed = false;
foreach ($allowedOrigins as $allowedOrigin) {
    if (strpos($allowedOrigin, '*') !== false) {
        // Handle wildcard domains
        $pattern = '/^' . str_replace('\*', '.*', preg_quote($allowedOrigin, '/')) . '$/';
        if (preg_match($pattern, $origin)) {
            $isAllowed = true;
            break;
        }
    } else {
        // Exact match
        if ($origin === $allowedOrigin) {
            $isAllowed = true;
            break;
        }
    }
}

if ($isAllowed && $origin) {
    header('Access-Control-Allow-Origin: ' . $origin);
} else {
    // Default to localhost for same-origin requests
    header('Access-Control-Allow-Origin: http://localhost:8000');
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 86400'); // Cache preflight for 24 hours

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
    exit;
}

// Get raw input
$raw = file_get_contents('php://input');
if (!$raw) {
    http_response_code(400);
    echo json_encode(['error' => 'Empty request body']);
    exit;
}

// Parse JSON input
$input = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON: ' . json_last_error_msg()]);
    exit;
}

// Required keys in the incoming payload
$required = ['Unit Name', 'Arrival', 'Departure', 'Occupants', 'Ages'];
foreach ($required as $k) {
    if (!array_key_exists($k, $input)) {
        http_response_code(400);
        echo json_encode(['error' => "Missing field: $k"]);
        exit;
    }
}

// Map Unit Name to Unit Type ID for testing as requested
// Provided test IDs
$testIds = [-2147483637, -2147483456];

// You can change this mapping to reflect your real unit names.
$unitMap = [
    'Unit1' => $testIds[0],
    'Unit2' => $testIds[1],
    // friendly fallbacks
    'Unit A' => $testIds[0],
    'Unit B' => $testIds[1],
    'Unit One' => $testIds[0],
    'Unit Two' => $testIds[1],
];

$unitName = trim($input['Unit Name']);
$unitTypeId = null;
if (isset($unitMap[$unitName])) {
    $unitTypeId = $unitMap[$unitName];
} else {
    // fallback strategy: if contains '1' use first id, if '2' use second, else first id
    if (preg_match('/\b1\b/', $unitName) || str_contains($unitName, '1')) {
        $unitTypeId = $testIds[0];
    } elseif (preg_match('/\b2\b/', $unitName) || str_contains($unitName, '2')) {
        $unitTypeId = $testIds[1];
    } else {
        $unitTypeId = $testIds[0];
    }
}

// Convert dates from dd/mm/yyyy to yyyy-mm-dd
function convertDate($d) {
    $dt = DateTime::createFromFormat('d/m/Y', $d);
    if ($dt === false) return false;
    return $dt->format('Y-m-d');
}

$arrival = convertDate($input['Arrival']);
$departure = convertDate($input['Departure']);
if ($arrival === false || $departure === false) {
    http_response_code(400);
    echo json_encode(['error' => 'Dates must be in dd/mm/yyyy format']);
    exit;
}

// Validate that departure is after arrival
if (strtotime($departure) <= strtotime($arrival)) {
    http_response_code(400);
    echo json_encode(['error' => 'Departure date must be after arrival date']);
    exit;
}

// Build Guests array from Ages
$ages = $input['Ages'];
if (!is_array($ages)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ages must be an array of integers']);
    exit;
}

$guests = [];
foreach ($ages as $age) {
    $ageInt = (int)$age;
    // Validate age is reasonable
    if ($ageInt < 0 || $ageInt > 120) {
        http_response_code(400);
        echo json_encode(['error' => 'Age must be between 0 and 120']);
        exit;
    }
    
    // Define adult threshold: 18 and above -> Adult; else Child
    $group = ($ageInt >= 18) ? 'Adult' : 'Child';
    $guests[] = ['Age Group' => $group];
}

// Validate number of guests matches occupants count
if (count($guests) !== (int)$input['Occupants']) {
    http_response_code(400);
    echo json_encode(['error' => 'Number of ages provided does not match occupants count']);
    exit;
}

// Construct payload for remote API
$remotePayload = [
    'Unit Type ID' => (int)$unitTypeId,
    'Arrival' => $arrival,
    'Departure' => $departure,
    'Guests' => $guests,
];

$remoteUrl = 'https://dev.gondwana-collection.com/Web-Store/Rates/Rates.php';

// POST to remote API using cURL
$ch = curl_init($remoteUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'User-Agent: Gondwana-Rates-API/1.0'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($remotePayload));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

$responseBody = curl_exec($ch);
$curlErr = curl_error($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($responseBody === false) {
    http_response_code(502);
    echo json_encode([
        'error' => 'Failed to contact remote API', 
        'curl_error' => $curlErr,
        'remote_url' => $remoteUrl
    ]);
    exit;
}

// Try to decode remote response
$decodedRemote = json_decode($responseBody, true);

// Return a structured response to the frontend
$output = [
    'success' => $httpStatus >= 200 && $httpStatus < 300,
    'requested' => $input,
    'converted' => $remotePayload,
    'remote' => [
        'status' => $httpStatus,
        'body_raw' => $responseBody,
        'body_json' => $decodedRemote,
    ],
    'metadata' => [
        'unit_name_mapped' => $unitName,
        'unit_type_id_used' => $unitTypeId,
        'guests_count' => count($guests)
    ]
];

// Set appropriate HTTP status code based on remote response
http_response_code($httpStatus >= 400 ? 502 : 200);

// Return the response
echo json_encode($output, JSON_PRETTY_PRINT);

// Functions exposed for testing (add at the end of the file)
function convertDate($d) {
    $dt = DateTime::createFromFormat('d/m/Y', $d);
    if ($dt === false) return false;
    return $dt->format('Y-m-d');
}

function validateAge($age) {
    $ageInt = (int)$age;
    return $ageInt >= 0 && $ageInt <= 120;
}

function getAgeGroup($age) {
    return ($age >= 18) ? 'Adult' : 'Child';
}
?>
