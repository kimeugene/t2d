<?php

use Phinx\Migration\AbstractMigration;

class InitialMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $exists = $this->hasTable('users');
        if (!$exists) {
            $table = $this->table('users', array('id' => false, 'primary_key' => array('id')));
            $table->addColumn('id', 'string', array('limit' => 36))
                ->addColumn('email', 'string', array('limit' => 100))
                ->addColumn('auth_code', 'string', array('limit' => 100))
                ->addColumn('auth_code_ttl', 'integer')
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')
                ->create();
        }

    }
}
