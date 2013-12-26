<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Deployer\Models\Deployment;
use Deployer\Models\Host;
use Deployer\Models\Project;
use Deployer\Models\User;

$app = new Silex\Application;

$app['debug'] = true; // @TODO: remove this

$app->register(new Silex\Provider\TwigServiceProvider, array(
    'twig.path' => __DIR__ . '/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider);

$app->register(new Deployer\ServiceProviders\CapsuleServiceProvider, array(
    'capsule.connection' => array(
        'database' => 'deployer',
        'username' => 'root',
        'password' => ''
    )
));

$app->register(new Deployer\ServiceProviders\SentryServiceProvider, array(
    'sentry.providers' => array(
        'user' => 'Deployer\Models\User'
    )
));

$app['capsule']->setAsGlobal();

$app['capsule']->bootEloquent();

$app->before(function (Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $app['twig']->addGlobal('current_route', $request->getRequestUri());
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

    return $app['twig']->render('index.twig', $data);
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

    return $app['twig']->render('projects.twig', $data);
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

    return $app['twig']->render('project/read.twig', $data);
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

    return $app['twig']->render('project/add.twig', $data);
})
->bind('project.add');

/**
 * Display hosts
 */
$app->get('/hosts', function() use ($app) {
    $data = array(
    );

    return $app['twig']->render('hosts.twig', $data);
})
->bind('hosts');

/**
 * Display users
 */
$app->get('/users', function() use ($app) {
    $data = array(
    );

    return $app['twig']->render('users.twig', $data);
})
->bind('users');

/**
 * Display account
 */
$app->get('/account', function() use ($app) {
    $data = array(
    );

    return $app['twig']->render('account.twig', $data);
})
->bind('account');

/**
 * Settings
 */
$app->get('/settings', function() use ($app) {
    $data = array(
    );

    return $app['twig']->render('settings.twig', $data);
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
