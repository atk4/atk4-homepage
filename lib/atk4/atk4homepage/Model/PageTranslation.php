<?php

namespace atk4\atk4homepage;

class Model_PageTranslation extends \SQL_Model {
    public $table = 'page_translation';
    public static $meta_fields = ['meta_title','meta_keywords','meta_description'];
    public static $meta_fields_for_group = ['meta_title'];

    function init(){
        parent::init();

        $this->addField('page_id');
        $this->addField('language');
        $this->addField('meta_title');
        $this->addField('meta_keywords');
        $this->addField('meta_description');
    }
    public function getMetaFields() {
        return ($this['type'] == '') ? self::$meta_fields_for_group : self::$meta_fields;
    }
}