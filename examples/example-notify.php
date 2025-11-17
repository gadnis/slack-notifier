<?php

$slack = require __DIR__ . '/bootstrap.php';

$slack->notify('warning', "Dynamic WARNING test");
$slack->notify('info',    "Dynamic INFO test");
$slack->notify('error',   "Dynamic ERROR test");
