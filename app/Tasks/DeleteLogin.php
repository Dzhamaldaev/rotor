<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Models\Login;
use Crontask\Tasks\Task;

class DeleteLogin extends Task
{
    /**
     * Удаляет старые записи истории авторизаций
     */
    public function run()
    {
        Login::query()
            ->where('created_at', '<', strtotime('-3 month', SITETIME))
            ->delete();
    }
}
