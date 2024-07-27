<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Domain\Models\User;

class AbstractController
{

    protected array $actionsPermissions = [];

    public function getUserRoles(): array
    {
        $roles = [];
        $roles[] = 'user';

        if (isset($_SESSION['id_user'])) {
            $roles = User::getUserRolesById();
        }
        return $roles;
    }

    public function getActionsPermissions(string $methodName): array
    {
        return $this->actionsPermissions[$methodName] ?? [];
    }
}
