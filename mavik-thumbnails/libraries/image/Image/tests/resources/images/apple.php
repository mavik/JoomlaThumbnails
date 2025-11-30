<?php
header('Content-Type: image/jpeg');
$image = file_get_contents('apple.jpg');
echo $image;