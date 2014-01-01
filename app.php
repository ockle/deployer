<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

use Deployer\Model\Deployment;
use Deployer\Model\Host;
use Deployer\Model\Project;
use Deployer\Model\User;

$app = new Deployer\Application;

$app['debug'] = true; // @TODO: remove this

$app->register(new Silex\Provider\SessionServiceProvider);

$app->register(new Silex\Provider\UrlGeneratorServiceProvider);

$app->register(new Silex\Provider\TranslationServiceProvider, array(
    'locale_fallbacks' => array('en'),
));

$app->register(new Deployer\Provider\CapsuleServiceProvider, array(
    'capsule.connection' => array
(        'database' => 'deployer',
        'username' => 'root',
        'password' => ''
    )
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


$hostProvider = function($id) use ($app) {
    $host = Host::find($id);

    if (is_null($host)) {
        $app->abort(404, 'Host not found');
    }

    return $host;
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
$app->get('/projects', function() use ($app) {
    $projects = Project::all();

    $data = array(
        'projects' => $projects
    );

    return $app['blade']->make('project.list', $data);
})
->bind('project.list');

/**
 * View a project
 */
$app->get('/project/{project}', function($project) use ($app) {
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
        'hosts' => Host::nameAsc()->get()
    ) + $app->getRedirectData();

    return $app['blade']->make('host.list', $data);
})
->bind('host.list');

/**
 * Add a host
 */
$app->get('/host/add', function() use ($app) {
    $data = array(
        'type' => 'add'
    ) + $app->getRedirectData();

    return $app['blade']->make('host.add-edit', $data);
})
->bind('host.add');

$app->post('/host/add', function() use ($app) {
    $input = $app['request']->request->all();

    $errors = array();

    $validation = $app['validator']($input, Host::$rules, Host::$messages);

    if ($validation->passes()) {
        $host = new Host;
        $host->name = $input['name'];

        if ($host->save()) {
            return $app->redirect('host.list', array(
                'successMessage' => 'Host successfully added'
            ));
        }

        $errors[] = 'Unable to save host to database';
    }

    return $app->forward('host.add', array(
        'errorMessages' => $validation->messages()->all() + $errors,
        'oldInput'      => $app['request']->request->all()
    ));
});

/**
 * Edit a host
 */
$app->get('/host/{host}/edit', function($host) use ($app) {
    $data = array(
        'type' => 'edit',
        'host' => $host
    ) + $app->getRedirectData();

    return $app['blade']->make('host.add-edit', $data);
})
->assert('host', '\d+')
->convert('host', $hostProvider)
->bind('host.edit');

$app->post('/host/{host}/edit', function($host) use ($app) {
    $input = $app['request']->request->all();

    $errors = array();

    $validation = $app['validator']($input, Host::$rules, Host::$messages);

    if ($validation->passes()) {
        $host->name = $input['name'];

        if ($host->save()) {
            return $app->redirect('host.list', array(
                'successMessage' => 'Host successfully edited'
            ));
        }

        $errors[] = 'Unable to save host to database';
    }
    return $app->forward(array('host.edit', array('host' => $host->id)), array(
        'errorMessages' => $validation->messages()->all() + $errors,
        'oldInput'      => $app['request']->request->all()
    ));
})
->assert('host', '\d+')
->convert('host', $hostProvider);

/**
 * Display users
 */
$app->get('/users', function() use ($app) {
    $data = array(
        'users' => $app['sentry']->findAllUsers()
    ) + $app->getRedirectData();

    return $app['blade']->make('user.list', $data);
})
->bind('user.list');

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
    $input = $app['request']->request->all();

    $errors = array();

    $validation = $app['validator']($input, User::$rules, User::$messages);

    if ($validation->passes()) {
        try {
            $app['sentry']->createUser(array(
                'email' => $input['email'],
                'password' => $input['password'],
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name']
            ));

            return $app->redirect('user.list', array(
                'successMessage' => 'User successfully added'
            ));
        } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
            $errors[] = 'A user with that email address already exists';
        }
    }

    return $app->forward('user.add', array(
        'errorMessages' => $validation->messages()->all() + $errors,
        'oldInput'      => $app['request']->request->all()
    ));
});

/**
 * Edit a user
 */
$app->get('/user/{user}/edit', function($user) use ($app) {
    $data = array(
        'type' => 'edit',
        'user' => $user
    ) + $app->getRedirectData();

    return $app['blade']->make('user.add-edit', $data);
})
->assert('user', '\d+')
->convert('user', $userProvider)
->bind('user.edit');

$app->post('/user/{user}/edit', function($user) use ($app) {
    $input = $app['request']->request->all();

    $errors = array();

    $validation = $app['validator']($input, User::$rules, User::$messages);

    if ($validation->passes()) {
        try {
            $user->first_name = $input['first_name'];
            $user->last_name = $input['last_name'];
            $user->email = $input['email'];

            if (!empty($input['password'])) {
                $user->password = $input['password'];
            }

            $user->save();

            return $app->redirect('user.list', array(
                'successMessage' => 'User successfully edited'
            ));
        } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
            $errors[] = 'A user with that email address already exists';
        }
    }
    return $app->forward(array('user.edit', array('user' => $user->id)), array(
        'errorMessages' => $validation->messages()->all() + $errors,
        'oldInput'      => $app['request']->request->all()
    ));
})
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
    exec('git fetch origin master 2>&1');
    exec('git reset --hard FETCH_HEAD 2>&1');
    exec('git clean -df 2>&1');
})
->assert('hash', '[a-f0-9]{32}')
->bind('hook');

$app->run();
