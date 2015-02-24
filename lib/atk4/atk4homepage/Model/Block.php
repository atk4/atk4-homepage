<?php

namespace atk4\atk4homepage;

class Model_Block extends \SQL_Model {

    use Trait_DTS, Trait_SoftDelete, Trait_Contitions;

    public $table = 'block';
    public $related_entities = [];

    function init(){
        parent::init();

        $this->addField('system_name');
        $this->addField('type');
        $this->addField('content')->type('text');
        $this->addField('page_id');
        $this->addField('language');
        $this->addField('order');
        $this->addField('is_deleted');//->type('boolean');

        $this->addHooks();
    }

    private function addHooks(){
        $this->createdDTS();

        $this->addHook('beforeSave', function($m){
            //TODO Save content to the search table
        });
    }

    public function withCurrentLanguage(){
        $this->addCondition('language',$this->app->getCurrentLanguage());
        return $this;
    }

    public function getPage() {
        return $this->add('Model_Page')->deleted($this['is_deleted']);
    }
}