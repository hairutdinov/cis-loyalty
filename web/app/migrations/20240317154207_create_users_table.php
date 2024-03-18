<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('username', 'string')
            ->addColumn('password_hash', 'string')
            ->addTimestamps()
            ->create();
    }
}
