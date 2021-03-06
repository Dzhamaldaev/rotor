<?php

use Phinx\Migration\AbstractMigration;

class CreatePhotosTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        if (! $this->hasTable('photos')) {
            $table = $this->table('photos', ['engine' => config('DB_ENGINE'), 'collation' => config('DB_COLLATION')]);
            $table
                ->addColumn('user_id', 'integer')
                ->addColumn('title', 'string', ['limit' => 50])
                ->addColumn('text', 'text', ['null' => true])
                ->addColumn('created_at', 'integer')
                ->addColumn('rating', 'integer', ['default' => 0])
                ->addColumn('closed', 'boolean', ['default' => 0])
                ->addColumn('count_comments', 'integer', ['default' => 0])
                ->addIndex('created_at')
                ->addIndex('user_id')
                ->create();
        }
    }
}
