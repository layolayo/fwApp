<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupTables extends AbstractMigration
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
        $groups = $this->table("groups");
        $groups->addColumn("name", 'string', ['limit' => 255, 'null' => false])
            ->save();

        $user_groups = $this->table("user_groups");
        $user_groups->addColumn("user_email", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("group_id", 'integer', ['null' => false])
            ->addForeignKey('user_email', 'facilitator', 'email', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('group_id', 'groups', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->save();

        $group_question_set = $this->table("group_question_set");
        $group_question_set->addColumn("question_set_id", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("group_id", 'integer', ['null' => false])
            ->addForeignKey('question_set_id', 'question_set', 'ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('group_id', 'groups', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->save();
    }
}
