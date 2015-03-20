<?php
/**
 * Created by PhpStorm.
 * User: vadym
 * Date: 12/03/15
 * Time: 20:22
 */

namespace atk4\atk4homepage;

class Config {


    private $app;


	protected $frontend_editor_model = 'Model_Admin';
	public function getFrontendEditModel() {
		return $this->frontend_editor_model;
	}
	public function setFrontendEditModel($model) {
		$this->frontend_editor_model = $model;
		return $this;
	}


	protected $frontend_editor_get_parameter_name = 'code';
	public function getFrontendGetParameterName() {
		return $this->frontend_editor_get_parameter_name;
	}
	public function setFrontendGetParameterName($name) {
		$this->frontend_editor_get_parameter_name = $name;
		return $this;
	}


	protected $frontend_editing_mode = false;
	public function isFrontendEditingMode() {
		return $this->frontend_editing_mode;
	}
	public function enableFrontendEditingMode() {
		$this->frontend_editing_mode = true;
		return $this;
	}
	public function disableFrontendEditingMode() {
		$this->frontend_editing_mode = false;
		return $this;
	}


	protected $frontend_editing_code = false;
	public function getFrontendEditingCode() {
		return $this->frontend_editing_code;
	}
	public function setFrontendEditingCode($code) {
		$this->frontend_editing_code = $code;
		return $this;
	}


    /* --------------------------------------------------
     |
     |
     |                Singleton stuff
     |
     |
    */

    private static $instance;
    public static function getInstance($app) {
        if (!self::$instance) {
            self::$instance = new Config();
        }
        self::$instance->app = $app;
        return self::$instance;
    }
    protected function __construct() {}

}