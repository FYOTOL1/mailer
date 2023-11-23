<?php

header("content-type: application/json");
try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fgc =  file_get_contents("php://input");
        $body = json_decode($fgc, true);
        if (isset($body["content"]) && isset($body["title"]) && isset($body["send_to"]) && !is_null($body)) {
            require_once("./mail.php");
            $mail->setFrom("email.fyo@gmail.com");
            $mail->AddAddress($body["send_to"]);
            $mail->Subject = $body["title"];
            $mail->Body = $body["content"];
            $mail->AltBody = $body["alt_content"] ?? "Error";
            $mail->send();
            echo json_encode(["msg" => "Successfully", "data" => $body]);
            return;
        } else {
            http_response_code("400");
            echo json_encode([
                "msg" => "Missing Something",
                "required" => ["title", "send_to", "content", "alt_content"],
                "isset" => $body
            ]);
            return;
        }
    } else {
        header("HTTP/1.1 404 Not Found");
        return;
    }
} catch (Exception $err) {
    return;
}
