<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTemporarilyBlockedGroupsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('temporarily_blocked_groups');
        $table->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('group_id', 'integer', ['signed' => false])
            ->addColumn('blocked_until', 'datetime')
            ->addTimestamps()
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('group_id', 'groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();
    }
}
