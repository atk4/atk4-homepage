<?php
/**
 * Created by PhpStorm.
 * User: vadym
 * Date: 20/02/15
 * Time: 19:37
 */

namespace atk4\atk4homepage;

trait Trait_ATK4HomePage {


    function initAtk4HomePage() {

    }



    public $atk4_home_page_real_page;

    /**
     * This will overwrite default App_Frontend::pageNotFound() method and start to search for dynamic pages
     *
     * @param $e
     */
    public function pageNotFound($e){

        $page_model = $this->add('atk4\atk4homepage\Model_Page');
        $page_model->tryLoadBy('hash_url',$this->app->page);
        if(!$page_model->loaded())
            throw $e->addMoreInfo('ATK4HomePage says','No page with url-hash ['.$this->app->page.'] found in database.');


        $layout = $this->layout ?: $this;
        $this->page_object = $layout->add('atk4\atk4homepage\Page_Dynamic',
            [
                'name'  => $this->app->page,
                'model' => $page_model,
            ],
            'Content'
        );
        
    }


}