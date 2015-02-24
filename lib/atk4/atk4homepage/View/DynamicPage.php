<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 11.02.15
 * Time: 17:56
 */

namespace atk4\atk4homepage;

class View_DynamicPage extends View_AbstractConstructor {

    public     $template_path = 'page';
    private    $blocks;
    protected  $necropolis = [];

    function init(){
        parent::init();
    }

    protected function getForAdmin(){
        $this->addBlocks($this,'admin');
        $this->addNecropolis($this);
    }

    protected function addNecropolis(\AbstractView $v){
        if(!$this->necropolis) return;
        $blocks = $this->add('atk4\atk4homepage\Model_Block')
            ->addCondition('page_id',$this->model->id)
            ->addCondition('id','not in',$this->necropolis)
            ->addCondition('language',$this->app->getCurrentLanguage());

        $v->add('H2')->set('Necropolis');
        $crud = $v->add('CRUD',['allow_add'=>false]);
        $crud->setModel($blocks);
    }

    protected function addBlocks(\AbstractView $v=null, $app_type = 'frontend'){
        if (!$v) $v = $this;
        if($this->model['type']){
            $this->blocks = $this->app->getConfig('atk4-home-page/page_types/'.$this->model['type'].'/blocks',[]);

            foreach($this->blocks as $sys_name=>$type){
                $this->addBlock($this->model->id, $sys_name, $type, $app_type,$v);
            }
        }
    }

    /**
     * @param $page_id
     * @param $sys_name
     * @param $type - The block's type from config
     * @param $app_type
     * @param AbstractView $v
     * @throws AbstractObject
     * @throws BaseException
     */
    private function addBlock($page_id, $sys_name, $type, $app_type, \AbstractView $v){
        $block = $this->add('atk4\atk4homepage\Model_Block')//->debug()
            ->addCondition('type',$type)
            ->addCondition('page_id',$page_id)
            ->addCondition('system_name',$sys_name)
            ->addCondition('language',$this->app->getCurrentLanguage())
            ->tryLoadAny();

        if(!$block->loaded()){
            $block->save();
        }
        $this->necropolis[] = $block->id;

        $view = $v->add('atk4\atk4homepage\View_DynamicBlock',['app_type'=>$app_type]);
        $view->setModel($block);
        $view->get();
    }
    protected function getForFrontend(){
        $blocks = $this->model->getBlocks()->withCurrentLanguage();

        $error_messages = [];

        foreach($blocks as $block){

            if ($this->template->hasTag($block['system_name'])) {
                $v = $this->add('atk4\atk4homepage\View_DynamicBlock',[
                    'template_path'=>$this->app->getConfig('atk4-home-page/block_types/'.$block['type'].'/template')
                ],$block['system_name']);
                $v->setModel($block);
                $v->get();
            }else{
                $error_messages[] = 'There is no tag '.$block['system_name'].' in template.';
            }
        }
        if(count($error_messages)){
            $this->js(true)->univ()->alert(implode("\n",$error_messages));
        }
    }
}