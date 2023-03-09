<?php
$fname = ($_POST['FName']) ? $_POST['FName'] : 'test'; // имя
$lname = ($_POST['LName']) ? $_POST['LName'] : 'test'; // фамилия
$fullphone = ($_POST['Phone']) ? $_POST['Phone'] : '111111111111'; // нелефон
$email = ($_POST['Email']) ? $_POST['Email'] : 'test@gmail.com'; // емаил
$ip = ($_SERVER['REMOTE_ADDR'] == '::1') ? '173.176.183.21' : $_SERVER['REMOTE_ADDR']; // ip пользователя
$domain = $_SERVER['SERVER_NAME']; // название домена
// получение страны и кода 
counAdd($ip);
$ipCR = D_ipCR; //  id страны
$coun = D_coun; //  страна пользователя
$sours = 'Quantima_Ai'; //  название проекта
$num_rows = ''; // количество строк
$lids_today = ''; // лидов за сегодня
$re_entry = true;
$scl = mysqli_connect('panelser.mysql.tools', 'panelser_db', 'KE4MVQqD', 'panelser_db'); // вход в БД
$chat_id = '-1001541410143'; // ID чата телеграм
$exit = '';
//////////////////////////////////////////////////////////////////////
if ($fname != 'test') {
    $apiData = [
        'link_id' => 3717,
        'fname' => $fname,
        'lname' => $lname,
        'email' => $email,
        'fullphone' => '+' . $fullphone,
        'source' => "Imperial OIL 2.0",
        'pass' => '12345678',
        'ip' => $ip,
        'domain' => $domain,
        'utm_source' => isset($utm_source) ? $utm_source : "",
        'utm_medium' => isset($utm_medium) ? $utm_medium : "",
        'utm_campaign' => isset($utm_campaign) ? $utm_campaign : "",
        'utm_term' => isset($utm_term) ? $utm_term : "",
        'utm_content' => isset($utm_content) ? $utm_content : "",
        'click_id' => isset($click_id) ? $click_id : "",
        'promo' => isset($promo) ? $promo : "",
        'trading_platform' => isset($tradeserv) ? $tradeserv : "WebTrade",
    ];
    $apiData = json_encode($apiData);
    $exit = addCrm($apiData);
} else {
    $exit = 'test';
}
// проверка gmail и phone на валидность
$post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Phone = '+$fullphone'");
$phone_valid = mysqli_fetch_assoc($post);
$phone_valid = $phone_valid['count'];
$post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Email = '$email'");
$email_valid = mysqli_fetch_assoc($post);
$email_valid = $email_valid['count'];
if ($phone_valid || $email_valid) {
    // Телеграм бот на повтор
    if ($phone_valid) {
        $post = mysqli_query($scl, "SELECT * FROM `lids` WHERE `Phone` = '+$fullphone'");
        $date_reg = mysqli_fetch_assoc($post);
        $date_reg = json_encode($date_reg['Data']);
    } else {
        $post = mysqli_query($scl, "SELECT * FROM `lids` WHERE `Email` = '$email'");
        $date_reg = mysqli_fetch_assoc($post);
        $date_reg = json_encode($date_reg['Data']);
    }
    // Составление сообщения
    $text = '';
    $text .= "\n" . '🟡Повторный Lid🟡';
    $text .= "\n" . 'Источник: ' . $sours;
    $text .= "\n" . 'Fast Name: ' . $fname;
    $text .= "\n" . 'Last Name: ' . $lname;
    $text .= "\n" . 'Email: ' . $email;
    $text .= "\n" . 'Phone: +' . $fullphone;
    $text .= "\n" . 'country: ' . $coun;
    $text .= "\n" . 'ip: ' . $_SERVER['REMOTE_ADDR'];
    $text .= "\n" . 'Data: ' . date('d.m.y H:i:s', strtotime('+2hour'));
    $text .= "\n" . 'Дата первой регистрации: ' . $date_reg;
    $text .= "\n" . 'Ответ CRM: ' . $exit;
    // 
    $param = [
        "chat_id" => $chat_id,
        "text" => $text
    ];
    addTgBot($param);
    exit($exit);
}
// получение $num_rows
$post = mysqli_query($scl, "SELECT * FROM lids");
$num_rows = mysqli_num_rows($post);
$num_rows = $num_rows + 1;
// получение количество лидов за сегодня
$dat = date('Y-m-d', strtotime('+2hour'));
$post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Data LIKE '%$dat%'");
$lids_today = mysqli_fetch_assoc($post);
$lids_today = $lids_today['count'] + 1;
// Телеграм бот
// Составление сообщения
if ($exit == 1) {
    $exTop = 'New lid';
} else {
    $exTop = $exit;
}
$text = '';
if ($fname == 'test') {
    $text .= "\n" . '🔵Тестовй лид:🔵';
    $chat_id = '-1001835903075';
    // $text .= "\n" . 'Лидов за сегодня: ' . $lids_today;
} else {
    $text .= "\n" . '🟢Новый лид:🟢 №' . $num_rows;
    //$text .= "\n" . 'Лидов за сегодня: ' . $lids_today;
}
$text .= "\n" . 'Источник: ' . $sours;
$text .= "\n" . 'Fast Name: ' . $fname;
$text .= "\n" . 'Last Name: ' . $lname;
$text .= "\n" . 'Email: ' . $email;
$text .= "\n" . 'Phone: +' . $fullphone;
$text .= "\n" . 'country: ' . $coun;
$text .= "\n" . 'ip: ' . $_SERVER['REMOTE_ADDR'];
$text .= "\n" . 'Data: ' . date('d.m.y H:i:s', strtotime('+2hour'));
$text .= "\n" . 'Ответ CRM: ' . $exTop;
// 
$param = [
    "chat_id" => $chat_id,
    "text" => $text
];
addTgBot($param);
// Запись в БД
if ($fname == 'test') {
    $post = mysqli_query($scl, "INSERT INTO `Test` (`FName`, `LName`, `Email`, `Phone`, `ip`, `Country`, `source`) VALUES('$fname', '$lname', '$email', '+$fullphone', '$ip', '$coun', '$sours')");
} else {
    $post = mysqli_query($scl, "INSERT INTO `lids` (`FName`, `LName`, `Email`, `Phone`, `ip`, `Country`, `source`) VALUES('$fname', '$lname', '$email', '+$fullphone', '$ip', '$coun', '$sours')");
}
// новая црм

