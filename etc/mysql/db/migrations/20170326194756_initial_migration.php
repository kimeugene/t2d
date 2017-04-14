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
            $table->addColumn('id', 'string', array('limit' => 36, 'null' => false))
                ->addColumn('email', 'string', array('limit' => 100, 'null' => false, 'default' => ''))
                ->addColumn('confirmed', 'datetime', array('default' => null, 'null' => true))
                ->addColumn('auth_code', 'string', array('limit' => 100))
                ->addColumn('auth_code_ttl', 'integer')
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')
                ->addColumn('deleted_at', 'datetime', array('default' => null, 'null' => true))
                ->addIndex(array('email'))
                ->create();
        }

        $exists = $this->hasTable('plates');
        if (!$exists) {
            $table = $this->table('plates', array('id' => false, 'primary_key' => array('id')));
            $table->addColumn('id', 'string', array('limit' => 36, 'null' => false))
                ->addColumn('user_id', 'string', array('limit' => 36))
                ->addColumn('plate', 'string', array('limit' => 15))
                ->addColumn('state', 'string', array('limit' => 2))
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')
                ->addColumn('deleted_at', 'datetime', array('default' => null, 'null' => true))
                ->addIndex(array('user_id'))
                ->create();
        }

       $exists = $this->hasTable('phones');
        if (!$exists) {
            $table = $this->table('phones', array('id' => false, 'primary_key' => array('id')));
            $table->addColumn('id', 'string', array('limit' => 36, 'null' => false))
                ->addColumn('user_id', 'string', array('limit' => 36))
                ->addColumn('phone', 'string', array('limit' => 15))
                ->addColumn('auth_code', 'string', array('limit' => 100))
                ->addColumn('auth_code_ttl', 'integer')
                ->addColumn('confirmed', 'datetime', array('default' => null, 'null' => true))
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')
                ->addColumn('deleted_at', 'datetime', array('default' => null, 'null' => true))
                ->addIndex(array('user_id'))
                ->create();
        }

    }
}
