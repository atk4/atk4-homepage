<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 11.02.15
 * Time: 18:05
 */

namespace atk4\atk4homepage;

class View_DynamicBlock extends View_AbstractConstructor {

    public $template_path = 'htmlelement';

	function init() {
		parent::init();
		Initiator::getInstance()->addJs($this->app);
	}

    public function getForAdmin(){
        $form = $this->add('Form');
        $form->setClass('stacked');

        if ($this->isMarkdownRequired($this->model['type'])) {
            $form->model = $this->model;
            $form->addField('atk4\markdown\Form_Field_Markdown','content')->set($this->model->get('content'));
        } else {
            $form->setModel($this->model,$this->field_list);
        }
        $form->getElement('content')->setCaption($this->model['system_name']);
        $form->addSubmit();

        if($form->isSubmitted()) {
            $form->save();

            $form->js()->univ()->successMessage('Saved')->execute();
        }
    }

    public function getForFrontend(){

		//var_dump(Config::getInstance($this->app)->isFrontendEditingMode()); echo '<hr>';


        if ($this->isMarkdownRequired($this->model['type'])) {
            $Parsedown = new \Parsedown();
            $this->setHTML( $Parsedown->text( $this->model->get('content') ) );
        } else {
            $this->setHtml(nl2br($this->model->get('content')));
        }

		if (Config::getInstance($this->app)->isFrontendEditingMode()) {
			$this->js(true)->atk4HomePage()->setEditableMode(
				$this->name,
				$this->app->url(null,[
					'access_code' => Config::getInstance($this->app)->getFrontendEditingCode(),
					'block_id' => $this->model->id,
					'cut_object' => $this->name,
					'edit' => true
				])
			);
		}

    }



    /**
     * Block can have markdown content.
     *
     * @param $block_type
     * @return mixed
     */
    protected function isMarkdownRequired($block_type) {
        return $this->app->getConfig('atk4-home-page/block_types/'.$block_type.'/markdown',false);
    }

}