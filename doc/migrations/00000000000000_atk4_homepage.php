<?php

use Phinx\Migration\AbstractMigration;

class ATK4Homepage extends AbstractMigration
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

        $this->table('page')
            ->addColumn('name', 'string',['limit'=>'255','null'=>false])
            ->addColumn('type', 'string',['limit'=>'255','null'=>true,'default'=>null])
            ->addColumn('hash_url', 'string',['limit'=>'255','null'=>false])
            ->addColumn('order', 'integer',['limit'=>'11','null'=>true,'default'=>null])
            ->addColumn('created_dts', 'datetime',['null'=>false])
            ->addColumn('is_deleted', 'integer',['null'=>false,'default'=>'0'])
            ->addColumn('page_id', 'integer',['limit'=>'11','null'=>true])
            ->create();

        $this->table('page_translation')
            ->addColumn('page_id', 'integer',['limit'=>'11','null'=>false])
            ->addColumn('language', 'string',['limit'=>'25','null'=>false])
            ->addColumn('meta_title', 'string',['limit'=>'255','null'=>false])
            ->addColumn('meta_keywords', 'string',['limit'=>'255','null'=>true,'default'=>null])
            ->addColumn('meta_description', 'string',['limit'=>'255','null'=>true,'default'=>null])
            ->create();

        $this->table('block')
            ->addColumn('system_name', 'string',['limit'=>'255','null'=>false])
            ->addColumn('type', 'string',['limit'=>'255','null'=>false])
            ->addColumn('content', 'text',['null'=>true,'default'=>null])
            ->addColumn('page_id', 'integer',['limit'=>'11','null'=>false])
            ->addColumn('order', 'integer',['limit'=>'11','null'=>true,'default'=>null])
            ->addColumn('language', 'integer',['limit'=>'11','null'=>false])
            ->addColumn('created_dts', 'datetime',['null'=>false])
            ->addColumn('is_deleted', 'integer',['null'=>false,'default'=>'0'])
            ->create();

        $this->table('language')
            ->addColumn('lang_code', 'string',['limit'=>'255','null'=>false])
            ->create();

        $this->table('search')
            ->addColumn('content', 'string',['limit'=>'255','null'=>false])
            ->addColumn('block_id', 'integer',['limit'=>'11','null'=>false])
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('page')->drop();
        $this->table('page_translation')->drop();
        $this->table('block')->drop();
        $this->table('language')->drop();
        $this->table('search')->drop();
    }
}