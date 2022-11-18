<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'api'], function() use ($router) {
    $router->group(['prefix' => 'user', 'name' => 'user.'], function() use ($router) {
        $router->post('register', [
            'uses'  => 'UserController@register',
            'as'    => 'register',
        ]);

        $router->post('sign-in', [
            'uses'  => 'UserController@signIn',
            'as'    => 'sign-in',
        ]);
        
        $router->post('recover-password', [
            'uses'  => 'UserController@requestRecoverToken',
            'as'    => 'request-recover-token',
        ]);

        $router->patch('recover-password', [
            'uses'  => 'UserController@recoverPassword',
            'as'    => 'recover-password',
        ]);

        $router->get('companies', [
            'middleware'    => 'auth',
            'uses'          => 'CompanyController@getCompanies',
            'as'            => 'get-companies',
        ]);

        $router->post('companies', [
            'middleware'    => 'auth',    
            'uses'          => 'CompanyController@addCompany',
            'as'            => 'add-company',
        ]);
    });
});