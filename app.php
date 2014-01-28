<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

use Symfony\Component\Process\Process;
use Deployer\Model\Deployment;
use Deployer\Model\Project;
use Deployer\Model\User;
use Deployer\Model\Username;

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

$app->register(new Deployer\Provider\ValidatorServiceProvider);

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
    $project = Project::find($id);

    if (is_null($project)) {
        $app->abort(404, 'User not found');
    }

    return $project;
};

$userProvider = function($id) use ($app) {
    try {
        return $app['sentry']->findUserById($id);
    } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
        $app->abort(404, 'Project not found');
    }
};

/**
 * Display home
 */
$app->get('/', function() use ($app) {
    $data = array(
    );

    return $app['blade']->make('home', $data);
})
->bind('home');

/**
 * Display projects
 */
$app->get('/projects', 'Deployer\Controller\ProjectController::actionList')
    ->bind('project.list');

/**
 * View a project
 */
$app->get('/project/{project}', 'Deployer\Controller\ProjectController::actionView')
    ->assert('project', '\d+')
    ->convert('project', $projectProvider)
    ->bind('project.view');

/**
 * Add a project
 */
$app->get('/project/add', 'Deployer\Controller\ProjectController::actionAdd')
    ->bind('project.add');

/**
 * Display users
 */
$app->get('/users', 'Deployer\Controller\UserController::actionList')
    ->bind('user.list');

/**
 * Add a user
 */
$app->get('/user/add', 'Deployer\Controller\UserController::actionAdd')
    ->bind('user.add');

$app->post('/user/add', 'Deployer\Controller\UserController::actionProcessAdd');

/**
 * Edit a user
 */
$app->get('/user/{user}/edit', 'Deployer\Controller\UserController::actionEdit')
    ->assert('user', '\d+')
    ->convert('user', $userProvider)
    ->bind('user.edit');

$app->post('/user/{user}/edit', 'Deployer\Controller\UserController::actionProcessEdit')
    ->assert('user', '\d+')
    ->convert('user', $userProvider);

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
 * Handle the POST hook
 */
$app->post('/hook/{hash}', function() use ($app) {
    // Find project from hash
    $project = Project::where('hash', '=', $hash)->first();

    if (is_null($project)) {
        $app->abort(404);
    }

    // Create host object
    $projectHost = $project->host;

    $host = new $projectHost($app['request']);

    // Check pushed branch is the branch that the project deploys from
    $branch = $host->getBranch();

    if ($project->branch_name != $branch) {
        $app->abort(404);
    }

    // Check the pusher is a user
    $pusher = $host->getPusher();

    $username = Username::where('host', '=', $projectHost)->where('username', '=', $host->getPusher())->first();

    if (is_null($username)) {
        $app->abort(404);
    }

    $user = $username->user;

    // Create a record of the deployment
    $deployment = new Deployment;
    $deployment->project()->associate($project);
    $deployment->user()->associate($user);

    // Start deployment
    chdir($project->directory);

    $fetch = new Process('git fetch origin ' . $project->branch_name);
    $fetch->run();

    if (!$fetch->isSuccessful()) {
        $deployment->error('Error fetching remote');

        $app->abort(500);
    }

    $reset = new Process('git reset --hard FETCH_HEAD');
    $reset->run();

    if (!$reset->isSuccessful()) {
        $deployment->error('Error resetting project to fetched files');

        $app->abort(500);
    }

    $clean = new Process('git clean -df');
    $clean->run();

    if (!$clean->isSuccessful()) {
        $deployment->error('Error cleaning project');

        $app->abort(500);
    }

    // Deployment successful
    $deployment->message = $host->getLastCommitMessage();
    $deployment->status = 1;

    $deployment->save();

    return true;
})
->assert('hash', '[a-f0-9]{32}')
->bind('hook');

$app->run();
