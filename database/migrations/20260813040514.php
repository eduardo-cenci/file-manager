<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class V20260813040514 extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('folders');

        $table->addColumn('user_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('parent_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('parent_id', 'folders', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['user_id', 'parent_id', 'name'], ['unique' => true])
            ->create();
    }
}
