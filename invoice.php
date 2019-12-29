<?php
include('token.php');
include('config.php');

function invoiceNumber(){
    global $clientId, $secret, $sandbox;
    $token = accessToken($clientId,$secret);
    $accept = "Accept:application/json";
    $contenttype = "Content-Type: application/json";
    $authorization = "Authorization: Bearer ".$token;
    $headers = array($accept,$contenttype,$authorization);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, ($sandbox) ? "https://api.sandbox.paypal.com/v2/invoicing/generate-next-invoice-number" 
                                            : "https://api.paypal.com/v2/invoicing/generate-next-invoice-number");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    if(empty($result)) {
        die("Error: Empty result");
    } else {
        return json_decode($result)->invoice_number;
    }
    curl_close($ch);
}

function draftInvoice($productName, $productDescription, $clientEmail, $value){
    global $clientId, $secret, $sandbox, $payPalEmail, $givenName, $surName, $note, $term, $currencyCode, $additionalNotes;
    $token = accessToken($clientId, $secret);
    $invoice_raw = array(
        'detail'=> array(
            'invoice_number'   => invoiceNumber(),
            'currency_code'    => $currencyCode,
            'note'             => $note,
            'term'             => $term
        ), 
        'invoicer'=> array(
            'name'             => array( 'given_name' => $givenName,
                                         'surname'   => $surName),
            'email_address'    => $payPalEmail,
            'additional_notes' => $additionalNotes
        ),
        'primary_recipients'=> array(
                array('billing_info' => array('email_address' => $clientEmail,
                                              'additional_info_value' => 'add-info'))
        ),
        'items'=> array(
            array(
                'name' => $productName,
                'description' => $productDescription,
                'quantity' => '1',
                'unit_amount' => array(
                    'currency_code' => $currencyCode,
                    'value'         => $value
                ),
                'unit_of_measure'   => 'QUANTITY'
            )
        )
    );
    $invoice = json_encode($invoice_raw);
    $accept = "Accept:application/json";
    $contenttype = "Content-Type: application/json";
    $contentlen = "Content-Length: " . strlen($invoice);
    $authorization = "Authorization: Bearer ".$token;
    $headers = array($accept,$contenttype,$authorization,$invoice);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, ($sandbox) ? "https://api.sandbox.paypal.com/v2/invoicing/invoices" 
                                            : "https://api.paypal.com/v2/invoicing/invoices");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $invoice);
    $result = curl_exec($ch);
    curl_close($ch);
    if(empty($result)) {
        die("Error: Empty result");
    } else {
        $pieces = explode('/', json_decode($result)->href);
        sendInvoice($pieces[count($pieces)-1]);
    }
    
}

function sendInvoice($invoiceId){
    global $clientId, $secret, $sandbox;
    $token = accessToken($clientId, $secret);
    $accept = "Accept:application/json";
    $contenttype = "Content-Type: application/json";
    $authorization = "Authorization: Bearer ".$token;
    $headers = array($accept,$contenttype,$authorization);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, ($sandbox) ? "https://api.sandbox.paypal.com/v2/invoicing/invoices/$invoiceId/send" 
                                            : "https://api.paypal.com/v2/invoicing/invoices/$invoiceId/send");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

    $result = curl_exec($ch);
    if(empty($result)) {
        die("Error: Empty result");
    } else {
        print_r(json_decode($result)->href);
    }
    curl_close($ch);
}
?>