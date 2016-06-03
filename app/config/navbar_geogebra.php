<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [
		
        'fragor' => [
            'text'  =>'Frågor',
            'url'   => $this->di->get('url')->create('fragor'),
            'title' => 'Frågor',            
        ],
				
				'taggar' => [
            'text'  =>'Taggar',
            'url'   => $this->di->get('url')->create('taggar'),
            'title' => 'Taggar',            
        ],
				
				'anvandare' => [
            'text'  =>'Användare',
            'url'   => $this->di->get('url')->create('anvandare'),
            'title' => 'Användare',            
        ],
				
				'stall_fraga' => [
            'text'  =>'Ställ en fråga',
            'url'   => $this->di->get('url')->create('stall_fraga'),
            'title' => 'Ställ en fråga',            
        ],
				
				'om' => [
            'text'  =>'Om',
            'url'   => $this->di->get('url')->create('om'),
            'title' => 'Om',            
        ],
    ],
 


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
