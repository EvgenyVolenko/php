<?php

namespace Geekbrains\Application1\Domain\Controllers;

class TimeController
{
    public function actionIndex(): string
    {
        $result = [
            'time' => date('H:i')
        ];

        return json_encode($result);
    }
}
