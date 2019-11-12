<?php

use Illuminate\Http\Request;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function ($route) {
    // user
    $route->group(['prefix' => '/users', 'namespace' => 'User'], function ($route) {
        //route for add user
        $route->post('/register', 'RegisterController@store');
        //route for fetching login user details
        $route->get('/', ['uses' => 'UserController@show' , 'as' => 'me']);
        //route for updating user
        $route->post('/update-user/','UserController@updateUser');
        $route->post('/update-status/{id}', 'UserController@updateStatus');
        $route->post('/check-user','RegisterController@checkUser');
    });


    // auth
    $route->group(['prefix' => '/auth', 'namespace' => 'Auth'], function ($route) {
        $route->post('/login', 'LoginController@login');
        $route->post('/refresh', 'LoginController@refresh');
        $route->post('/social-auth', 'SocialAuthController@socialAuth');
    });

    // vision
    $route->group(['prefix' => '/vision', 'namespace' => 'Vision'], function ($route) {
        $route->get('/', 'VisionController@showAllVisions');
        $route->get('/overall-amount', 'VisionController@showSumAllVisionByFamilyCode');
        $route->get('/lists/', 'VisionController@showAllVisionsLists');
        //route for adding new vision
        $route->post('/add', 'VisionController@addVision');
        //route for showing vision categories by family
        $route->get('/categories/', 'VisionController@showAllCategories');
        $route->get('/show/{id}', 'VisionController@showAllVisionsPerCategory');
        //$route->get('/sample', 'VisionController@countAll');
        //route for updating vision
        $route->post('/update/{id}', 'VisionController@updateVision');
        $route->delete('/delete/{id}', 'VisionController@deleteVision' );
    });

    //News
    $route->group(['prefix' => '/news','namespace' => 'News'], function($route){

        $route->get('/','NewsController@getAll');
        
        $route->get('/{slug}','NewsController@viewData');
    });

    //Pauwinako
    $route->group(['prefix' => '/pauwinako','namespace' => 'Pauwinako'], function($route) {

        $route->get('/all' , 'PauwinakoController@getAll');
        $route->get('/view/{id}' , 'PauwinakoController@getSingle');
        
        $route->post('/setdate' , 'PauwinakoController@setDate');
        $route->post('/edit/{id}' , 'PauwinakoController@edit');
    });

    //family
    $route->group(['namespace' => 'Family', 'prefix' => '/family'], function ($route) {
        $route->post('/check-code',  'FamilyCodeController@checkFamilyCode');
        //route for creating family code
        $route->post('/code', ['uses' => 'FamilyController@store', 'as' => 'me.create_family']);
        //route for showing family code
        $route->get('/', ['uses' => 'FamilyController@show', 'as' => 'me.show_family']);
        //route for showing family members
        $route->get('/members', ['uses' => 'FamilyMemberController@index', 'as' => 'me.show_family_members']);
    });

    // investment
    $route->group(['prefix' => '/investment', 'namespace' => 'Investment'], function ($route) {
        //route for fetching investment levels
        $route->get('/',  'InvestmentController@showInvestmentLevel');        
        $route->get('/level-count',  'InvestmentController@countInvestmentLevel');
        $route->get('/investment-count',  'InvestmentController@countInvestment');
        //route for fetching all investments under investment level
        $route->get('/{id}','InvestmentController@showAllInvestment');
        //route for showing investment details
        $route->get('/{id}/{id_inv}','InvestmentController@showInvestmentDetails');
    });

    //contacts
    $route->group(['namespace' => 'Contact', 'prefix' => '/contact'], function ($route) {
        $route->get('/', 'ContactController@showContacts');
    });

    //comments
    $route->group(['namespace' => 'Comments', 'prefix' => '/comment'], function ($route) {
        $route->post('/add', 'CommentController@addComment');
    });

    //tips
    $route->group(['namespace' => 'Tips', 'prefix' => '/tips'], function ($route) {
        $route->get('/details/{code}', 'TipsController@showTipsDetails');
        $route->get('/category', 'TipsController@showAllCategory');
    });

    //balitahanan
    $route->group(['namespace' => 'Balitahanan', 'prefix' => '/balitahanan'], function ($route) {
       $route->post('/post', 'BalitahananController@addPost');
       $route->get('/', 'BalitahananController@showPost');
       $route->get('/{id}', 'BalitahananController@showPostByAdmin');

    });
});

