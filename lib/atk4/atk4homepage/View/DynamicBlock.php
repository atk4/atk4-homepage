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

    public function getForAdmin(){
        $form = $this->add('Form');
        $form->setClass('stacked');
        $form->setModel($this->model,$this->field_list);
        $form->getElement('content')->setCaption($this->model['system_name']);
        $form->addSubmit();

        if($form->isSubmitted()) {
            $form->save();

            $form->js()->univ()->successMessage('Saved')->execute();
        }
    }

    public function getForFrontend(){

        $this->setHtml(nl2br($this->model->get('content')));
    }

}