<?php

use Phinx\Migration\AbstractMigration;

class CreateTopicsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        if (! $this->hasTable('topics')) {
            $table = $this->table('topics', ['engine' => config('DB_ENGINE'), 'collation' => config('DB_COLLATION')]);
            $table
                ->addColumn('forum_id', 'integer')
                ->addColumn('title', 'string', ['limit' => 50])
                ->addColumn('user_id', 'integer')
                ->addColumn('closed', 'boolean', ['default' => 0])
                ->addColumn('locked', 'boolean', ['default' => 0])
                ->addColumn('moderators', 'string', ['null' => true])
                ->addColumn('note', 'string', ['null' => true])
                ->addColumn('updated_at', 'integer', ['null' => true])
                ->addColumn('count_posts', 'integer')
                ->addColumn('visits', 'integer', ['default' => 0])
                ->addColumn('last_post_id', 'integer', ['null' => true])
                ->addColumn('created_at', 'integer')
                ->addIndex(['count_posts', 'updated_at'], ['name' => 'count_posts_time'])
                ->addIndex(['user_id', 'updated_at'], ['name' => 'user_time'])
                ->addIndex(['forum_id', 'locked', 'updated_at'], ['name' => 'forum_time'])
                ->addIndex('updated_at');

            $mysql = $this->query('SHOW VARIABLES LIKE "version"')->fetch();

            if (config('DB_ENGINE') === 'MyISAM' || version_compare($mysql['Value'], '5.6.0', '>=')) {
                $table->addIndex('title', ['type' => 'fulltext']);
            }

            $table->create();
        }
    }
}
