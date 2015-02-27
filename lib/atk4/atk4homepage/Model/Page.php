<?php

namespace atk4\atk4homepage;

class Model_Page extends \SQL_Model {

    use Trait_DTS, Trait_RelatedEntities, Trait_SoftDelete, Trait_Contitions;

    public $table = 'page';
    public $related_entities = [
        ['atk4\atk4homepage\Model_Block', ['type'=>'hard', 'field'=>'page_id']],
    ];

    protected $available_types = [];

    public static $edit_in_form = ['name','type','page_id'];
    public static $show_in_grid = ['name','type'];


    function init(){
        parent::init();

        //$this->debug();
        $this->addField('name')->required();
        $this->addField('type')->setValueList($this->getAvailableTypes());
        $this->addField('created_dts');
        $this->addField('hash_url');
        $this->addField('order');
        $this->addField('is_deleted');//->type('boolean');

        $this->hasOne('atk4\atk4homepage\Page','page_id','name');

        $this->setOrder('order');

        $this->addHooks();
    }

    function getAvailableTypes(){
        if(!count($this->available_types)){
            $this->available_types[''] = '<Group of pages>';
            $pages = $this->app->getConfig('atk4-home-page/page_types',[]);
            foreach($pages as $name=>$page){
                $this->available_types[$name] = $page['descr'];
            }
        }
        return $this->available_types;
    }



    private function addHooks(){
        $this->createdDTS();

        $this->addHook('beforeInsert', function($m,$q){
            if($m['type']) $q->set('hash_url', $this->generateUrlHash($m['name']));
        });
    }


    public function getTranslations($lang = null){
        if(!$this->loaded()) throw $this->exception(get_class($this).' MUST be loaded','atk4\atk4homepage\NotLoadedModel');
        $trans = $this->add('atk4\atk4homepage\Model_PageTranslation');
        $trans->addCondition('page_id',$this->id);
        if($lang === true){
            $trans->addCondition('language',$this->app->getCurrentLanguage());
        }elseif(is_string($lang)){
            $trans->addCondition('language',$lang);
        }
        return $trans;
    }

    /**
     * Lang argument must be true for current language or string with valid language name
     *
     * @param $lang - string|true
     * @return Model_PageTranslation
     * @throws Exception_UnsupportedType
     */
    public function getTranslation($lang){
        if($lang === false) throw $this->exception('This argument is not supported','atk4\atk4homepage\UnsupportedType');
        $m = $this->getTranslations($lang)->tryLoadAny();
        if(!$m->loaded()){
            $m->set('meta_title',$this['name'] .' - '. $this->app->getCurrentLanguage())->save();
        }
        return $m;
    }

    public function getTemplatePath(){
        if(!$this->loaded()) throw $this->exception(get_class($this).' MUST be loaded','\atk4\atk4homepage\NotLoadedModel');

        if(!$this['type']) return false;

        return $this->app->getConfig('atk4-home-page/page_types/'.$this['type'].'/template',false);
    }

    public function getForMenu() {
        $this->addExpression('url_first_child',function($m){
            $f = $m->add('Model_Page',['table_alias'=>'p2'])->addCondition('page_id',$m->getElement('id'));
            $f->setLimit(1);
            return $f->fieldQuery('hash_url');
        });
        return $this;
    }

    public function getTop(){
        $this->addCondition('page_id',null);
        $this->addCondition('is_deleted','0');
        return $this;
    }

    public function getSiblings() {
        if(!$this->loaded()) throw $this->exception(get_class($this).' MUST be loaded','atk4\atk4homepage\NotLoadedModel');
        if($this['page_id']) {
            $p = $this->add('Model_Page');
            $p->addCondition('page_id',$this['page_id']);
            return $p;
        } else {
            return false;
        }
    }

    public function hasChildren() {
        if(!$this->loaded()) throw $this->exception(get_class($this).' MUST be loaded','atk4\atk4homepage\NotLoadedModel');
        if(!$this['type']) {
            $p = $this->add('Model_Page');
            $p->addCondition('page_id',$this['id']);
            $p->addCondition('is_deleted','0');
            if(count($p->getRows())) return true;
            return false;
        } else {
            return false;
        }
    }

    public function create($data){
        if($this->loaded()) throw $this->exception(get_class($this).' MUST NOT be loaded','atk4\atk4homepage\LoadedModel');

        //validation
        $fields_errors = [];
        //TODO
        //Check existence
        if(!isset($data['title']) || !$data['title']) $fields_errors['title'] = $this->exception('Page title is empty','atk4\atk4homepage\NoTitle');
        if(!isset($data['type']) || !$data['type']) $fields_errors['type'] = $this->exception('Page type is empty','atk4\atk4homepage\NoType');
        if(!isset($data['has_content'])) $fields_errors['has_content'] = $this->exception('Page has content value is empty','atk4\atk4homepage\NoHasContent');
        if(!in_array($data['type'],self::$available_types)) $fields_errors['type'] = $this->exception('Incorrect Page Type','atk4\atk4homepage\UnsupportedType');
        //Check types
        if(!is_string($data['title'])) $fields_errors['title'] = $this->exception('Incorrect value type','atk4\atk4homepage\IncorrectValueType');
        if(!is_int($data['has_content'])) $fields_errors['has_content'] = $this->exception('Incorrect value type','atk4\atk4homepage\IncorrectValueType');

        if($fields_errors) throw $this->exception('Invalid Field','atk4\atk4homepage\InvalidField')->setArray($fields_errors);

        try{
            $this->set($data)->save();
        }catch (Exception $e){
            throw $this->exception('Can not create new Page '.$e->getMessage(),'atk4\atk4homepage\CanNotSave');
        }
        return $this;
    }



    public function generateUrlHash($name) {
        $str = strtolower($name);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");

        return $str;
    }

    public function getSubPages(){
        if(!$this->loaded()) throw $this->exception(get_class($this).' MUST be loaded','atk4\atk4homepage\NotLoadedModel');
        $m = $this->add(get_class($this));
        $m->addCondition('page_id',$this->id)->deleted($this['is_deleted']);
        return $m;
    }

    public function getBlocks($as_array=false,$limit=false,$offset=0,$order=false,$desc=true) {
        $bc = $this->add('atk4/atk4homepage/Model_Block')->deleted($this['is_deleted'])->addCondition('page_id',$this->id);
        return $this->prepareRelated($bc,$as_array,$limit,$offset,$order,$desc);
    }
}