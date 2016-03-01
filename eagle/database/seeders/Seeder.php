<?php

require_once './eagle/models/User.php';
require_once './eagle/utils/Auth.php';
require_once './eagle/utils/Downloader.php';

$test = new User;
$test->name = 'Admin';
$test->email = 'test@email.com';
$test->password = Auth::hash('mmr2410');
$test->rank = 10;
$test->save();

Downloader::getEvent('2016mokc');
Downloader::getEvent('2016iacf');
Downloader::getTeamsAtEvent('2016mokc');
Downloader::getTeamsAtEvent('2016iacf');