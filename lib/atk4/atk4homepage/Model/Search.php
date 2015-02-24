<?php

namespace atk4\atk4homepage;

class Model_Search extends \SQL_Model {

    use Trait_SoftDelete, Trait_Contitions;

    public $table = 'search';
    public $related_entities = [];

    function init(){
        parent::init();

        $this->addField('content');
        $this->addField('block_id');
        $this->addField('is_deleted');//->type('boolean');
    }
}