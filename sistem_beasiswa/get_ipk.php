<?php
require_once 'includes/functions.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Simulasi mendapatkan IPK dari sistem akademik
$ipk = getIPK();

// Return JSON response
echo json_encode([
    'ipk' => $ipk,
    'status' => 'success'
]);
?>