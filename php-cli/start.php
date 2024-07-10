<?php
echo "Привет, GeekBrains!\n";

$a = 5;
$b = '05';
var_dump($a == $b);
var_dump((int)'012345');
var_dump((float)123.0 === (int)123.0);
var_dump(0 == 'hello, world');

// docker run --rm -v ${pwd}/php-cli/:/cli php:8.2-cli php /cli/start.php

// Используя только две числовые переменные, поменяйте их значение местами. Например, если a = 1, b = 2, надо, чтобы получилось: b = 1, a = 2. Дополнительные переменные, функции и конструкции типа list() использовать нельзя.

$c = 2;
$d = 3;

$d = $d + $c;
$c = $d - $c;
$d = $d - $c;

echo "c={$c} d={$d}\n";

$d = $d ^ $c;
$c = $d ^ $c;
$d = $d ^ $c;

echo "c={$c} d={$d}";