$scl = mysqli_close($scl);
exit($exit);

// Oтправка в тг
function addTgBot($param)
{
    $tg_bot_token = '5987793418:AAH7O8AjmcmD29ig0H-K31jjzXAfwrjo6c4'; // Токен телеграм
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $tg_bot_token . '/sendMessage');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(array('Content-Type: application/json'), []));
    $result = curl_exec($ch);
    curl_close($ch);
}
// получение страны и кода страны
function counAdd($ip)
{
    // получение $ipCR
    $ip_data = file_get_contents("http://api.sypexgeo.net/json/" . $ip);
    $ip_data = json_decode($ip_data);
    foreach ($ip_data as $i => $vl) {
        if ($i === 'country') {
            foreach ($vl as $d => $va) {
                if ($d === 'id') {
                    define('D_ipCR', $va);
                }
            }
        }
    }
    // получение $coun
    foreach ($ip_data as $ic => $vla) {
        if ($ic === 'country') {
            foreach ($vla as $di => $vda) {
                if ($di === 'name_en') {
                    define('D_coun', $vda);
                }
            }
        }
    }
}
// отправка в crm 
function addCrm($apiData)
{
    $token = 'mVec2YGDK6LU9PiU4r8uBjGCP46nLHIjtVXy5h3rP583yeGvfowvx9A3TEVO';
    $url = 'https://marketing.affboat.com/api/v3/integration?api_token=' . $token;
    $header = array();
    $header[] = 'Content-Type: application/json';
    $crl = curl_init();
    curl_setopt($crl, CURLOPT_URL, $url);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($crl, CURLOPT_VERBOSE, 0);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $apiData);
    $rest = curl_exec($crl);
    curl_close($crl);
    $rest = json_decode($rest);
    foreach ($rest as $i) {
        if ($i == 'Phone number not valid!') {
            return $i;
        }
        if ($i == 'Lead already exists in your account' || $i == 'Duplicate!') {
            return 'You are already registered';
        }
    }
    return true;
}













