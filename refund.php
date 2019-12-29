<?php
include('token.php');
include('config.php');

function fullRefund($saleId) {
    echo $saleId;
    global $clientId, $secret, $sandbox;
    $token = accessToken($clientId,$secret);
    $accept = "Accept:application/json";
    $contenttype = "Content-Type: application/json";
    $authorization = "Authorization: Bearer ".$token;
    $headers = array($accept,$contenttype,$authorization);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, ($sandbox) ? "https://api.sandbox.paypal.com/v1/payments/sale/$saleId/refund" 
                                            : "https://api.paypal.com/v1/payments/sale/$saleId/refund");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    if(empty($result)) {
        die("Error: Empty result");
    } else {
        return json_decode($result);
    }
    curl_close($ch);
}

function partialRefund($saleId, $refundValue) {
    echo $saleId;
    global $clientId, $secret, $sandbox, $currencyCode;
    $token = accessToken($clientId,$secret);
    $accept = "Accept:application/json";
    $contenttype = "Content-Type: application/json";
    $authorization = "Authorization: Bearer ".$token;
    $headers = array($accept,$contenttype,$authorization);
    $postFields = array('amount' => array('total' => $refundValue,
                                            'currency' => $currencyCode));
    $post = json_encode($postFields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, ($sandbox) ? "https://api.sandbox.paypal.com/v1/payments/sale/$saleId/refund" 
                                            : "https://api.paypal.com/v1/payments/sale/$saleId/refund");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
    if(empty($result)) {
        die("Error: Empty result");
    } else {
        return json_decode($result);
    }
    curl_close($ch);
}
?>