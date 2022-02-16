<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\RocketIntegration\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	   ['name' => 'page#file', 'url' => '/file-chat', 'verb' => 'GET'],
	   ['name' => 'config#setupUrl', 'url' => '/setup-url', 'verb' => 'POST'],
	   ['name' => 'settings#resetConfig', 'url' => '/reset-config', 'verb' => 'POST'],
	   ['name' => 'file#store', 'url' => '/file', 'verb' => 'POST'],
       ['name' => 'settings#setAdminConfig', 'url' => '/admin-config', 'verb' => 'PUT'],
       ['name' => 'settings#getWidgetContent', 'url' => '/widget-content', 'verb' => 'GET'],
    ]
];
