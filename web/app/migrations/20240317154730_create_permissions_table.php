<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePermissionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('permissions');
        $table->addColumn('name', 'string')
            ->create();
    }
}
