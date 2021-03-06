<?php

use Phinx\Migration\AbstractMigration;

class RenameCountBlogsInCategories extends AbstractMigration
{
    /**
     * Migrate Change.
     */
    public function change(): void
    {
        $table = $this->table('categories');
        $table->renameColumn('count_blogs', 'count_articles')->update();
    }
}
