<?php
/**
 * Created by PhpStorm.
 * User: vadym
 * Date: 20/02/15
 * Time: 19:37
 */

namespace atk4\atk4homepage;

trait Trait_Contitions {


    function removeCondition($field,$kind='where',$system=false,$editable=true){
        // get model field object
        if(!$field instanceof Field){
            $field=$this->getElement($field);
        }
        foreach($this->_dsql()->args[$kind] as $k=>$v){
            if ($v[0]==$this->table.'.'.$field->short_name){
                unset($this->_dsql()->args[$kind][$k]);
            }
        }
        $this->getElement($field->short_name)->system($system)->editable($editable);
    }

    function listConditions(){
        foreach($this->_dsql()->args['where'] as $k=>$v){
            var_dump($v);
        }
    }


}