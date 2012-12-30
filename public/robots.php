<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Date: 24.07.12
 */
header('Content-Type: text/plain');

define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

echo file_get_contents('robots.' . mb_strtolower(APPLICATION_ENV) . '.txt');