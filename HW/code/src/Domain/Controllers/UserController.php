<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\User;
use Geekbrains\Application1\Application\Auth;

class UserController extends AbstractController
{

    protected array $actionsPermissions = [
        'actionHash' => ['admin'],
        'actionSave' => ['admin'],
        'actionEdit' => ['admin'],
        'actionIndex' => ['admin'],
        'actionLogout' => ['admin'],
    ];

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

    public function actionSave(): string
    {
        if (User::validateRequestData()) {
            $user = new User();
            $user->setParamsFromRequestData();
            $user->saveToStorage();

            $render = new Render();

            return $render->renderPage(
                'user-created.twig',
                [
                    'title' => 'Пользователь создан',
                    'message' => "Создан пользователь " . $user->getUserName() . " " . $user->getUserLastName()
                ]
            );
        } else {
            throw new \Exception("Переданные данные некорректны");
        }
    }

    public function actionEdit(): string
    {
        $render = new Render();

        return $render->renderPageWithForm(
            'user-form.tpl',
            [
                'title' => 'Форма создания пользователя'
            ]
        );
    }

    public function actionAuth(): string
    {
        $render = new Render();

        return $render->renderPageWithForm(
            'user-auth.tpl',
            [
                'title' => 'Форма логина'
            ]
        );
    }

    function actionShow()
    {
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
        if (User::exists($id)) {
            $user = User::getUserFromStorageById($id);
            $render = new Render();
            return $render->renderPage(
                'user-page.twig',
                [
                    'user' => $user
                ]
            );
        } else {
            throw new \Exception("Пользователь не существует");
        }
    }

    public function actionHash(): string
    {
        return Auth::getPasswordHash($_GET['pass_string']);
    }

    public function actionLogin(): string
    {
        $result = false;

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
        }

        if (!$result) {
            $render = new Render();

            return $render->renderPageWithForm(
                'user-auth.twig',
                [
                    'title' => 'Форма логина',
                    'auth-success' => false,
                    'auth-error' => 'Неверные логин или пароль'
                ]
            );
        } else {
            header('Location: /');
            return "";
        }
    }

    public function actionLogout(): void
    {
        session_destroy();
        unset($_SESSION['user_name']);
        header("Location: /");
        die();
    }

    public function actionUpdate(): string
    {
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
        if (User::exists($id)) {
            $user = new User();
            $user->setUserId($_GET['id']);

            $arrayData = [];

            if (isset($_GET['name']))
                $arrayData['user_name'] = $_GET['name'];

            if (isset($_GET['lastname'])) {
                $arrayData['user_lastname'] = $_GET['lastname'];
            }

            $user->updateUser($arrayData);
        } else {
            throw new \Exception("Пользователь не существует");
        }

        $render = new Render();
        return $render->renderPage(
            'user-created.twig',
            [
                'title' => 'Пользователь обновлен',
                'message' => "Обновлен пользователь " . $user->getUserId()
            ]
        );
    }

    public function actionDelete(): string
    {
        if (User::exists($_GET['id'])) {
            User::deleteFromStorage($_GET['id']);

            $render = new Render();

            return $render->renderPage(
                'user-removed.twig',
                []
            );
        } else {
            throw new \Exception("Пользователь не существует");
        }
    }
}
