<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Deployer\Models\Deployment;
use Deployer\Models\Host;
use Deployer\Models\Project;
use Deployer\Models\User;

$app = new Deployer\Application;

$app['debug'] = true; // @TODO: remove this

$app->register(new Silex\Provider\SessionServiceProvider);

$app->register(new Silex\Provider\UrlGeneratorServiceProvider);

$app->register(new Silex\Provider\TranslationServiceProvider, array(
    'locale_fallbacks' => array('en'),
));

$app->register(new Deployer\ServiceProviders\CapsuleServiceProvider, array(
    'capsule.connection' => array(
        'database' => 'deployer',
        'username' => 'root',
        'password' => ''
    )
));

$app->register(new Deployer\ServiceProviders\ValidatorServiceProvider);

$app->register(new Deployer\ServiceProviders\SentryServiceProvider, array(
    'sentry.providers' => array(
        'user' => 'Deployer\Models\User'
    )
));

$app->register(new Deployer\ServiceProviders\BladeServiceProvider, array(
    'blade.settings' => array(
        'cache' => '/tmp',
        'views' => array(
            __DIR__ . '/views'
        )
    )
));

// Inject the $app into every blade view
$app['blade']->composer('*', function($view) use ($app) {
    $view->app = $app;
});

$projectProvider = function($id) {
    return Project::find($id);
};

/**
 * Display index
 */
$app->get('/', function() use ($app) {
    $data = array(
    );

    return $app['blade']->make('index', $data);
})
->bind('home');

/**
 * Display projects
 */
$app->get('/projects', function() use ($app) {
    $projects = Project::all();

    $data = array(
        'projects' => $projects
    );

    return $app['blade']->make('projects', $data);
})
->bind('projects');

/**
 * View a project
 */
$app->get('/project/{project}', function($project) use ($app) {
    if (is_null($project)) {
        $app->abort(404, 'Project not found');
    }

    $data = array(
        'project' => $project
    );

    return $app['blade']->make('project.read', $data);
})
->assert('project', '\d+')
->convert('project', $projectProvider)
->bind('project.read');

/**
 * Add a project
 */
$app->get('/project/add', function() use ($app) {
    $data = array(
    );

    return $app['blade']->make('project.add', $data);
})
->bind('project.add');

/**
 * Display hosts
 */
$app->get('/hosts', function() use ($app) {
    $data = array(
    );

    return $app['blade']->make('hosts', $data);
})
->bind('hosts');

/**
 * Display users
 */
$app->get('/users', function() use ($app) {
    $data = array();

    $data['users'] = $app['sentry']->findAllUsers();

    return $app['blade']->make('users', $data);
})
->bind('users');

/**
 * Add a user
 */
$app->get('/user/add', function() use ($app) {
    $data = array(
        'type' => 'add',
    ) + $app->getRedirectData();

    return $app['blade']->make('user.add-edit', $data);
})
->bind('user.add');

$app->post('/user/add', function() use ($app) {
    $validation = $app['validator']($app['request']->request->all(), User::$rules, User::$messages);

    if ($validation->passes()) {

    } else {
        return $app->redirectWithData('/user/add', array(
            'success'       => false,
            'errorMessages' => $validation->messages()->all(),
            'oldInput'      => $app['request']->request->all()
        ));
    }
});

/**
 * Display account
 */
$app->get('/account', function() use ($app) {
    $data = array(
    );

    return $app['blade']->make('account', $data);
})
->bind('account');

/**
 * Settings
 */
$app->get('/settings', function() use ($app) {
    $data = array(
    );

    return $app['blade']->make('settings', $data);
})
->bind('settings');

/**
 * Handle the POST hook
 */
$app->post('/hook/{hash}', function() use ($app) {
    exec('git fetch origin master 2>&1');
    exec('git reset --hard FETCH_HEAD 2>&1');
    exec('git clean -df 2>&1');
})
->assert('hash', '[a-f0-9]{32}')
->bind('hook');

$app->run();
