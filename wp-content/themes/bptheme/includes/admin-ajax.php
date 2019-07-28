<?php 

/**
 * ADMIN AJAX EXAMPLE
 */

add_action("wp_ajax_send_example_form", "sendContactForm");
add_action("wp_ajax_nopriv_send_example_form", "sendContactForm");

function sendContactForm(){
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "send_form_nonce")) {
        exit("No naughty business please");
    }

    $returnData = [];

    $form_submission = array(
        'post_title'    => wp_strip_all_tags( $_REQUEST['first_name'] . ' ' . $_REQUEST['last_name']),
        'post_content'  => $_REQUEST['message'],
        'post_status'   => 'draft',
        'post_author'   => 1,
        'post_type'   => 'form_submission',
    );

    $returnData['submission'] = $form_submission;
    $returnData['finance_status'] = 'accepted';

    $postFields = [
        'content' => [
            'from' => ['name' => 'hello@reddico.co.uk', 'email' => 'hello@reddico.co.uk'],
            'subject' => 'Admin Ajax Sparkpost test email from ' . $_REQUEST['first_name'] . ' ' . $_REQUEST['last_name'],
            'text' => 'A new sparkpost test message has been sent. Please find details below:'.PHP_EOL.'Name: ' . $_REQUEST['first_name'] . ' ' . $_REQUEST['last_name'] . PHP_EOL . 'Email address: ' . $_REQUEST['email'] . PHP_EOL .'Message: ' . $_REQUEST['message'],
        ],
        "recipients"=> [
            [
                "address"=> [
                    "email"=> "jack.callow@reddico.co.uk"
                ]
            ],
//            [
//                "address"=> [
//                    "email"=> "ainsley@reddico.co.uk"
//                ]
//            ]
        ]
    ];


    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.eu.sparkpost.com/api/v1/transmissions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($postFields),
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Authorization: bdbc039a2456e1b2ef5f74a60bbb5b366c1c0df7",
            "Cache-Control: no-cache",
            "Content-Type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);


    if($err) {
        $returnData['status'] = 'fail';
        $returnData['msg'] = 'Message not sent or saved to DB';
    } else {
        $postId = wp_insert_post($form_submission);
        if($postId > 0) {
            update_field( 'email_address', $_REQUEST['email'], $postId );
            $returnData['status'] = 'success';
            $returnData['msg'] = 'Email sent and saved to Database';
            $returnData['sparkpost'] = $response;
        } else {
            $returnData['status'] = 'success';
            $returnData['msg'] = 'Email sent but NOT saved to database';
            $returnData['sparkpost'] = $response;
        }
    }


    echo json_encode($returnData);
    die();
}