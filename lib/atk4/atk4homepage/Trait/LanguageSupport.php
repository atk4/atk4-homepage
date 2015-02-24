<?php

namespace atk4\atk4homepage;

trait Trait_LanguageSupport {
    protected static $SESSION_LANGUAGE_NAME = 'curr_lang_name';

    public function getCurrentLanguage(){
        if(!is_a($this,'App_Web')) throw $this->exception('Trait_LanguageSupport must be used in App_Web only');
        if(!$this->recall($this::$SESSION_LANGUAGE_NAME)){
            $this->memorize($this::$SESSION_LANGUAGE_NAME,$this->getConfig('atk4-home-page/default_language','en'));
        }
        return $this->recall($this::$SESSION_LANGUAGE_NAME);
    }

    public function setCurrentLanguage($lang){
        if(!is_a($this,'App_Web')) throw $this->exception('Trait_LanguageSupport must be used in App_Web only');
//        if(!in_array($lang)) //TODO use array_column

        $this->memorize($this::$SESSION_LANGUAGE_NAME,$lang);
        return $this->recall($this::$SESSION_LANGUAGE_NAME);
    }
}