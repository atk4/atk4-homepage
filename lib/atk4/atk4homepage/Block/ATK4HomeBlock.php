<?php
/**
 * Created by PhpStorm.
 * User: vadym
 * Date: 20/02/15
 * Time: 21:43
 */

namespace atk4\atk4homepage;

class Block_ATK4HomeBlock extends View_AbstractConstructor {
    public $template_path = 'htmlelement';
    public function getForAdmin(){
        $form = $this->add('Form');
        $form->setClass('stacked');
        $form->setModel($this->model,$this->field_list);
        $form->getElement('content')->setCaption($this->model['system_name']);
        $form->addSubmit();

        if($form->isSubmitted()) $form->save();
    }
    public function getForFrontend(){

        $this->setHtml(nl2br($this->model->get('content')));
    }

}