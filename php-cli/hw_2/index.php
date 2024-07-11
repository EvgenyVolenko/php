<?php

// 1. Реализовать основные 4 арифметические операции 
// в виде функции с тремя параметрами – два параметра это числа, третий – операция. 
// Обязательно использовать оператор return.

function action(float $a, float $b, string $operator): float | string
{

    if ($operator === "+") {
        return $a + $b;
    } else if ($operator === "-") {
        return $a - $b;
    } else if ($operator === "*") {
        return $a * $b;
    } else if ($operator === "/") {
        return $b != 0 ? $a / $b : "Делить на 0 нельзя";
    } else {
        return "Введите корректную операцию";
    }
}

$a = 4;
$b = 6;

echo "a + b = " . action($a, $b, "+") . PHP_EOL;
echo "a - b = " . action($a, $b, "-") . PHP_EOL;
echo "a * b = " . action($a, $b, "*") . PHP_EOL;
echo "a / b = " . action($a, $b, "/") . PHP_EOL;
echo "a / b = " . action($a, 0, "/") . PHP_EOL;
echo "a + b = " . action($a, $b, "fg") . PHP_EOL;

// 2. Реализовать функцию с тремя параметрами: function mathOperation($arg1, $arg2, $operation), где $arg1, $arg2 – 
// значения аргументов, $operation – строка с названием операции. В зависимости от переданного значения операции 
// выполнить одну из арифметических операций (использовать функции из пункта 1) и вернуть полученное значение (использовать switch).

function mathOperation(float $arg1, float $arg2, string $operation)
{
    switch ($operation) {
        case "+":
            echo "(switch) a + b = " . action($arg1, $arg2, "+") . PHP_EOL;
            break;
        case "-":
            echo "(switch) a - b = " . action($arg1, $arg2, "-") . PHP_EOL;
            break;
        case "*":
            echo "(switch) a * b = " . action($arg1, $arg2, "*") . PHP_EOL;
            break;
        case "/":
            echo "(switch) a / b = " . action($arg1, $arg2, "/") . PHP_EOL;
            break;
        default:
            echo "(switch) Введите корректную операцию" . PHP_EOL;;
            break;
    }
}

mathOperation($a, $b, "+");
mathOperation($a, $b, "-");
mathOperation($a, $b, "*");
mathOperation($a, $b, "/");
mathOperation($a, 0, "/");
mathOperation($a, $b, "bn");

// 3. Объявить массив, в котором в качестве ключей будут использоваться названия областей, 
// а в качестве значений – массивы с названиями городов из соответствующей области. 
// Вывести в цикле значения массива, чтобы результат был таким: 
// Московская область: Москва, Зеленоград, Клин 
// Ленинградская область: Санкт-Петербург, Всеволожск, Павловск, Кронштадт 
// Рязанская область … (названия городов можно найти на maps.yandex.ru).

$arr = [
    'Московская область' => [
        'Москва',
        'Зеленоград',
        'Клин'
    ],
    'Ленинградская область' => [
        'Всеволжск',
        'Павловск'
    ],
    'Рязанская область' => [
        'Рязань'
    ]
];

foreach ($arr as $key => $value) {
    $str = $key . ": ";
    foreach ($value as $city) {
        $str = $str . $city . ", ";
    }
    $str = substr_replace($str, '.', -2);
    echo $str . PHP_EOL;;
}

// 4. Объявить массив, индексами которого являются буквы русского языка, а значениями – соответствующие 
// латинские буквосочетания (‘а’=> ’a’, ‘б’ => ‘b’, ‘в’ => ‘v’, ‘г’ => ‘g’, …, ‘э’ => ‘e’, ‘ю’ => ‘yu’, ‘я’ => ‘ya’). 
// Написать функцию транслитерации строк.

$alfabet = [
    'а' => 'a',   'б' => 'b',   'в' => 'v',
    'г' => 'g',   'д' => 'd',   'е' => 'e',
    'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
    'и' => 'i',   'й' => 'y',   'к' => 'k',
    'л' => 'l',   'м' => 'm',   'н' => 'n',
    'о' => 'o',   'п' => 'p',   'р' => 'r',
    'с' => 's',   'т' => 't',   'у' => 'u',
    'ф' => 'f',   'х' => 'h',   'ц' => 'c',
    'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
    'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
    'э' => 'e',   'ю' => 'yu',  'я' => 'ya'
];

$str = "Транслитерация... Ё-маё!!!";

function checkLetter(string $char, array $alfabetTt): string
{
    foreach ($alfabetTt as $key => $value) {
        if ($char === $key) {
            $char = $value;
        }
    }
    return $char;
}

function Transliteration(string $str, array $alfabetT): string
{
    $newArray = mb_str_split($str);

    for ($i = 0; $i < count($newArray); $i++) {

        if (mb_strtolower($newArray[$i], 'UTF-8') !== $newArray[$i]) {
            $char = mb_strtolower($newArray[$i], 'UTF-8');
            $newArray[$i] = mb_strtoupper(checkLetter($char, $alfabetT));
        } else {
            $newArray[$i] = checkLetter($newArray[$i], $alfabetT);
        }
    }
    return implode('', $newArray);
}

echo $str . PHP_EOL;
echo Transliteration($str, $alfabet) . PHP_EOL;

// 5. *С помощью рекурсии организовать функцию возведения числа в степень. 
// Формат: function power($val, $pow), где $val – заданное число, $pow – степень.

$val = 2;
$pow = 10;

function power(float $val, float $pow, float $res = 1)
{
    if ($pow <= 0) {
        return $res;
    }
    return power($val, $pow - 1, $res * $val);
}

echo power($val, $pow) . PHP_EOL;


// 6. *Написать функцию, которая вычисляет текущее время и возвращает его в формате с правильными склонениями, например:
// 22 часа 15 минут
// 21 час 43 минуты.

//$n % 10;
/*
0 часов	минут	10 часов минут 20 часов минут	30 минут  40 минут
1 час	минута	11 часов минут 21 час	минута	31 минута 41 минута
2 часа	минуты	12 часов минут 22 часа	минуты	32 минуты 42 минуты
3 часа	минуты	13 часов минут 23 часа	минуты	33 минуты 43 минуты
4 часа	минуты	14 часов минут 24 часа	минуты	34 минуты 44 минуты
5 часов	минут	15 часов минут 25		минут	35 минут  45 минут
6 часов	минут	16 часов минут 26		минут	36 минут  46 минут
7 часов минут	17 часов минут 27		минут	37 минут  47 минут
8 часов	минут	18 часов минут 28		минут	38 минут  48 минут
9 часов минут	19 часов минут 29		минут	39 минут  49 минут
*/
date_default_timezone_set('Europe/Moscow');

$time = date("H:i");
$timeArray = explode(':', $time);

// for ($j = 0; $j < 60; $j++) {
//     $timeArray = ["0", (string)$j];
$str = '';

for ($i = 0; $i < count($timeArray); $i++) {
    $str = $str . ' ' . $timeArray[$i] . ' ' . word((int)$timeArray[$i], $i);
}

echo $str . PHP_EOL;
// }


function word(int $digit, int $index): string
{
    if ($index === 0) {
        if ($digit % 20 >= 2 && $digit % 20 <= 4) {
            return "часа";
        } else if ($digit % 20 === 1) {
            return "час";
        } else {
            return "часов";
        }
    } else {
        if ($digit % 10 >= 2 && $digit % 10 <= 4 && ($digit < 12 || $digit > 14)) {
            return "минуты";
        } else if ($digit % 10 === 1 && $digit !== 11) {
            return "минута";
        } else {
            return "минут";
        }
    }
}
