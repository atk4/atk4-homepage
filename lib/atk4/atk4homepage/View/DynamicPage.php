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
    protected  $living = [];

    function init(){
        parent::init();
    }

    protected function getForAdmin(){
        $this->addBlocks($this,'admin');
        $this->addNecropolis($this);
    }

    protected function addNecropolis(\AbstractView $v){
        //if(!$this->living) return;
        $blocks = $this->add('atk4\atk4homepage\Model_Block')
            ->addCondition('page_id',$this->model->id)
            ->addCondition('is_deleted',false)
            //->addCondition('id','not in',$this->living)
            ->addCondition('language',$this->app->getCurrentLanguage());

        if($this->living) $blocks->addCondition('id','not in',$this->living);

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
        $this->living[] = $block->id;

		$this->_getBlock( $block, $v, $app_type);

    }
    protected function getForFrontend(){

        $blocks = $this->model->getBlocks()->withCurrentLanguage();

        $error_messages = [];

        foreach($blocks as $block){
            if ($this->template->hasTag($block['system_name'])) {
				$this->_getBlock( $block, $this, 'frontend');
            } else {
                $error_messages[] = 'There is no tag '.$block['system_name'].' in template.';
            }
        }
        if(count($error_messages)){
            $this->js(true)->univ()->alert(implode("\n",$error_messages));
        }

    }

	protected function _getBlock(Model_Block $model, \AbstractView $v, $app_type='frontend') {

		if (Config::getInstance($this->app)->isFrontendEditingMode() && $_GET['edit']==1) {
			$this->app->stickyGet('edit');
			$this->app->stickyGet('access_code');
			$app_type = 'admin';
		}

		if ($app_type == 'admin') {
			$view = $v->add('atk4\atk4homepage\View_DynamicBlock',['app_type'=>$app_type]);
		} else {
			$view = $this->add('atk4\atk4homepage\View_DynamicBlock',[
				'template_path'=>$this->app->getConfig('atk4-home-page/block_types/'.$model['type'].'/template')
			],$model['system_name']);
		}

		$view->setModel($model);
		$view->get();

	}
}