<?php

function validateDate(string $date): bool
{
    $dateBlocks = explode("-", $date);

    if (count($dateBlocks) < 3) {
        return false;
    }

    if (isset($dateBlocks[0]) && ((int)$dateBlocks[0] > 31 || (int)$dateBlocks[0] < 1)) {
        return false;
    }

    if (isset($dateBlocks[1]) && ((int)$dateBlocks[1] > 12 || (int)$dateBlocks[1] < 1)) {
        return false;
    }

    if (isset($dateBlocks[2]) && (int)$dateBlocks[2] > date('Y')) {
        return false;
    }

    return true;
}

function validateName(string $name): bool
{
    if (preg_match("/[^a-zA-Zа-яёА-ЯЁ ]/u", $name)) {
        print_r($name);
        return false;
    }
    if (mb_strlen($name) > 20) {
        return false;
    }

    return true;
}
