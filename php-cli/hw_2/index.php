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
echo Transliteration($str, $alfabet);
