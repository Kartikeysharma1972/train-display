<?php
header('Content-Type: application/json');

if(isset($_POST['getData'])) {
    $authToken = "dce6e4e056319e36dac78a98842e5432";
    
    $url = "https://enquiry.indianrail.gov.in/ntessrvc/LiveStation";
    
    $postData = json_encode([
        "station" => "JP",
        "nextMins" => 360
    ]);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'authToken: ' . $authToken
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if(curl_errno($ch)) {
        echo json_encode([
            'error' => 'Connection Error: ' . curl_error($ch)
        ]);
        curl_close($ch);
        exit;
    }
    
    curl_close($ch);
    
    if($httpCode != 200) {
        echo json_encode([
            'error' => 'API Error: HTTP ' . $httpCode
        ]);
        exit;
    }
    
    $apiData = json_decode($response, true);
    
    if(!$apiData || !isset($apiData['restServiceMessage'])) {
        echo json_encode([
            'error' => 'Invalid API Response'
        ]);
        exit;
    }
    
    if($apiData['restServiceMessage']['serviceDataFoundFlag'] !== 'Y') {
        echo json_encode([
            'error' => 'No Data Available'
        ]);
        exit;
    }
    
    $trainList = isset($apiData['vTrainList']) ? $apiData['vTrainList'] : [];
    
    $englishData = [];
    $hindiData = [];
    
    foreach($trainList as $train) {
        $englishTrain = [
            'trainNo' => $train['trainNo'] ?? '',
            'trainName' => ($train['trainName'] ?? '') . ' (' . ($train['srcName'] ?? '') . ' - ' . ($train['dstnName'] ?? '') . ')',
            'expectedTime' => $train['expectedTime'] ?? '',
            'ADFlag' => $train['ADFlag'] ?? '',
            'platformNo' => $train['platformNo'] ?? '-'
        ];
        
        $trainNameHindi = isset($train['trainNameHindi']) ? html_entity_decode($train['trainNameHindi'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
        $srcNameHindi = isset($train['srcNameHindi']) ? html_entity_decode($train['srcNameHindi'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
        $dstnNameHindi = isset($train['dstnNameHindi']) ? html_entity_decode($train['dstnNameHindi'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
        
        $hindiTrain = [
            'trainNo' => $train['trainNo'] ?? '',
            'trainName' => $trainNameHindi . ' (' . $srcNameHindi . ' - ' . $dstnNameHindi . ')',
            'expectedTime' => $train['expectedTime'] ?? '',
            'ADFlag' => $train['ADFlag'] ?? '',
            'platformNo' => $train['platformNo'] ?? '-'
        ];
        
        $englishData[] = $englishTrain;
        $hindiData[] = $hindiTrain;
    }
    
    echo json_encode([
        'data' => [
            'ENG' => $englishData,
            'HIN' => $hindiData
        ]
    ]);
} else {
    echo json_encode([
        'error' => 'Invalid Request'
    ]);
}
?>
