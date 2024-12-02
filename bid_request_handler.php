<?php
// bid_request_handler.php

require_once 'campaigns.php';
require_once 'helpers.php';

 // Get raw bid request JSON
$request = file_get_contents('php://input');

if (!$request) {
    sendErrorResponse('Invalid or missing request payload.');
}

try {
    $bidRequest = json_decode($request, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendErrorResponse('Invalid JSON format.');
    }

    // Validate bid request
    validateBidRequest($bidRequest);

    // Select the best campaign
    $selectedCampaign = selectBestCampaign($bidRequest, $campaigns);

    if (!$selectedCampaign) {
        sendErrorResponse('No suitable campaign found.');
    }

    // Generate bid response
    $bidResponse = generateBidResponse($bidRequest, $selectedCampaign);

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($bidResponse, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    sendErrorResponse($e->getMessage());
}
?>
