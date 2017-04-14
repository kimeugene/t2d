<?php

use Phinx\Migration\AbstractMigration;

class MessagesTable extends AbstractMigration
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
        $exists = $this->hasTable('messages');
        if (!$exists) {
            $table = $this->table('messages', array('id' => false, 'primary_key' => array('id')));
            $table->addColumn('id', 'string', array('limit' => 36, 'null' => false))
                ->addColumn('user_id', 'string', array('limit' => 36, 'default' => null, 'null' => true))
                ->addColumn('plate_id', 'string', array('limit' => 36))
                ->addColumn('text', 'string', array('limit' => 50))
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')
                ->addColumn('deleted_at', 'datetime', array('default' => null, 'null' => true))
                ->addIndex(array('plate_id'))
                ->create();
        }

    }
}
