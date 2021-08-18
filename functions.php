<?php

/**
 * Resume - HNG Internship Cohort 8 Stage 2 Task.
 *
 * @author Olayemi Olatayo <olatayo.olayemi.peter@gmail.com>
 */

use PHPMailer\PHPMailer\PHPMailer;

function returnJson($response = ''): string {
    header("Expires: Mon, 14 Jun 2005 05:00:00 GMT"); // Date in the past
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Pragma: no-cache"); // HTTP/1.0
    header("Content-Type: application/json");
    exit(json_encode($response));
}


function displayError(string $message): void {
    die($message);
}

/**
 * @throws \PHPMailer\PHPMailer\Exception
 */
function sendEmail($data = []): bool {
    global $config;
    /* set header */
    $header = "From: {$data['sender']} \r\n";
    $header .= "Reply-To: {$data['sender']} \r\n";
    $header .= "Content-Type: text/html; charset=\"utf-8\"\r\n";

    $mail = new PHPMailer(true);
    $mail->isHTML(true);

    if ($config['mail_type'] == 'default') {
        $mail->IsMail();
    } else if ($config['mail_type'] == 'smtp') {
        $mail->IsSMTP();
        $mail->SMTPDebug = 2;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = $config['smtp_encryption'];
        $mail->Host = $config['smtp_host'];
        $mail->Port = $config['smtp_port'];
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
    }
    $mail->CharSet = "UTF-8";
    $mail->Subject = $data['subject'];
    $mail->Body = $data['body'];
    $mail->setFrom($data['sender'], $data['name']);
    $mail->addReplyTo($data['sender'], $data['name']);
    $mail->AddAddress($data['recipent']);

    // send mail using phpmailer library
    if (!$mail->send()) {
        // send mail using default php mail
        if (!mail($data['recipent'], $data['subject'], $data['body'], $header)) {
            return false;
        }
    }
    return true;
}


function renderPage(string $page): void {
    global $smarty;
    try {
        $page = ($page != '') ? $page : 'index';
        $smarty->display($page . '.tpl');
    } catch (Exception $exception) {
        displayError('System Error:' . $exception->getMessage());
    }
}


/**
 * @throws SmartyException
 */
function getMailTemplate(string $name, string $subject, string|array $variables = []): string {
    global $smarty;
    $smarty->assign("subject", $subject);
    if ($variables) {
        foreach ($variables as $key => $value) {
            $smarty->assign($key, $value);
        }
    }
    return $smarty->fetch("emails/" . $name . ".html");
}
