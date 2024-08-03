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
        // 'actionIndex' => ['admin'],
        'actionLogout' => ['admin'],
        'actionUpdate' => ['admin'],
        'actionChange' => ['admin'],
        'actionDelete' => ['admin'],
    ];

    public function actionIndex()
    {
        $render = new Render();
        $users = User::getAllUsersFromStorage();
        $roleAdmin = isset($_SESSION['auth']['user_admin']) ? $_SESSION['auth']['user_admin'] : false;

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
                    'users' => $users,
                    'user_role' => $roleAdmin
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
        // $render = new Render();

        // return $render->renderPageWithForm(
        //     'user-form.twig',
        //     [
        //         'title' => 'Форма создания пользователя'
        //     ]
        // );

        $render = new Render();

        $form_name = "Создание пользователя";
        $action = '/user/save';

        if (isset($_GET['id'])) {
            $userId = (int)$_GET['id'];
            $action = '/user/update';
            $userData = User::getUserFromStorageById($userId);
            $form_name = 'Редактирование пользователя';
        }

        return $render->renderPageWithForm(
            'user-form.twig',
            [
                'title' => $form_name,
                'user_data' => $userData ?? [],
                'action' => $action
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
                // 'auth_error' => 'Вы пока не вошли в систему'
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
        if (isset($_GET['pass_string']) && !empty($_GET['pass_string'])) {
            return Auth::getPasswordHash($_GET['pass_string']);
        } else {
            throw new \Exception("Невозможно сгенерировать хэш. Не передан пароль!");
        }
    }

    public function actionLogin(): string
    {

        $render = new Render();
        $result = false;

        if (isset($_POST['login']) && isset($_POST['password'])) {
            $result = Application::$auth->proceedAuth($_POST['login'], $_POST['password']);

            if (
                $result &&
                isset($_POST['user-remember']) && $_POST['user-remember'] == 'remember'
            ) {
                $token = Application::$auth->generateToken($_SESSION['auth']['id_user']);
                User::setToken($_SESSION['auth']['id_user'], $token);
            }
        }

        if (!$result) {
            return $render->renderPage(
                'user-auth.twig',
                [
                    'title' => 'Форма логина',
                    'user_authorized' => false,
                    'auth_error' => 'Неверные логин или пароль'
                ]
            );
        } else {

            // header('Location: /');
            return $render->renderPageWithForm(
                // 'page-index.twig',
                // [
                //     'title' => 'Форма логина',
                //     'user_authorized' => true,
                //     'userName' => $_SESSION['auth']['user_name']
                // ]
            );
        }
    }

    public function actionLogout(): string
    {
        User::destroyToken();
        session_destroy();
        unset($_SESSION['auth']);

        $render = new Render();

        return $render->renderPage();
    }

    public function actionUpdate(): string
    {

        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

        if (User::exists($id)) {
            if (User::validateRequestData()) {
                $user = User::getUserFromStorageById($id);
                $tempUser = new User();
                $tempUser->setUserId($id);
                $tempUser->setName($_POST['name']);
                $tempUser->setLastName($_POST['lastname']);
                $tempUser->setBirthdayFromString($_POST['birthday']);
                $tempUser->setUserLogin($_POST['login']);
                $tempUser->setUserPassword(Auth::getPasswordHash($_POST['password']));
                $tempUser->updateUser($tempUser);
            }
        } else {
            throw new \Exception("Пользователь не существует");
        }

        $render = new Render();
        return $render->renderPage(
            'user-changed.twig',
            [
                'title' => 'Пользователь обновлен',
                'message' => "Обновлен пользователь " . $user['id_user']
            ]
        );
    }

    // public function actionChange(): string
    // {
    //     $render = new Render();

    //     $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

    //     if (User::exists($id)) {
    //         $user = User::getUserFromStorageById($id);
    //     } else {
    //         throw new \Exception("Пользователь не существует");
    //     }

    //     return $render->renderPageWithForm(
    //         'user-update.twig',
    //         [
    //             'title' => 'Форма изменения пользователя',
    //             'name' => $user->getUserName(),
    //             'lastname' => $user->getUserLastName(),
    //             'birthday' => date('m-d-Y', $user->getUserBirthday()),
    //             'id' => $id
    //         ]
    //     );
    // }


    public function actionDelete(): string
    {
        if (User::exists($_GET['id']) && !in_array('admin', User::getUserRolesById($_GET['id']))) {
            User::deleteFromStorage($_GET['id']);

            /**header('Location: /user');
            die();

            $render = new Render();

            return $render->renderPageWithForm(
                'user-removed.twig',
                []
            );*/
            return json_encode(['answer' => "Пользователь с ID={$_GET['id']} уудален"]);
        } else {
            return json_encode("Пользователь не существует или вы пытаетесь удалить администратора");
            // throw new \Exception("Пользователь не существует или вы пытаетесь удалить администратора");
        }
    }

    public function actionIndexRefresh()
    {
        $limit = null;

        if (isset($_POST['maxId']) && ($_POST['maxId'] > 0)) {
            $limit = $_POST['maxId'];
        }

        $users = User::getAllUsersFromStorage($limit);
        $usersData = [];

        if (count($users) > 0) {
            foreach ($users as $user) {
                $usersData[] = $user->getUserDataAsArray();
            }
        }

        return json_encode($usersData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); // параметры только на время разработки и отладки
    }
}