//for users and admins
Route::middleware('auth:api')->group(function ($route){
    $route->post('/logout', 'Api\Auth\LoginController@logout');
});

// for admin route only
Route::middleware('auth.user:api,' . User::USER_TYPE_ADMIN)->group(function ($route){
    $route->group(['namespace' => 'Api'], function ($route) {
        //users admin
        $route->group(['prefix' => '/users', 'namespace' => 'User'], function ($route) {
            //route for user count
            $route->get('/count', 'UserController@showAllCount');
            //route for fetching all ofw users
            $route->get('/all', 'UserController@showAllUsers');
            //route for fetching all admin users
            $route->get('/all-admin', 'UserController@showAllAdmin');
            //route for showing user details
            $route->get('/{id}','UserController@showUser');
            //route for showing admin details
            $route->get('/admin/{id}','UserController@showAdmin');
            //route for deleting user
            $route->post('/delete/{id}', 'UserController@delete');
            //route for updating user
            //$route->put('/update-status/{id}', 'UserController@updateStatus');
            //route for updating user
            $route->put('/update/{id}','UserController@update');
            $route->post('/search','UserController@searchUser');
        });

        //invesments admin
        $route->group(['prefix' => '/investment', 'namespace' => 'Investment'], function ($route) {
            //route for adding investment level
            $route->post('/add', 'InvestmentController@addInvestmentLevel');
            //route for adding investment
            $route->post('/add-investment', 'InvestmentController@addInvestment');
            //route for updating investment level
            $route->post('/update/{id}', 'InvestmentController@updateInvestmentLevel');
            //route for updating investment
            $route->post('/investment-update/{id}', 'InvestmentController@updateInvestment');
            //route for deleting investment level
            $route->delete('/delete/{id}', 'InvestmentController@deleteInvestmentLevel');
            //route for deleting investment
            $route->delete('/investment-delete/{id}', 'InvestmentController@deleteInvestment');
        });

        // News admin
        $route->group(['prefix' => '/news','namespace' => 'News'], function($route){
        
            $route->post('/test/test1/','NewsController@sample');

            $route->get('/admin/all','NewsController@getAllAdmin');
            $route->get('/admin/{id}','NewsController@viewDataAdmin');

            $route->post('admin/create','NewsController@addNews');
            $route->post('admin/update/{id}','NewsController@update');
            $route->post('admin/delete/{id}','NewsController@destroy');
        });
        
        // News Category admin
        $route->group(['prefix' => '/newscat','namespace' => 'News'], function($route){
            
            $route->get('/all','NewsCategoryController@allData');
            $route->get('/{id}','NewsCategoryController@viewCat');

            $route->post('/create','NewsCategoryController@addNewsCateg');
            $route->post('/update/{id}','NewsCategoryController@updateNewsCateg');
            $route->post('/delete/{id}','NewsCategoryController@deleteNewsCateg');
        });

        //family admin
        $route->group(['namespace' => 'Family', 'prefix' => '/family'], function ($route) {
            //route for showing family members for specific OFW
            $route->get('/{id}/members', 'FamilyMemberController@memberList');
        });

        //Contact admin
        $route->group(['namespace' => 'Contact', 'prefix' => '/contact'], function ($route) {
            $route->post('/add', 'ContactController@addContacts');
            $route->post('/update/{id}', 'ContactController@updateContacts');
            $route->delete('/delete/{id}', 'ContactController@deleteContacts');

        });

        //Comments admin
        $route->group(['namespace' => 'Comments', 'prefix' => '/comment'], function ($route) {
            $route->get('/', 'CommentController@showComment');
            $route->get('/{id}', 'CommentController@showCommentById');
            $route->delete('/delete/{id}', 'CommentController@deleteComment');
        });

        //Vision admin
        $route->group(['prefix' => '/vision', 'namespace' => 'Vision'], function ($route) {
            $route->get('/count', 'VisionController@countAll');
        });

        // Dashboard admin
        $route->group(['prefix' => '/dashboard', 'namespace' => 'Dashboard'], function ($route) {
            $route->get('/', 'DashboardController@countAll');
        });

        // Tips admin
        $route->group(['namespace' => 'Tips', 'prefix' => '/tips'], function ($route) {
            $route->get('/all',  'TipsController@showAllTips');
            $route->post('/add', 'TipsController@addTips');
            $route->post('/update/{id}', 'TipsController@updateTips');
            $route->delete('/delete/{id}', 'TipsController@deleteTips');
        });
    });
});


