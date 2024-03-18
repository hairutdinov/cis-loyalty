<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserGroupsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('user_groups');
        $table->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('group_id', 'integer', ['signed' => false])
            ->addTimestamps()
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('group_id', 'groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();
    }
}