// <?php
// $fname = ($_POST['FName']) ? $_POST['FName'] : 'test'; // имя
// $lname = ($_POST['LName']) ? $_POST['LName'] : 'test'; // фамилия
// $fullphone = ($_POST['Phone']) ? $_POST['Phone'] : '1111111111'; // нелефон
// $email = ($_POST['Email']) ? $_POST['Email'] : 'test@gmail.com'; // емаил
// $ip = ($_SERVER['REMOTE_ADDR'] == '::1') ? '173.176.183.21' : $_SERVER['REMOTE_ADDR']; // ip пользователя
// $domain = $_SERVER['SERVER_NAME']; // название домена
// $ipCR = 'test'; //  id страны
// $coun = 'test'; //  страна пользователя
// $sours = 'Imperial Oil'; //  название проекта
// $num_rows = ''; // количество строк
// $lids_today = ''; // лидов за сегодня
// $re_entry = true;
// $scl = mysqli_connect('fdmarket.mysql.tools', 'fdmarket_db', 'XAn8mJ68rF6z', 'fdmarket_db'); // вход в БД
// $tg_bot_token = '5987793418:AAH7O8AjmcmD29ig0H-K31jjzXAfwrjo6c4'; // Токен телеграм
// $chat_id = '-1001541410143'; // ID чата телеграм
// //////////////////////////////////////////////////////////////////////
// // проверка gmail и phone на валидность
// $post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Phone = '+$fullphone'");
// $phone_valid = mysqli_fetch_assoc($post);
// $phone_valid = $phone_valid['count'];
// $post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Email = '$email'");
// $email_valid = mysqli_fetch_assoc($post);
// $email_valid = $email_valid['count'];
// if ($phone_valid || $email_valid) {
//     // получение $ipCR
//     $ip_data = file_get_contents("http://api.sypexgeo.net/json/" . $ip);
//     $ip_data = json_decode($ip_data);
//     foreach ($ip_data as $i => $vl) {
//         if ($i === 'country') {
//             foreach ($vl as $d => $va) {
//                 if ($d === 'id') {
//                     $ipCR = $va;
//                 }
//             }
//         }
//     }
//     // получение $coun
//     foreach ($ip_data as $ic => $vla) {
//         if ($ic === 'country') {
//             foreach ($vla as $di => $vda) {
//                 if ($di === 'name_en') {
//                     $coun = $vda;
//                 }
//             }
//         }
//     }
//     // Телеграм бот на повтор
//     if ($phone_valid) {
//         $post = mysqli_query($scl, "SELECT * FROM `lids` WHERE `Phone` = '+$fullphone'");
//         $date_reg = mysqli_fetch_assoc($post);
//         $date_reg = json_encode($date_reg['Data']);
//     } else {
//         $post = mysqli_query($scl, "SELECT * FROM `lids` WHERE `Email` = '$email'");
//         $date_reg = mysqli_fetch_assoc($post);
//         $date_reg = json_encode($date_reg['Data']);
//     }
//     // Составление сообщения
//     $text = '';
//     $text .= "\n" . '🟡Повторный Lid🟡';
//     $text .= "\n" . 'Источник: ' . $sours;
//     $text .= "\n" . 'Fast Name: ' . $fname;
//     $text .= "\n" . 'Last Name: ' . $lname;
//     $text .= "\n" . 'Email: ' . $email;
//     $text .= "\n" . 'Phone: +' . $fullphone;
//     $text .= "\n" . 'country: ' . $coun;
//     $text .= "\n" . 'ip: ' . $_SERVER['REMOTE_ADDR'];
//     $text .= "\n" . 'Data: ' . date('d.m.y H:i:s', strtotime('+2hour'));
//     $text .= "\n" . 'Дата первой регистрации: ' . $date_reg;
//     // 
//     $param = [
//         "chat_id" => $chat_id,
//         "text" => $text
//     ];
//     $url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendMessage?" . http_build_query($param);
//     file_get_contents($url);
//     $url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendDocument";
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, ["chat_id" => $chat_id]);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//     $out = curl_exec($ch);
//     curl_close($ch);
//     exit('You are already registered, expect a call.');
// }
// // получение $ipCR
// $ip_data = file_get_contents("http://api.sypexgeo.net/json/" . $ip);
// $ip_data = json_decode($ip_data);
// foreach ($ip_data as $i => $vl) {
//     if ($i === 'country') {
//         foreach ($vl as $d => $va) {
//             if ($d === 'id') {
//                 $ipCR = $va;
//             }
//         }
//     }
// }
// // получение $coun
// foreach ($ip_data as $ic => $vla) {
//     if ($ic === 'country') {
//         foreach ($vla as $di => $vda) {
//             if ($di === 'name_en') {
//                 $coun = $vda;
//             }
//         }
//     }
// }
// $ip_data = json_encode($ip_data);
// // получение $num_rows
// $post = mysqli_query($scl, "SELECT * FROM lids");
// $num_rows = mysqli_num_rows($post);
// $num_rows = $num_rows + 1;
// // получение количество лидов за сегодня
// $dat = date('Y-m-d', strtotime('+2hour'));
// $post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Data LIKE '%$dat%'");
// $lids_today = mysqli_fetch_assoc($post);
// $lids_today = $lids_today['count'] + 1;

