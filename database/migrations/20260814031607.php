<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class V20260814031607 extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('files');

        $table->addColumn('user_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('folder_id', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('storage_name', 'string', ['limit' => 255])
            ->addColumn('mime_type', 'string', ['limit' => 100])
            ->addColumn('extension', 'string', ['limit' => 10])
            ->addColumn('size', 'integer', ['limit' => 100])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('folder_id', 'folders', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['storage_name'], ['unique' => true])
            ->create();
    }
}
