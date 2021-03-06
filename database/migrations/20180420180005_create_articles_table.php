<?php

use Phinx\Migration\AbstractMigration;

class CreateArticlesTable extends AbstractMigration
{
    /**
     * Migrate Change.
     */
    public function change()
    {
        if (! $this->hasTable('articles')) {
            $table = $this->table('articles', ['engine' => config('DB_ENGINE'), 'collation' => config('DB_COLLATION')]);
            $table
                ->addColumn('category_id', 'integer')
                ->addColumn('user_id', 'integer')
                ->addColumn('title', 'string', ['limit' => 50])
                ->addColumn('text', 'text', ['null' => true])
                ->addColumn('tags', 'string', ['limit' => 100])
                ->addColumn('rating', 'integer', ['default' => 0])
                ->addColumn('visits', 'integer', ['default' => 0])
                ->addColumn('count_comments', 'integer', ['default' => 0])
                ->addColumn('created_at', 'integer')
                ->addIndex('category_id')
                ->addIndex('user_id')
                ->addIndex('created_at')
                ->create();
        }
    }
}
