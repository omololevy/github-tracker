<?php

$limit = 5555;
$used = 0;
$message = "";

function getHeaders($curl, $header_line)
{
    if (strpos($header_line, "X-RateLimit-Limit:") !== false) {
        $GLOBALS["limit"] = (int) preg_replace("/[^0-9]/", "", $header_line);
    }
    if (strpos($header_line, "X-RateLimit-Used:") !== false) {
        $GLOBALS["used"] = (int) preg_replace("/[^0-9]/", "", $header_line);
    }
    return strlen($header_line);
}

function doAction($u, $p, $action, $user)
{
    $cURLConnection = curl_init();
    curl_setopt(
        $cURLConnection,
        CURLOPT_URL,
        "https://api.github.com/user/following/" . $user
    );
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_USERPWD, $u . ":" . $p);
    curl_setopt($cURLConnection, CURLOPT_CUSTOMREQUEST, $action);
    curl_setopt($cURLConnection, CURLOPT_HEADERFUNCTION, "getHeaders");
    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, [
        "Accept: application/vnd.github.v3+json",
        "User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Mobile Safari/537.36",
    ]);
    $result = curl_exec($cURLConnection);
    curl_close($cURLConnection);
    return $result;
}

?>