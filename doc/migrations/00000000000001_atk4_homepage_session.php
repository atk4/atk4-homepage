<?php

use Phinx\Migration\AbstractMigration;

class ATK4HomepageSession extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {

        $this->table('atk4_hp_session')
            ->addColumn('type', 'string',['limit'=>'255','null'=>false])
            ->addColumn('user_id', 'integer',['limit'=>'11','null'=>false])
            ->addColumn('access_code', 'string',['limit'=>'255','null'=>false])
            ->addColumn('created_dts', 'datetime',['null'=>false])
            ->addColumn('valid_seconds', 'integer',['null'=>false])
            ->create();

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('atk4_hp_session')->drop();
    }
}