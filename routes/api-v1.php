<?php

$apiFiles = glob(base_path('routes/v1/*.php'));
//dd($apiFiles);
foreach ($apiFiles as $apiFile) {
    require $apiFile;
    //var_dump($apiFile);
}