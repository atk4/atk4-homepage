<?php
/**
 * Created by PhpStorm.
 * User: vadym
 * Date: 24/02/15
 * Time: 19:51
 */

namespace atk4\atk4homepage;

trait Trait_SoftDelete {


//    public $related_entities;
//    public $soft_deleted_id = null;
//    public $hard_deleted_id = null;


    /**
     * Cascade restoring of related entities
     * @param null $id
     * @return $this
     * @throws AbstractObject
     * @throws BaseException
     */
    public function restore($id=null){
        if(!is_null($id))$this->load($id);
        if(!$this->loaded())throw $this->exception('Unable to determine which record to restore','atk4\atk4homepage\NotLoadedModel');

        if(!isset($this->related_entities)) throw $this->exception('Related entities are not specified', 'NoRelatedEntities');

        foreach($this->related_entities as $related_entity){
            $model_arr = $this
                ->add('Model_'.$related_entity[0])
                ->addCondition($related_entity[1]['field'], $this->id)
                ->deleted();
            foreach($model_arr as $record){
                $record->restore();
            }
        }

        $restored_id = $this->id;
        $this->removeCondition('is_deleted');
        $this['is_deleted'] = 0;
        $this->saveAndUnload();
        $this->load($restored_id);
        return $this;
    }




    public function deleted($yes=true) {

        if(is_bool($yes)){
            $yes = $yes?1:0;
        }

        $this->addCondition('is_deleted',$yes);
        return $this;
    }




    /**
     * cascade soft deleting of related entities
     * @param null $id
     * @return $this
     * @throws AbstractObject
     * @throws BaseException
     */
    function delete($id=null){
        if(!is_null($id))$this->load($id);
        if(!$this->loaded())throw $this->exception('Unable to determine which record to delete','atk4\atk4homepage\NotLoadedModel');

        //Delete related entities
        if(!isset($this->related_entities)) throw $this->exception('Related entities are not specified', 'NoRelatedEntities');
        foreach($this->related_entities as $related_entity){
            $model_arr = $this
                ->add('Model_'.$related_entity[0])
                ->addCondition($related_entity[1]['field'], $this->id)
                ->deleted(false);
            foreach($model_arr as $record){
                $record->delete();
            }
        }

        $this->soft_deleted_id = $this->id;
        //Delete current entity
        $this->hook('beforeDelete',array($this->_dsql()));
        $this->set('is_deleted',1)->saveAndUnload();
        $this->hook('afterDelete');

        return $this;
    }

    /**
     * Cascade hard deleting of related entities
     * @param null $id
     * @return $this
     * @throws AbstractObject
     * @throws BaseException
     */
    function forceDelete($id=null){
        //Delete related entities
        if(!isset($this->related_entities)) throw $this->exception('Related entities are not specified', 'NoRelatedEntities');
        foreach($this->related_entities as $related_entity){
            $model = $this
                ->add('Model_'.$related_entity[0])
                ->addCondition($related_entity[1]['field'], $this->id);
            foreach($model as $record){
                if($related_entity[1]['type'] == 'soft'){
                    $record->delete();
                }else{
                    $record->forceDelete();
                }
            }

        }

        if($id){
            $this->hard_deleted_id = $id;
        }else{
            $this->hard_deleted_id = $this->id;
        }
        //Delete current entity
        return parent::delete($id);
    }

}