<?php

namespace atk4\atk4homepage;

class Model_Language extends \SQL_Table {

    use Trait_RelatedEntities, Trait_Contitions;

    public $table = 'language';
    public $related_entities = [];


    function init(){
        parent::init();

        $this->addField('lang_code')->setValueList($this->app->getConfig('available_languages',['en']));

    }

}