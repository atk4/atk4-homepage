<?php

namespace atk4\atk4homepage;

use atk4\atk4homepage\Config;

class Model_Session extends \SQL_Model {

    use Trait_DTS;

    public $table = 'atk4_hp_session';

	const TYPE_EDIT_ON_FRONTEND = 'edit_frontend';

	protected static $available_types = [
		self::TYPE_EDIT_ON_FRONTEND,
	];


    function init(){
        parent::init();

        //$this->debug();
		$this->addField('type')->setValueList($this->getAvailableTypes())->required();
		$this->addField('user_id')->required();
		$this->addField('access_code')->required();
		$this->addField('created_dts')->required();
        $this->addField('valid_seconds')->required();

        $this->addHooks();
    }


	private function addHooks(){
		$this->createdDTS();
	}

    public function generate($user_id,$type) {
        $this
            ->set('type',$type)
            ->set('user_id',$user_id)
            ->set('access_code',uniqid('atk4_hp_'))
        ;
        return $this;
    }

    public function isValid() {
        $now_timestamp = time();
        $created_timestamp = strtotime($this['created_dts']);
        return ( ($now_timestamp - $created_timestamp) < $this['valid_seconds'])? true: false;
    }


    /**
     * Helper to set perion in seconds for some quantity of days.
     *
     * @param $count
     * @return $this
     */
    public function setValidForDays($count) {
        $seconds = 60*60*24*$count;
        $this->set('valid_seconds',$seconds);
        return $this;
    }


	/**
	 * Helper to set perion in seconds for some quantity of hours.
	 *
	 * @param $count
	 * @return $this
	 */
	public function setValidForHours($count) {
		$seconds = 60*60*$count;
		$this->set('valid_seconds',$seconds);
		return $this;
	}


    function getAvailableTypes($name=null) {
		$total_array =  array_merge(
			self::$available_types,
			static::$available_types
		);
		if ($name) {
			return $total_array[$name];
		}
		return $total_array;
    }


    public function getUserModel() {
		if (!$this->loaded()) throw $this->exception('Model must be loaded');
        return $this->add( Config::getInstance($this->app)->getFrontendEditModel() )->addCondition( 'id', $this['user_id'] );
    }
}