// // получение токена от БД лиама
// $addToken = array(
//     'username' => "MetaLive",
//     'password' => "HvoGeZ0574aL"
// );
// $addToken = json_encode($addToken);
// $url = 'https://api.alphatech.proftit.com/api/user/v3/tokens';
// $header = array();
// $header[] = 'Accept: application/json';
// $header[] = 'Content-Type: application/json';
// $header[] = 'cache-control: no-cache';
// $crl = curl_init();
// curl_setopt($crl, CURLOPT_URL, $url);
// curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
// curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($crl, CURLOPT_POST, true);
// curl_setopt($crl, CURLOPT_POSTFIELDS, $addToken);
// curl_setopt($crl, CURLOPT_INTERFACE, '185.233.116.51');
// $rest = curl_exec($crl);
// $rest = json_decode($rest);
// $token = false;
// if ($rest) {
//     foreach ($rest as $val) {
//         if (gettype($val) == 'string') {
//             if (strlen($val) > 60) {
//                 $token = $val;
//             }
//         }
//     };
// }
// curl_close($crl);
// // отправка в БД лама
// $apiData = array(
//     "isLead" => true,
//     "firstName" => $fname,
//     "lastName" => $lname,
//     "email" => $email,
//     "phone" => $fullphone,
//     "password" => "a1234",
//     "brandId" => "1",
//     "countryId" => $ipCR,
//     "campaignId" => 1005,
//     "productName" => $sours,
//     "marketingInfo" => "additionalFunnelInfo",
// );
// $apiData = json_encode($apiData);
// $url = 'https://api.alphatech.proftit.com/api/user/v3/customers';
// $header = array();
// $header[] = 'Accept: application/json';
// $header[] = 'Content-Type: application/json';
// $header[] = 'cache-control: no-cache,no-cache';
// $header[] = 'authorization:' . $token;
// $crl = curl_init();
// curl_setopt($crl, CURLOPT_URL, $url);
// curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
// curl_setopt($crl, CURLOPT_VERBOSE, 0);
// curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($crl, CURLOPT_POST, true);
// curl_setopt($crl, CURLOPT_POSTFIELDS, $apiData);
// curl_setopt($crl, CURLOPT_INTERFACE, '185.233.116.51');
// $rest = curl_exec($crl);
// curl_close($crl);
// // Телеграм бот
// // Составление сообщения
// $text = '';
// if ($fname == 'test') {
//     $text .= "\n" . '🔵Тестовй лид:🔵';
//     $chat_id = '-1001835903075';
//     // $text .= "\n" . 'Лидов за сегодня: ' . $lids_today;

