<?php

use Phinx\Migration\AbstractMigration;

class CreatePostsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        if (! $this->hasTable('posts')) {
            $table = $this->table('posts', ['engine' => config('DB_ENGINE'), 'collation' => config('DB_COLLATION')]);
            $table
                ->addColumn('topic_id', 'integer')
                ->addColumn('user_id', 'integer')
                ->addColumn('text', 'text', ['null' => true])
                ->addColumn('rating', 'integer', ['default' => 0])
                ->addColumn('created_at', 'integer')
                ->addColumn('ip', 'varbinary', ['limit' => 16])
                ->addColumn('brow', 'string', ['limit' => 25])
                ->addColumn('edit_user_id', 'integer', ['null' => true])
                ->addColumn('updated_at', 'integer', ['null' => true])
                ->addIndex(['topic_id', 'created_at'], ['name' => 'topic_time'])
                ->addIndex(['user_id', 'created_at'], ['name' => 'user_time'])
                ->addIndex(['rating', 'created_at'], ['name' => 'rating_time'])
                ->addIndex('created_at');

            $mysql = $this->query('SHOW VARIABLES LIKE "version"')->fetch();

            if (config('DB_ENGINE') === 'MyISAM' || version_compare($mysql['Value'], '5.6.0', '>=')) {
                $table->addIndex('text', ['type' => 'fulltext']);
            }

            $table->create();
        }
    }
}
