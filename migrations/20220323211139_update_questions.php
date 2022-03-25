<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateQuestions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table("question")
            ->addColumn("audio_details", 'string', ['limit' => 255, 'null' => true])
            ->addColumn("image", 'string', ['limit' => 255, 'null' => true])
            ->addColumn("image_alttext", 'string', ['limit' => 255, 'null' => true])
            ->update();
    }
}
