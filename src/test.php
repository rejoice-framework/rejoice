<?php
require_once realpath(__DIR__) . '/../../../autoload.php';

use App\Helpers\MoMoService;
\set_time_limit(180);
// $res = MoMoService::pay('233545466796', '01', '0.2');
/*
Error 1: voucher code in JSON
Error 2: {"status":"Declined","
code":"030",
"reason":"Transaction amount below GHS 0.10 are not allowed.","auth_code":"030"}"
}

Error 3: {
["SUCCESS"]=> bool(false)
["data"]=> bool(false)
["error"]=> string(66) "Operation timed out after 30001 milliseconds with 0 bytes received"
}

{
"status": "declined",
"code": 109,
"reason": "Transaction timed out or declined",
"transaction_id": "000248975873"
}
 */
// echo base64_encode('nampa5e3995012a9fd:MTAyZWEyYmIyYmU0YmY1ZTI4NDkyNjAwMTRiMzZhMzM=');

echo str_pad(rand(1, 999999999), 12, '0', STR_PAD_LEFT);

// echo str_pad(floatval(0.2) * 100, 12, '0', STR_PAD_LEFT);
// var_dump($res);
