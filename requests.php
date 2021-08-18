<?php

// fetch bootstrap
require_once('bootstrap.php');

// initiate Variable
$action = 'x';
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
}

// prepare allowed requests actions
$allowed = ['contact'];

// validate ajax request
if (!in_array($action, $allowed)) {
    die("Access Denied here");
}
if ((!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest')) {
    die("Access Denied");
}

if ($action == 'contact') {
    try {
        // validations
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])) {
            returnJson(array('success' => false, 'message' => "All fields are required."));
        }

        // prepare password recovery email
        $subject = $_POST['subject'];
        $body = getMailTemplate("contact", $subject, [
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            "message" => $_POST['message'],
        ]);

        $send_email = sendEmail([
            'sender' => $_POST['email'],
            'recipent' => 'olatayo.olayemi.peter@gmail.com',
            'subject' => $subject,
            'body' => $body
        ]);

        if (!$send_email) {
            returnJson([
                'success' => false,
                'message' => 'Message could not be sent at the moment. Please try again later'
            ]);
        } else {
            returnJson([
                'success' => true,
                'message' => "Message has been sent successfully.",
            ]);
        }
    } catch (Exception $exception) {
        returnJson(['success' => false, 'message' => $exception->getMessage()]);
    }
}
