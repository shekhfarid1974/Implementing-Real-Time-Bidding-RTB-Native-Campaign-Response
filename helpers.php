<?php
// helpers.php

function validateBidRequest($bidRequest)
{
    if (empty($bidRequest['id']) || empty($bidRequest['imp']) || empty($bidRequest['device'])) {
        throw new Exception('Missing required bid request fields.');
    }

    foreach ($bidRequest['imp'] as $imp) {
        if (empty($imp['bidfloor']) || empty($imp['native'])) {
            throw new Exception('Invalid impression data in bid request.');
        }
    }
}

function selectBestCampaign($bidRequest, $campaigns)
{
    $device = $bidRequest['device'];
    $geo = $device['geo'];
    $bidFloor = $bidRequest['imp'][0]['bidfloor'];

    $eligibleCampaigns = array_filter($campaigns, function ($campaign) use ($geo, $device, $bidFloor) {
        return $campaign['country'] === $geo['country'] &&
               ($campaign['device_make'] === $device['make'] || $campaign['device_make'] === 'No Filter') &&
               $campaign['price'] >= $bidFloor;
    });

    usort($eligibleCampaigns, function ($a, $b) {
        return $b['price'] <=> $a['price'];
    });

    return $eligibleCampaigns[0] ?? null;
}

function generateBidResponse($bidRequest, $campaign)
{
    $bidResponse = [
        'id' => $bidRequest['id'],
        'bidid' => uniqid(),
        'seatbid' => [
            [
                'bid' => [
                    [
                        'price' => $campaign['price'],
                        'adm' => json_encode([
                            'native' => [
                                'assets' => [
                                    [
                                        'id' => 101,
                                        'title' => ['text' => $campaign['name']],
                                        'required' => 1,
                                    ],
                                    [
                                        'id' => 102,
                                        'data' => ['value' => $campaign['advertiser']],
                                        'required' => 1,
                                    ],
                                ],
                                'link' => [
                                    'url' => $campaign['landing_page'],
                                ],
                            ],
                        ]),
                        'impid' => $bidRequest['imp'][0]['id'],
                        'crid' => $campaign['creative_id'],
                        'bundle' => $campaign['bidtype'],
                    ],
                ],
                'seat' => '1',
                'group' => 0,
            ],
        ],
    ];

    return $bidResponse;
}

function sendErrorResponse($message)
{
    header('Content-Type: application/json', true, 400);
    echo json_encode(['error' => $message]);
    exit;
}
?>
