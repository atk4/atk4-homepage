<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 20.01.15
 * Time: 14:41
 */

namespace atk4\atk4homepage;

use \Carbon\Carbon;

trait Trait_DTS {
    function createdDTS() {
        $this->addHook('beforeInsert', function($m,$q){
            $timezone = $this->app->getConfig('timezone',null);
            $q->set('created_dts',  Carbon::now($timezone)->toDateTimeString() );
        });
    }
    function updatedDTS() {
        $this->addHook('beforeSave', function($m){
            $timezone = $this->app->getConfig('timezone',null);
            $m['updated_dts'] = Carbon::now($timezone)->toDateTimeString();
        });
    }
}