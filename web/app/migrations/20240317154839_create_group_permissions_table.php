<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupPermissionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('group_permissions');
        $table->addColumn('group_id', 'integer', ['signed' => false])
            ->addColumn('permission_id', 'integer', ['signed' => false])
            ->addTimestamps()
            ->addForeignKey('group_id', 'groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->addForeignKey('permission_id', 'permissions', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();
    }
}
