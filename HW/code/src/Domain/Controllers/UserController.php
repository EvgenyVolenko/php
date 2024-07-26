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
        'actionAuth' => ['user'],
        'actionLogin' => ['user']
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
            'user-form.twig',
            [
                'title' => 'Форма создания пользователя'
            ]
        );
    }

    public function actionAuth(): string
    {
        $render = new Render();

        return $render->renderPageWithForm(
            'user-auth.twig',
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

        $render = new Render();
        $result = false;

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);
        }

        if (!$result) {
            return $render->renderPage(
                'auth-template.twig',
                [
                    'title' => 'Форма логина',
                    'user_authorized' => false,
                    'auth_error' => 'Неверные логин или пароль'
                ]
            );
        } else {

            // header('Location: /');
            return $render->renderPageWithForm(
                'auth-template.twig',
                [
                    'title' => 'Форма логина',
                    'user_authorized' => true,
                    'userName' => $_SESSION['user_name']
                ]
            );
        }
    }

    public function actionLogout(): void
    {
        session_destroy();
        unset($_SESSION['user_name']);
        unset($_SESSION['user_lastname']);
        unset($_SESSION['id_user']);
        header("Location: /");
        die();
    }

    public function actionUpdate(): string
    {

        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

        if (User::exists($id)) {
            if (User::validateRequestData()) {
                $user = User::getUserFromStorageById($id);
                if (isset($_POST['name']) && $_POST['name'] !== $user->getUserName()) {
                    $user->setName($_POST['name']);
                }
                if (isset($_POST['lastname']) && $_POST['lastname'] !== $user->getUserLastName()) {
                    $user->setLastName($_POST['lastname']);
                }
                if (isset($_POST['birthday']) && $_POST['birthday'] !== date('d-m-Y', $user->getUserBirthday())) {
                    $user->setBirthdayFromString($_POST['birthday']);
                }

                $user->updateUser($user);
            }
        } else {
            throw new \Exception("Пользователь не существует");
        }

        $render = new Render();
        return $render->renderPage(
            'user-changed.twig',
            [
                'title' => 'Пользователь обновлен',
                'message' => "Обновлен пользователь " . $user->getUserId()
            ]
        );
    }

    public function actionChange(): string
    {
        $render = new Render();

        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

        if (User::exists($id)) {
            $user = User::getUserFromStorageById($id);
        } else {
            throw new \Exception("Пользователь не существует");
        }

        return $render->renderPageWithForm(
            'user-update.twig',
            [
                'title' => 'Форма изменения пользователя',
                'name' => $user->getUserName(),
                'lastname' => $user->getUserLastName(),
                'birthday' => date('m-d-Y', $user->getUserBirthday()),
                'id' => $id
            ]
        );
    }

    public function actionDelete(): string
    {

        if (User::exists($_GET['id'])) {
            User::deleteFromStorage($_GET['id']);

            $render = new Render();

            return $render->renderPageWithForm(
                'user-removed.twig',
                []
            );
        } else {
            throw new \Exception("Пользователь не существует");
        }
    }
}