// } else {
//     $text .= "\n" . '🟢Новый лид:🟢 №' . $num_rows;
//     //$text .= "\n" . 'Лидов за сегодня: ' . $lids_today;
// }
// $text .= "\n" . 'Источник: ' . $sours;
// $text .= "\n" . 'Fast Name: ' . $fname;
// $text .= "\n" . 'Last Name: ' . $lname;
// $text .= "\n" . 'Email: ' . $email;
// $text .= "\n" . 'Phone: +' . $fullphone;
// $text .= "\n" . 'country: ' . $coun;
// $text .= "\n" . 'ip: ' . $_SERVER['REMOTE_ADDR'];
// $text .= "\n" . 'Data: ' . date('d.m.y H:i:s', strtotime('+2hour'));
// // 
// $param = [
//     "chat_id" => $chat_id,
//     "text" => $text
// ];
// $url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendMessage?" . http_build_query($param);
// file_get_contents($url);
// $url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendDocument";
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, ["chat_id" => $chat_id]);
// curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// $out = curl_exec($ch);
// curl_close($ch);

// // Запись в БД
// if ($fname == 'test') {
//     $post = mysqli_query($scl, "INSERT INTO `Test` (`FName`, `LName`, `Email`, `Phone`, `ip`, `Country`, `source`) VALUES('$fname', '$lname', '$email', '+$fullphone', '$ip', '$coun', '$sours')");
// } else {
//     $post = mysqli_query($scl, "INSERT INTO `lids` (`FName`, `LName`, `Email`, `Phone`, `ip`, `Country`, `source`) VALUES('$fname', '$lname', '$email', '+$fullphone', '$ip', '$coun', '$sours')");
// }
// $scl = mysqli_close($scl);

// // новая црм
// $apiData = [
//     'link_id' => 3717,
//     'fname' => $fname,
//     'lname' => $lname,
//     'email' => $email,
//     'fullphone' => '+' . $fullphone,
//     'source' => "Imperial OIL 2.0",
//     'pass' => '12345678',
//     'ip' => $ip,
//     'domain' => $domain,
//     'utm_source' => isset($utm_source) ? $utm_source : "",
//     'utm_medium' => isset($utm_medium) ? $utm_medium : "",
//     'utm_campaign' => isset($utm_campaign) ? $utm_campaign : "",
//     'utm_term' => isset($utm_term) ? $utm_term : "",
//     'utm_content' => isset($utm_content) ? $utm_content : "",
//     'click_id' => isset($click_id) ? $click_id : "",
//     'promo' => isset($promo) ? $promo : "",
//     'trading_platform' => isset($tradeserv) ? $tradeserv : "WebTrade",
// ];

// $apiData = json_encode($apiData);
// $token = 'mVec2YGDK6LU9PiU4r8uBjGCP46nLHIjtVXy5h3rP583yeGvfowvx9A3TEVO';
// $url = 'https://marketing.affboat.com/api/v3/integration?api_token=' . $token;
// $header = array();
// $header[] = 'Content-Type: application/json';
// $crl = curl_init();
// curl_setopt($crl, CURLOPT_URL, $url);
// curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
// curl_setopt($crl, CURLOPT_VERBOSE, 0);
// curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($crl, CURLOPT_POST, true);
// curl_setopt($crl, CURLOPT_POSTFIELDS, $apiData);
// $rest = curl_exec($crl);
// $rest = json_decode($rest);
// foreach ($rest as $i) {
//     if ($i == 'Phone number not valid!') {
//         // echo ($i);
//         exit($i);
//     }
//     if ($i == 'Lead already exists in your account' || $i == 'Duplicate!') {
//         // echo ($i);
//         exit('You are already registered');
//     }
//     // echo ($i);
// }
// curl_close($crl);


// exit(true);
