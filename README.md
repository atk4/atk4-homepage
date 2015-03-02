Agile Toolkit Home Page
-----------------------

Almost every project has bunch of static pages, some projects are nothing more but the bunch of static pages.

Almost every client wants to have ability to do small text changes on those static pages and we create CMS for those clients.

Almost every client doesn't know what CMS is and how to deal with it because most of CMS give a lot of power for content control,
to much power, and client, who wants just change name of his wife on his homepage (because now he has a new wife :-) )
doesn't need this great power and doesn't want to spend time learning how to work with such a powerful CMS.

Almost every designer hates when client has access to HTML and can change or add it.

Ok, let's separate power, design and name of client's wife.


## Roles

* Programmer - can write code, know nothing about design.
* Designer - know nothing about programming, can do design and convert it to html.
* Client - know nothing about design, programming and CMS, want to have homepage with static page and ability
to change some texts in the future.


## Idea

Every design can by cut to small peaces of HTML. In Agile Toolkit we call them "view".
Usually they are located in "templates" folder of project.
What if we cut HTML design to such a views, put them into project folder and just describe how to put these small pieces together somehow?

### Shared template

First of all we need to put all common parts of design to shared.html.
If you don't know how to do it you must read documentation of Agile Toolkit.

### Pages

After creation of shared.html we can determine pages with different structures. Usually there are 4-6 structures.
Let's create page template file for each page structure and describe them in config.

    $config['atk4-home-page'] = [
        'page_types' => [
            'home' =>[
                'descr' => 'Page with static template',  // user friendly description
                'template' => 'pages/home',              // path to template
            ],
        ],
        'tcm' => [
            'descr' => 'Two columns text with left menu',
            'template' => 'pages/tcm',
        ],
    ];


Now we have two page's type (pages with two different structures).

### Blocks

Next step is describing bunch of blocks for each page.


        'tcm' => [
            'descr' => 'Two columns text with left menu',   // user friendly description
            'template' => 'pages/tcm',                      // path to page template
            'blocks'   => [                                 // list of blocks of this page
                'italic_1_spot'=>'italic',                  // combination of 'page spot' => 'block type'
                'two_columns_1_spot'=>'two columns',        // combination of 'page spot' => 'block type'
                'quote_1_spot'=>'quote',                    // combination of 'page spot' => 'block type'
            ]
        ],

You must read Agile Toolkit documentation if you don't know how to use spots in templates.

But what is block it self???
Usually content on the page is separated on logical parts. There might be a title, regular text, two columns text, quota etc.
Every, even small piece of design can be converted to the block. Every block must have independed design and appears very identical
in any place of web site.

Ok, we've described blocks for page, but still don't have any block description. Let's do it now.


    'block_types' => [
        'italic'=>[
            'template' => 'view/blocks/italic'
        ],
        'quote'=>[
            'template' => 'view/blocks/quote'
        ],
        'two columns' =>[
            'template' => 'view/blocks/two_columns'
        ],
        'header h2'=>[
            'template' => 'view/blocks/header_h2'
        ],
        'paragraph'=>[
            'template' => 'view/blocks/paragraph'
        ],
        'small block'=>[
            'template' => 'view/blocks/small_block'
        ],
        'button'=>[
            'template' => 'view/blocks/button'
        ],
        'long button'=>[
            'template' => 'view/blocks/long_button'
        ],
        'header h4'=>[
            'template' => 'view/blocks/header_h4'
        ],
        'big block'=>[
            'template' => 'view/blocks/big_block'
        ],
    ],

### Languages

Add trait tk4\atk4homepage\Trait_LanguageSupport to your App class

    <?php
    class Admin extends App_Admin {
        use atk4\atk4homepage\Trait_LanguageSupport;
    }

Set bunch of languages in config

    'available_languages' => ['en'=>'en_EN','lv'=>'lv_LV','ru'=>'ru_RU'],

Set default language in config

    'default_language' => 'en',

That's all.

We can go to admin part and create pages based on described design.

## Installation

Add requirement to your project's composer.json file


    "require": {
        "atk4/atk4-homepage": "*"
    },

Run

    php composer.phar update

Done!

## Admin


The trait Trait_LanguageSupport adds translation support to your application. You can get current language using

    $this->app->getCurrentLanguage();

### Page

Create page inherited from atk4\atk4-homepage\Page_ATK4HomePage

    <?php

    use atk4\atk4homepage\Page_ATK4HomePage;

    class page_cms extends Page_ATK4HomePage {}


Open this page in your browser. You must see something like this

    [IMAGE doc/imgs/0001.jpeg]

This is list of pages which will appears in main (top level) menu.














