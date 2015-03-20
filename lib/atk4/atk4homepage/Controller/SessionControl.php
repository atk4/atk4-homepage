<?php
/**
 * Created by PhpStorm.
 * User: vadym
 * Date: 20/03/15
 * Time: 21:49
 */

namespace atk4\atk4homepage;

class Controller_SessionControl extends \AbstractController {

	function init() {
		parent::init();
		$this->check();
	}

	protected function check() {
		$session = $this->add('atk4\atk4homepage\Model_Session');
		if ($code = $this->tryGetFromURL()) {
			//echo 'from url<hr>';     // <-----------------------------   REMOVE ME
			$no_cookie_yet = true;
		} else if ($code = $this->tryGetFromCookie()) {
			//echo 'from cookie<hr>';  // <-----------------------------   REMOVE ME
			$no_cookie_yet = false;
		} else {
			return false;
		}
		$session->addCondition('access_code',$code)->tryLoadAny();

		if ($session->loaded() && $session->isValid()) {
			Config::getInstance($this->app)->enableFrontendEditingMode();
			Config::getInstance($this->app)->setFrontendEditingCode($code);
			if ($no_cookie_yet) {
				$this->setCookie($code);
				$this->app->js(true)->redirect(null,[
					Config::getInstance($this->app)->getFrontendGetParameterName() => null,
				]);
			}
		} else {
			Config::getInstance($this->app)->disableFrontendEditingMode();
			$this->unsetCookie();
		}

	}

	protected function tryGetFromURL() {
		if ($code = $_GET[Config::getInstance($this->app)->getFrontendGetParameterName()]) {
			return $code;
		}
		return false;
	}

	protected function tryGetFromCookie() {
		if ($code = $_COOKIE[Config::getInstance($this->app)->getFrontendGetParameterName()]) {
			return $code;
		}
		return false;
	}

	protected function setCookie($code) {
		setcookie(
			Config::getInstance($this->app)->getFrontendGetParameterName(), // name
			$code // value
		);
	}

	protected function unsetCookie() {
		if (isset($_COOKIE[Config::getInstance($this->app)->getFrontendGetParameterName()])) {
			unset($_COOKIE[Config::getInstance($this->app)->getFrontendGetParameterName()]);
			setcookie(Config::getInstance($this->app)->getFrontendGetParameterName(), null, -1);
			return true;
		}
		return false;
	}

}