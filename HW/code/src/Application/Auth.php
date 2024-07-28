<?php

namespace Geekbrains\Application1\Application;

class Auth
{
    public static function getPasswordHash(string $rawPassword): string
    {
        return password_hash($rawPassword, PASSWORD_BCRYPT);
    }

    public function proceedAuth(string $login, string $password): bool
    {

        if (!$login) {
            header('Location: /'); //!!!!!!!!!!!!!!!!!!!!!!!!!
            die();
        }

        $sql = "SELECT id_user, user_name, user_lastname, password_hash FROM users WHERE user_login = :user_login";

        $handler = Application::$storage->get()->prepare($sql);
        $handler->execute(['user_login' => $login]);
        $result = $handler->fetchAll();

        if (!empty($result) && password_verify($password, $result[0]['password_hash'])) {
            $_SESSION['user_name'] = $result[0]['user_name'];
            $_SESSION['user_lastname'] = $result[0]['user_lastname'];
            $_SESSION['id_user'] = $result[0]['id_user'];

            return true;
        } else {
            return false;
        }
    }
}
