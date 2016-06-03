<?php
/**
 * Config-file for Anax, theme related settings, return it all as array.
 *
 */
return [

    /**
     * Settings for Which theme to use, theme directory is found by path and name.
     *
     * path: where is the base path to the theme directory, end with a slash.
     * name: name of the theme is mapped to a directory right below the path.
     */
    'settings' => [
        'path' => ANAX_INSTALL_PATH . 'theme/',
        'name' => 'anax-base',
    ],

    
    /**
     * Add default views.
     */
    'views' => [
				[ 
					'region'   => 'user_info', 
					'template' => 'geogebra/user_info', 
					'data'     => [
						'siteTitle' => "Användare",
						'siteTagline' => "Patrik",
					], 
					'sort'     => -1
				],
        [ 
					'region'   => 'header', 
					'template' => 'geogebra/header', 
					'data'     => [
						'siteTitle' => "Allt om GeoGebra",
						'siteTagline' => "På svenska",
					], 
					'sort'     => -1
				],
        ['region' => 'footer', 'template' => 'geogebra/footer', 'data' => [], 'sort' => -1],
				[
					'region' => 'navbar', 
					'template' => [
            'callback' => function() {
                return $this->di->navbar->create();
            },
					], 
					'data' => [], 
					'sort' => -1
				],
    ],


    /**
     * Data to extract and send as variables to the main template file.
     */
    'data' => [

        // Language for this page.
        'lang' => 'sv',

        // Append this value to each <title>
        'title_append' => ' | Allt om GeoGebra',

        // Stylesheets
        'stylesheets' => ['css/style.css', 'css/navbar.css', 'css/jquery-ui.css', 'css/jquery.tagit.css'],

        // Inline style
        'style' => null,

        // Favicon
        'favicon' => 'favicon.ico',

        // Path to modernizr or null to disable
        'modernizr' => 'js/modernizr.js',

        // Path to jquery or null to disable
        'jquery' => null,//'//ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js',
				
				

        // Array with javscript-files to include
        'javascript_include' => ['js/tag-it.js'],

        // Use google analytics for tracking, set key or null to disable
        'google_analytics' => null,
    ],
];
