<?php
$scl = mysqli_connect('panelser.mysql.tools', 'panelser_pixel', 'd3+S%t3j8H', 'panelser_pixel'); // вход в БД
$post = mysqli_query($scl, "SELECT `Pixel` FROM `Quantima` ");
$pixel = [];
while ($pix = mysqli_fetch_array($post)) {
    $pixel[] = $pix['Pixel'];
}
$pixel = json_encode($pixel);
echo $pixel;
exit;
