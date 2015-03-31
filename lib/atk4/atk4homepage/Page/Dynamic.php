<?php
/**
 * Created by PhpStorm.
 * User: vadym
 * Date: 27/02/15
 * Time: 22:07
 */
namespace atk4\atk4homepage;

class Page_Dynamic extends \Page {

    /**
     * @var Model_Page $model
     */
    public $model;

    public $page_translation;

    function init() {
        parent::init();

        if ($this->model) {
            if (!is_a($this->model,'atk4\atk4homepage\Model_Page') || !$this->model->loaded()) {
                throw $this->exception('Set up proper model.');
            }
        } else {
            $this->model = $this->add('atk4\atk4homepage\Model_Page');
            $this->model->tryLoadBy('hash_url',$this->app->page);
            if(!$this->model->loaded()) throw $this->exception('There is no such a page ['.$this->app->page.']', 'atk4\atk4homepage\NoPage');
        }


        $this->page_translation = $this->model->getTranslation(true);


        // This is dynamically added page, let's do everything we need
        // In other case user must do the same manually
        if (get_class($this) == 'atk4\atk4homepage\Page_Dynamic') {
            $this->addDynamicContent();
        }

    }


    public function addDynamicContent() {
        $this->title = $this->page_translation['meta_title'];

        $view = $this->add('atk4\atk4homepage\View_DynamicPage',['template_path'=>$this->model->getTemplatePath()]);
        $view->setModel($this->model);
        $view->get();

        $this->getPageMenu($view,$this->model);
    }




    public function getPageMenu(\AbstractView $view,Model_Page $page) {
        if ($view->template->hasTag('SubMenu') && $siblings = $page->getSiblings()) {

            $menu = $view->add('Menu_Vertical',null,'SubMenu');
            foreach($siblings as $page){
                $page->page_translation = $page->getTranslation(true);
                $url = $page['hash_url']?:$page['url_first_child'];
                $this->app->addMenuItem($menu,$page->page_translation['meta_title'],'home-1','atk-swatch-beigeDarken',$url);
            }

        }
    }

}