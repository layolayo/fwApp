<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddInitialTables extends AbstractMigration
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
        $facilitator = $this->table("facilitator", ['id' => false]);
        $facilitator->addColumn("email", 'string', ['limit' => 255, 'null' => false])
            ->addColumn('userpassword', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('disableuser', 'boolean', ['null' => true])
            ->addColumn('superuser', 'boolean', ['null' => true])
            ->changePrimaryKey("email")
            ->save();

        $question = $this->table("question", ['id' => false]);
        $question->addColumn("ID", 'string', ['limit' => 255, 'null' => false])
            ->addColumn('scaffold', 'string', ['limit' => 1000, 'null' => true])
            ->addColumn('details', 'string', ['limit' => 1000, 'null' => true])
            ->addColumn('question', 'string', ['limit' => 1000, 'null' => true])
            ->changePrimaryKey("ID")
            ->save();

        $type = $this->table("type", ['id' => false]);
        $type->addColumn("title", 'string', ['limit' => 255, 'null' => false])
            ->changePrimaryKey("title")
            ->save();

        $token = $this->table("token", ['id' => false]);
        $token->addColumn("ID", 'string', ['limit' => 100, 'null' => false])
            ->addColumn('used', 'boolean', ['null' => true])
            ->changePrimaryKey("ID")
            ->save();

        $specialism = $this->table("specialism", ['id' => false]);
        $specialism->addColumn("title", 'string', ['limit' => 255, 'null' => false])
            ->changePrimaryKey("title")
            ->save();

        $phase_title = $this->table("phase_title", ['id' => false]);
        $phase_title->addColumn("ID", 'integer', ['null' => false])
            ->addColumn("title", 'string', ['limit' => 255, 'null' => true])
            ->changePrimaryKey("ID")
            ->save();

        $question_set = $this->table("question_set", ['id' => false]);
        $question_set->addColumn("ID", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("specialism", 'string', ['limit' => 1000, 'null' => true])
            ->addColumn("type", 'string', ['limit' => 1000, 'null' => true])
            ->addColumn("frequency", 'integer', ['null' => true])
            ->addColumn("acknowledgements", 'string', ['limit' => 1000, 'null' => true])
            ->addColumn("academicSupport", 'string', ['limit' => 1000, 'null' => true])
            ->addColumn("title", 'string', ['limit' => 1000, 'null' => true])
            ->addColumn("preparation", 'string', ['limit' => 1000, 'null' => true])
            ->addColumn("random", 'boolean', ['null' => true])
            ->addColumn("background", 'string', ['limit' => 1000, 'null' => true])
            ->addForeignKey('specialism', 'specialism', 'title', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('type', 'type', 'title', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->changePrimaryKey("ID")
            ->save();

        $category = $this->table("category", ['id' => false]);
        $category->addColumn("ID", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("category", 'string', ['limit' => 255, 'null' => true])
            ->addColumn("questionSetID", 'string', ['limit' => 255, 'null' => true])
            ->addForeignKey('questionSetID', 'question_set', 'ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->changePrimaryKey("ID")
            ->save();

        $phase = $this->table("phase", ['id' => false]);
        $phase->addColumn("title", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("questionSetID", 'string', ['limit' => 255, 'null' => false, 'unique' => true])
            ->addForeignKey('questionSetID', 'question_set', 'ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
//            ->addForeignKey('title', 'phase_title', 'title', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->changePrimaryKey(["title", "questionSetID"])
            ->save();

        $ask = $this->table("ask", ['id' => false]);
        $ask->addColumn("question", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("qOrder", 'integer', ['null' => true])
            ->addColumn("questionSet", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("repeats", 'integer', ['null' => true])
            ->addForeignKey('questionSet', 'question_set', 'ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('question', 'question', 'ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->changePrimaryKey(["question", "questionSet"])
            ->save();

        $amend_qs = $this->table("amend_qs", ['id' => false]);
        $amend_qs->addColumn("email", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("questionSetID", 'string', ['limit' => 255, 'null' => false])
            ->addColumn("deletePermission", 'boolean', ['null' => true])
            ->addForeignKey('email', 'facilitator', 'email', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('questionSetID', 'question_set', 'ID', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->changePrimaryKey(["email", "questionSetID"])
            ->save();
    }
}
