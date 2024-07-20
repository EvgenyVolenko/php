<?php

// function readAllFunction(string $address) : string {
function readAllFunction(array $config): string
{
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "rb");

        $contents = '';

        while (!feof($file)) {
            $contents .= fread($file, 100);
        }

        fclose($file);
        return $contents;
    } else {
        return handleError("Файл не существует");
    }
}

// function addFunction(string $address) : string {
function addFunction(array $config): string
{
    $address = $config['storage']['address'];

    $name = readline("Введите имя: ");

    if (validateName($name)) {
        return handleError("Введена некорректная информация") . PHP_EOL;
    }

    $date = readline("Введите дату рождения в формате ДД-ММ-ГГГГ: ");

    if (validateDate($date)) {
        $data = $name . ", " . $date . "\r\n";

        $fileHandler = fopen($address, 'a');

        if (fwrite($fileHandler, $data)) {
            return "Запись $data добавлена в файл $address";
        } else {
            return handleError("Произошла ошибка записи. Данные не сохранены") . PHP_EOL;
        }

        fclose($fileHandler);
    } else {
        return handleError("Введена некорректная информация") . PHP_EOL;
    }
}

// function clearFunction(string $address) : string {
function clearFunction(array $config): string
{
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "w");

        fwrite($file, '');

        fclose($file);
        return "Файл очищен";
    } else {
        return handleError("Файл не существует");
    }
}

function findProfilesTodayBirthday(array $config): string
{
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $timeZone = $config['localSetings']['timeZone'];
        if ($timeZone) {
            date_default_timezone_set($timeZone);
        }

        $file = fopen($address, "r");

        $result = [];
        $today = explode('-', date("d-m-Y"));

        while (!feof($file)) {
            $item = explode(',', fgets($file));
            if ($item[0] !== '') {
                $dateBlocks = explode("-", trim($item[1]));
                if ($today[0] === $dateBlocks[0] && $today[1] === $dateBlocks[1]) {
                    array_push($result, implode($item));
                }
            }
        }

        if (count($result) > 0) {
            array_unshift($result, 'Сегодня день рождения у:' . PHP_EOL);
        } else {
            return 'Сегодня нет именинников!';
        }

        fclose($file);
        return implode($result);
    } else {
        return handleError("Файл данных не существует");
    }
}

function delProfileByName(array $config): string
{
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address) && is_writable(($address))) {

        $name = readline("Введите имя: ");

        if (validateName($name)) {
            return handleError("Введена некорректная информация") . PHP_EOL;
        }

        $fileHandler = fopen($address, "r");

        $contents = '';
        $marker = false;

        while (!feof($fileHandler)) {
            $item = explode(',', fgets($fileHandler));
            if ($item[0] !== '') {
                if (mb_strtolower($name) === mb_strtolower($item[0])) {
                    $marker = true;
                } else {
                    $contents .= $item[0] . "," . $item[1];
                }
            }
        }

        fclose($fileHandler);

        if (!$marker) {
            return handleError("Указанного пользователя нет в файле!") . PHP_EOL;;
        }

        $fileHandler = fopen($address, "w");

        if (fwrite($fileHandler, $contents)) {
            return "Пользователь $name удален из файла $address" . PHP_EOL
                . "Теперь в файле:" . PHP_EOL
                . readAllFunction($config);
        }

        fclose($fileHandler);
    } else {
        return handleError("Файл данных не существует");
    }
}

function helpFunction()
{
    return handleHelp();
}

function readConfig(string $configAddress): array|false
{
    return parse_ini_file($configAddress, true);
}

function readProfilesDirectory(array $config): string
{
    $profilesDirectoryAddress = $config['profiles']['address'];

    if (!is_dir($profilesDirectoryAddress)) {
        mkdir($profilesDirectoryAddress);
    }

    $files = scandir($profilesDirectoryAddress);

    $result = "";

    if (count($files) > 2) {
        foreach ($files as $file) {
            if (in_array($file, ['.', '..']))
                continue;

            $result .= $file . "\r\n";
        }
    } else {
        $result .= "Директория пуста \r\n";
    }

    return $result;
}

function readProfile(array $config): string
{
    $profilesDirectoryAddress = $config['profiles']['address'];

    if (!isset($_SERVER['argv'][2])) {
        return handleError("Не указан файл профиля");
    }

    $profileFileName = $profilesDirectoryAddress . $_SERVER['argv'][2] . ".json";

    if (!file_exists($profileFileName)) {
        return handleError("Файл $profileFileName не существует");
    }

    $contentJson = file_get_contents($profileFileName);
    $contentArray = json_decode($contentJson, true);

    $info = "Имя: " . $contentArray['name'] . "\r\n";
    $info .= "Фамилия: " . $contentArray['lastname'] . "\r\n";

    return $info;
}
