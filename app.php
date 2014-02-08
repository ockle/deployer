<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

$app = new Deployer\Application;

$app['debug'] = true; // @TODO: remove this

$app['config'] = require_once 'config.php';

$app->register(new Silex\Provider\SessionServiceProvider);

$app->register(new Silex\Provider\UrlGeneratorServiceProvider);

$app->register(new Silex\Provider\TranslationServiceProvider, array(
    'locale_fallbacks' => array('en'),
));

$app->register(new Deployer\Provider\CapsuleServiceProvider, array(
    'capsule.connection' => $app['config']['database']
));

$app->register(new Deployer\Provider\ValidatorServiceProvider, array(
    'validator.class' => 'Deployer\Validator'
));

$app->register(new Deployer\Provider\SentryServiceProvider, array(
    'sentry.providers' => array(
        'user' => 'Deployer\Model\User'
    )
));

$app->register(new Deployer\Provider\BladeServiceProvider, array(
    'blade.settings' => array(
        'cache' => '/tmp',
        'views' => array(
            __DIR__ . '/views'
        )
    )
));

$projectProvider = function($id) use ($app) {
    $project = Deployer\Model\Project::find($id);

    if (is_null($project)) {
        $app->abort(404, 'Project not found');
    }

    return $project;
};

$userProvider = function($id) use ($app) {
    try {
        return $app['sentry']->findUserById($id);
    } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
        $app->abort(404, 'User not found');
    }
};

$loggedIn = function(Symfony\Component\HttpFoundation\Request $request, Deployer\Application $app) {
    if (!$app['sentry']->check()) {
        return $app->redirect('login', array(
            'errorMessage' => 'You must be logged in to access that page.'
        ));
    }
};

$notLoggedIn = function(Symfony\Component\HttpFoundation\Request $request, Deployer\Application $app) {
    if ($app['sentry']->check()) {
        return $app->redirect('home');
    }
};

/**
 * Display home
 */
$app->get('/', 'Deployer\Controller\HomeController::actionView')
    ->before($loggedIn)
    ->bind('home');

/**
 * Display projects
 */
$app->get('/projects', 'Deployer\Controller\ProjectController::actionList')
    ->before($loggedIn)
    ->bind('project.list');

/**
 * View a project
 */
$app->get('/project/{project}', 'Deployer\Controller\ProjectController::actionView')
    ->assert('project', '\d+')
    ->before($loggedIn)
    ->convert('project', $projectProvider)
    ->bind('project.view');

/**
 * Add a project
 */
$app->get('/project/add', 'Deployer\Controller\ProjectController::actionAdd')
    ->before($loggedIn)
    ->bind('project.add');

$app->post('/project/add', 'Deployer\Controller\ProjectController::actionProcessAdd')
    ->before($loggedIn);

/**
 * Add a project
 */
$app->get('/project/{project}/edit', 'Deployer\Controller\ProjectController::actionEdit')
    ->before($loggedIn)
    ->convert('project', $projectProvider)
    ->bind('project.edit');

/**
 * Display users
 */
$app->get('/users', 'Deployer\Controller\UserController::actionList')
    ->before($loggedIn)
    ->bind('user.list');

/**
 * Add a user
 */
$app->get('/user/add', 'Deployer\Controller\UserController::actionAdd')
    ->before($loggedIn)
    ->bind('user.add');

$app->post('/user/add', 'Deployer\Controller\UserController::actionProcessAdd')
    ->before($loggedIn);

/**
 * Edit a user
 */
$app->get('/user/{user}/edit', 'Deployer\Controller\UserController::actionEdit')
    ->assert('user', '\d+')
    ->before($loggedIn)
    ->convert('user', $userProvider)
    ->bind('user.edit');

$app->post('/user/{user}/edit', 'Deployer\Controller\UserController::actionProcessEdit')
    ->assert('user', '\d+')
    ->before($loggedIn)
    ->convert('user', $userProvider);

/**
 * Delete a user
 */
$app->get('/user/{user}/delete', 'Deployer\Controller\UserController::actionDelete')
    ->assert('user', '\d+')
    ->before($loggedIn)
    ->convert('user', $userProvider)
    ->bind('user.delete');

$app->post('/user/{user}/delete', 'Deployer\Controller\UserController::actionProcessDelete')
    ->assert('user', '\d+')
    ->before($loggedIn)
    ->convert('user', $userProvider);

/**
 * Login
 */
$app->get('/login', 'Deployer\Controller\UserController::actionLogin')
    ->before($notLoggedIn)
    ->bind('login');

$app->post('/login', 'Deployer\Controller\UserController::actionProcessLogin')
    ->before($notLoggedIn);

/**
 * Logout
 */
$app->get('/logout', 'Deployer\Controller\UserController::actionLogout')
    ->before($loggedIn)
    ->bind('logout');

$app->post('/logout', 'Deployer\Controller\UserController::actionProcessLogout')
    ->before($loggedIn);

/**
 * Handle the POST hook
 */
$app->post('/deployment/hook/{hash}', 'Deployer\Controller\DeploymentController::actionHook')
    ->assert('hash', '[a-f0-9]{32}')
    ->bind('deployment.hook');

$app->run();
