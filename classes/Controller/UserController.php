<?php

namespace Deployer\Controller;

use Deployer\Application;
use Deployer\Model\User;
use Deployer\Model\Username;

class UserController
{
    public function actionList(Application $app)
    {
        $data = array(
            'users' => $app['sentry']->findAllUsers()
        ) + $app->getRedirectData();

        return $app['blade']->make('user.list', $data);
    }

    public function actionAdd(Application $app)
    {
        $data = array(
            'type'  => 'add',
            'hosts' => array_keys($app['config']['hosts'])
        ) + $app->getRedirectData();

        return $app['blade']->make('user.add-edit', $data);
    }

    public function actionProcessAdd(Application $app)
    {
        $input = $app['request']->request->all();

        $errors = array();

        $validation = $app['validator']($input, User::$rules, User::$messages);

        if ($validation->passes()) {
            try {
                $user = $app['sentry']->createUser(array(
                    'email' => $input['email'],
                    'password' => $input['password'],
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name']
                ));

                $user->save();

                foreach ($input['hosts'] as $hostName => $usernameValue) {
                    if (!empty($usernameValue)) {
                        $username = $user->usernames()->where('host', '=', $hostName)->first();

                        if (is_null($username)) {
                            $username = new Username;
                            $username->host = $hostName;
                        }

                        $username->username = $usernameValue;
                        $username->user()->associate($user);
                        $username->save();
                    }
                }

                return $app->redirect('user.list', array(
                    'successMessage' => 'User successfully added'
                ));
            } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
                $errors[] = 'A user with that email address already exists';
            } catch (Illuminate\Database\QueryException $e) {
                $errors[] = 'Unable to add host accounts';
            }
        }

        return $app->forward('user.add', array(
            'errorMessages' => $validation->messages()->all() + $errors,
            'oldInput'      => $app['request']->request->all()
        ));
    }

    public function actionEdit(User $user, Application $app)
    {
        $data = array(
            'type'  => 'edit',
            'user'  => $user,
            'hosts' => array_keys($app['config']['hosts']),
            'usernames' => $user->usernames()->lists('username', 'host')
        ) + $app->getRedirectData();

        return $app['blade']->make('user.add-edit', $data);
    }

    public function actionProcessEdit(User $user, Application $app)
    {
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

                foreach ($input['hosts'] as $hostName => $usernameValue) {
                    $username = $user->usernames()->where('host', '=', $hostName)->first();

                    if (!empty($usernameValue)) {
                        if (is_null($username)) {
                            $username = new Username;
                            $username->host = $hostName;
                        }

                        $username->username = $usernameValue;
                        $username->user()->associate($user);
                        $username->save();
                    } elseif (!is_null($username)) {
                        $username->delete();
                    }
                }

                return $app->redirect('user.list', array(
                    'successMessage' => 'User successfully edited'
                ));
            } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
                $errors[] = 'A user with that email address already exists';
            } catch (Illuminate\Database\QueryException $e) {
                $errors[] = 'Unable to update host accounts';
            }
        }

        return $app->forward(array('user.edit', array('user' => $user->id)), array(
            'errorMessages' => $validation->messages()->all() + $errors,
            'oldInput'      => $app['request']->request->all()
        ));
    }

    public function actionLogin(Application $app)
    {
        $data = array(
        ) + $app->getRedirectData();

        return $app['blade']->make('user.login', $data);
    }
}
