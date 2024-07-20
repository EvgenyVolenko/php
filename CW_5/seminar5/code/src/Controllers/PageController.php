<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Render;
use Geekbrains\Application1\Application;

class PageController
{


    public function actionIndex()
    {
        $render = new Render();

        return $render->renderPage('page-index.twig', [
            'title' => 'Главная страница',
            // 'style' => Application::config()['storage']['style']
        ]);
    }
}
