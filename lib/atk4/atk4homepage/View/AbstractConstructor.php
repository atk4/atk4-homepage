<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 11.02.15
 * Time: 18:32
 */

namespace atk4\atk4homepage;

abstract class View_AbstractConstructor extends \View {
    public $template_path;
    public $field_list = ['content'];
    public $app_type;
    public function get(){
        if($this->app_type == 'admin'){
            $this->getForAdmin();
        }else{
            $this->getForFrontend();
        }
    }
    function defaultTemplate(){
        if($this->app_type != 'admin' && !is_null($this->template_path)){
            return [$this->template_path];
        }
        return parent::defaultTemplate();
    }
}