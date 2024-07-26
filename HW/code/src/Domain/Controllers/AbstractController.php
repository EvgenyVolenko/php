<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Domain\Models\User;

class AbstractController
{

    protected array $actionsPermissions = [];

    public function getUserRoles(): array
    {
        $roles = [];

        if (isset($_SESSION['id_user'])) {
            $roles = User::getUserRolesById();
        } else {
            $roles[] = 'user';
        }

        return $roles;
    }

    public function getActionsPermissions(string $methodName): array
    {
        return $this->actionsPermissions[$methodName] ?? [];
    }
}
