<?php
register_shutdown_function('XCCErrorHandler');
function XCCErrorHandler()
{
    if($e = error_get_last()) {
        $message = '';
        $message .= "error:\t".$e['message']."\n\n";
        $message .= "file:\t".$e['file']."\n\n";
        $message .= "line:\t".$e['line']."\n\n";
        echo $message;
    }
}
