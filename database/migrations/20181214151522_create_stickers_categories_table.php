<?php

use Phinx\Migration\AbstractMigration;

class CreateStickersCategoriesTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change(): void
    {
        $table = $this->table('stickers_categories', ['engine' => config('DB_ENGINE'), 'collation' => config('DB_COLLATION')]);
        $table
            ->addColumn('name', 'string', ['limit' => 50])
            ->addColumn('updated_at', 'integer', ['null' => true])
            ->addColumn('created_at', 'integer')
            ->create();
    }
}
