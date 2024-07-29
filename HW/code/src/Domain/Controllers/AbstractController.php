<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Domain\Models\User;

class AbstractController
{

    protected array $actionsPermissions = [];

    public function getUserRoles(): array
    {
        $roles = [];

        if (isset($_SESSION['auth']['id_user'])) {
            $roles = User::getUserRolesById();
        }

        $roles[] = 'user';

        return $roles;
    }

    public function getActionsPermissions(string $methodName): array
    {
        return $this->actionsPermissions[$methodName] ?? ['user'];
    }
}
