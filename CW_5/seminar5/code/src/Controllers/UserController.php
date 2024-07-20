<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Application;
use Geekbrains\Application1\Render;
use Geekbrains\Application1\Models\User;

class UserController
{

    public function actionSave()
    {
        $render = new Render();

        $newUserGET = $_GET;
        $newUser = new User($newUserGET['name']);
        $newUser->setBirthdayFromString($newUserGET['birthday']);
        User::addUser($newUserGET);
        // /user/save/?name=Иван&birthday=05-05-1991
        return $render->renderPage(
            'save-user-result.twig',
            [
                'name' => $newUser->getUserName(),
                'birthday' => $newUserGET['birthday']
            ]
        );
    }

    public function actionIndex()
    {
        $render = new Render();
        $users = User::getAllUsersFromStorage();

        if (!$users) {
            return $render->renderPage(
                'user-empty.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'message' => "Список пуст или не найден"
                ]
            );
        } else {
            return $render->renderPage(
                'user-index.twig',
                [
                    'title' => 'Список пользователей в хранилище',
                    'users' => $users
                ]
            );
        }
    }
}
