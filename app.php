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

$app['projectProvider'] = $app->protect(function($id) use ($app) {
    $project = Deployer\Model\Project::find($id);

    if (is_null($project)) {
        $app->abort(404, 'Project not found');
    }

    return $project;
});

$app['deploymentProvider'] = $app->protect(function($id) use ($app) {
    $deployment = Deployer\Model\Deployment::find($id);

    if (is_null($deployment)) {
        $app->abort(404, 'Deployment not found');
    }

    return $deployment;
});

$app['userProvider'] = $app->protect(function($id) use ($app) {
    try {
        return $app['sentry']->findUserById($id);
    } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
        $app->abort(404, 'User not found');
    }
});

$app['loggedIn'] = $app->protect(function(Symfony\Component\HttpFoundation\Request $request, Deployer\Application $app) {
    if (!$app['sentry']->check()) {
        return $app->redirect('login', array(
            'errorMessage' => 'You must be logged in to access that page.'
        ));
    }
});

$app['notLoggedIn'] = $app->protect(function(Symfony\Component\HttpFoundation\Request $request, Deployer\Application $app) {
    if ($app['sentry']->check()) {
        return $app->redirect('home');
    }
});

/**
 * Display home
 */
$app->get('/', 'Deployer\Controller\HomeController::actionView')
    ->before($app['loggedIn'])
    ->bind('home');

/**
 * Display projects
 */
$app->get('/projects', 'Deployer\Controller\ProjectController::actionList')
    ->before($app['loggedIn'])
    ->bind('project.list');

/**
 * View a project
 */
$app->get('/project/{project}', 'Deployer\Controller\ProjectController::actionView')
    ->assert('project', '\d+')
    ->before($app['loggedIn'])
    ->convert('project', $app['projectProvider'])
    ->bind('project.view');

/**
 * Add a project
 */
$app->get('/project/add', 'Deployer\Controller\ProjectController::actionAdd')
    ->before($app['loggedIn'])
    ->bind('project.add');

$app->post('/project/add', 'Deployer\Controller\ProjectController::actionProcessAdd')
    ->before($app['loggedIn']);

/**
 * Edit a project
 */
$app->get('/project/{project}/edit', 'Deployer\Controller\ProjectController::actionEdit')
    ->before($app['loggedIn'])
    ->convert('project', $app['projectProvider'])
    ->bind('project.edit');

$app->post('/project/{project}/edit', 'Deployer\Controller\ProjectController::actionProcessEdit')
    ->convert('project', $app['projectProvider'])
    ->before($app['loggedIn']);

/**
 * Delete a project
 */
$app->get('/project/{project}/delete', 'Deployer\Controller\ProjectController::actionDelete')
    ->assert('project', '\d+')
    ->before($app['loggedIn'])
    ->convert('project', $app['projectProvider'])
    ->bind('project.delete');

$app->post('/project/{project}/delete', 'Deployer\Controller\ProjectController::actionProcessDelete')
    ->assert('project', '\d+')
    ->before($app['loggedIn'])
    ->convert('project', $app['projectProvider']);

/**
 * Display users
 */
$app->get('/users', 'Deployer\Controller\UserController::actionList')
    ->before($app['loggedIn'])
    ->bind('user.list');

/**
 * Add a user
 */
$app->get('/user/add', 'Deployer\Controller\UserController::actionAdd')
    ->before($app['loggedIn'])
    ->bind('user.add');

$app->post('/user/add', 'Deployer\Controller\UserController::actionProcessAdd')
    ->before($app['loggedIn']);

/**
 * Edit a user
 */
$app->get('/user/{user}/edit', 'Deployer\Controller\UserController::actionEdit')
    ->assert('user', '\d+')
    ->before($app['loggedIn'])
    ->convert('user', $app['userProvider'])
    ->bind('user.edit');

$app->post('/user/{user}/edit', 'Deployer\Controller\UserController::actionProcessEdit')
    ->assert('user', '\d+')
    ->before($app['loggedIn'])
    ->convert('user', $app['userProvider']);

/**
 * Delete a user
 */
$app->get('/user/{user}/delete', 'Deployer\Controller\UserController::actionDelete')
    ->assert('user', '\d+')
    ->before($app['loggedIn'])
    ->convert('user', $app['userProvider'])
    ->bind('user.delete');

$app->post('/user/{user}/delete', 'Deployer\Controller\UserController::actionProcessDelete')
    ->assert('user', '\d+')
    ->before($app['loggedIn'])
    ->convert('user', $app['userProvider']);

/**
 * Login
 */
$app->get('/login', 'Deployer\Controller\UserController::actionLogin')
    ->before($app['notLoggedIn'])
    ->bind('login');

$app->post('/login', 'Deployer\Controller\UserController::actionProcessLogin')
    ->before($app['notLoggedIn']);

/**
 * Logout
 */
$app->get('/logout', 'Deployer\Controller\UserController::actionLogout')
    ->before($app['loggedIn'])
    ->bind('logout');

$app->post('/logout', 'Deployer\Controller\UserController::actionProcessLogout')
    ->before($app['loggedIn']);

/**
 * View a deployment
 */
$app->get('/deployment/{deployment}', 'Deployer\Controller\DeploymentController::actionView')
    ->assert('deployment', '\d+')
    ->before($app['loggedIn'])
    ->convert('deployment', $app['deploymentProvider'])
    ->bind('deployment.view');

/**
 * Handle a manual deployment
 */
$app->get('/deployment/manual/{project}', 'Deployer\Controller\DeploymentController::actionManual')
    ->assert('project', '\d+')
    ->before($app['loggedIn'])
    ->convert('project', $app['projectProvider'])
    ->bind('deployment.manual');

$app->post('/deployment/manual/{project}', 'Deployer\Controller\DeploymentController::actionProcessManual')
    ->assert('project', '\d+')
    ->before($app['loggedIn'])
    ->convert('project', $app['projectProvider']);

/**
 * Handle an automatic deplyment POST hook
 */
$app->post('/deployment/automatic/{hash}', 'Deployer\Controller\DeploymentController::actionAutomatic')
    ->assert('hash', '[a-f0-9]{32}')
    ->bind('deployment.automatic');

$app->run();
