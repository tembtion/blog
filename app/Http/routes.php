<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['namespace' => 'Home'], function () {
    //首页
    Route::get('/', ['uses' => 'TopController@index', 'as' => 'homeTopIndex']);

    Route::get('/ajax', ['uses' => 'TopController@ajax', 'as' => 'homeTopAjax']);

    Route::get('/post/search', ['uses' => 'PostController@search', 'as' => 'homePostSearch']);

    Route::get('/post/search/ajax', ['uses' => 'PostController@searchAjax', 'as' => 'homePostSearchAjax']);

    Route::get('/post/{post_id}.html', ['uses' => 'PostController@index', 'as' => 'homePostIndex']);

    //评论列表
    Route::get('/comment/index', ['uses' => 'CommentController@index', 'as' => 'homeCommentIndex']);
    //评论添加
    Route::post('/comment/post', ['uses' => 'CommentController@post', 'as' => 'homeCommentPost']);
    //评论删除
    Route::post('/comment/delete', ['uses' => 'CommentController@delete', 'as' => 'homeCommentDelete']);
    //评论编辑
//    Route::post('/comment/edit', ['uses' => 'CommentController@edit', 'as' => 'homeCommentEdit']);
    //标签列表
    Route::get('/tag/{term_id}.html', ['uses' => 'TermController@tag', 'as' => 'homeTagIndex']);
    //标签加载更多
    Route::get('/tag/ajax', ['uses' => 'TermController@tagAjax', 'as' => 'homeTagAjaxIndex']);
    //分类列表
    Route::get('/category/{term_id}.html', ['uses' => 'TermController@category', 'as' => 'homeCategoryIndex']);
    //分类加载更多
    Route::get('/category/ajax', ['uses' => 'TermController@categoryAjax', 'as' => 'homeCategoryAjaxIndex']);

    Route::get('/profile/{user_id?}.html', ['uses' => 'ProfileController@index', 'as' => 'homeProfileIndex']);

    Route::get('/message.html', ['uses' => 'MessageController@index', 'as' => 'homeMessageIndex']);

    //相册页面
    Route::get('/album/index.html', ['uses' => 'AlbumController@index', 'as' => 'homeAlbumIndex']);
    //相册加载更多
    Route::get('/album/item', ['uses' => 'AlbumController@item', 'as' => 'homeAlbumItem']);
    //图片
    Route::get('/album/{album_id}.html', ['uses' => 'AlbumController@detail', 'as' => 'homeAlbumDetail']);
    //图片加载更多
    Route::get('/photo/item', ['uses' => 'PhotoController@item', 'as' => 'homePhotoItem']);
});

Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
    // Authentication routes...
    Route::get('login', ['uses' => 'AuthController@getLogin', 'as' => 'authAuthGetLogin']);
    Route::post('login', ['uses' => 'AuthController@postLogin', 'as' => 'authAuthPostLogin']);
//    Route::get('register', ['uses' => 'AuthController@getRegister', 'as' => 'authAuthGetRegister']);
//    Route::post('register', ['uses' => 'AuthController@postRegister', 'as' => 'authAuthPostRegister']);
    Route::get('logout', ['uses' => 'AuthController@getLogout', 'as' => 'authAuthGetLogout']);

    //微博登陆
    Route::any('login/weibo', ['uses' => 'AuthController@loginByWeibo', 'as' => 'authAuthLoginByWeibo']);
    // 用户管理页面
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', ['uses' => 'TopController@home', 'as' => 'authTopHome']);
        Route::get('/index', ['uses' => 'TopController@index', 'as' => 'authTopIndex']);

        Route::get('/profile', ['uses' => 'ProfileController@index', 'as' => 'authProfileIndex']);
        Route::post('/profile/avatar/upload', ['uses' => 'ProfileController@avatarUpload', 'as' => 'authProfileAvatarUpload']);
        Route::post('/profile/edit', ['uses' => 'ProfileController@edit', 'as' => 'authProfileEdit']);
        Route::post('/profile/passwordreset', ['uses' => 'ProfileController@passwordreset', 'as' => 'authProfilePasswordreset']);

        //文章
        Route::group(['prefix' => 'post'], function () {
            Route::get('/index', ['uses' => 'PostController@index', 'as' => 'authPostIndex']);
            Route::get('/create/{post_id?}', ['uses' => 'PostController@create', 'as' => 'authPostCreate']);
            Route::post('/add', ['uses' => 'PostController@add', 'as' => 'authPostAdd']);
            Route::post('/edit', ['uses' => 'PostController@edit', 'as' => 'authPostEdit']);
            Route::post('/delete', ['uses' => 'PostController@delete', 'as' => 'authPostDelete']);
            Route::any('/ueditor', ['uses' => 'PostController@ueditor', 'as' => 'authPostUeditor']);
        });

        //分类
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', ['uses' => 'CategoryController@index', 'as' => 'authCategoryIndex']);
            Route::post('/add', ['uses' => 'CategoryController@add', 'as' => 'authCategoryAdd']);
            Route::post('/edit', ['uses' => 'CategoryController@edit', 'as' => 'authCategoryEdit']);
            Route::post('/delete', ['uses' => 'CategoryController@delete', 'as' => 'authCategoryDelete']);
        });

        //标签
        Route::group(['prefix' => 'tag'], function () {
            Route::get('/', ['uses' => 'TagController@index', 'as' => 'authTagIndex']);
            Route::post('/add', ['uses' => 'TagController@add', 'as' => 'authTagAdd']);
            Route::post('/edit', ['uses' => 'TagController@edit', 'as' => 'authTagEdit']);
            Route::post('/delete', ['uses' => 'TagController@delete', 'as' => 'authTagDelete']);
        });

        //相册
        Route::group(['prefix' => 'album'], function () {
            Route::get('/', ['uses' => 'AlbumController@index', 'as' => 'authAlbumIndex']);
            Route::post('/add', ['uses' => 'AlbumController@add', 'as' => 'authAlbumAdd']);
            Route::post('/edit', ['uses' => 'AlbumController@edit', 'as' => 'authAlbumEdit']);
            Route::post('/delete', ['uses' => 'AlbumController@delete', 'as' => 'authAlbumDelete']);
            Route::get('/{album_id}', ['uses' => 'AlbumController@detail', 'as' => 'authAlbumDetail']);
        });

        Route::group(['prefix' => 'photo'], function () {
            Route::post('/upload', ['uses' => 'PhotoController@upload', 'as' => 'authPhotoUpload']);
            Route::post('/delete', ['uses' => 'PhotoController@delete', 'as' => 'authPhotoDelete']);
        });

        //相册
        Route::group(['prefix' => 'media'], function () {
            Route::get('/', ['uses' => 'MediaController@index', 'as' => 'authMediaIndex']);
            Route::get('/add', ['uses' => 'MediaController@add', 'as' => 'authMediaAdd']);
            Route::post('/upload', ['uses' => 'MediaController@upload', 'as' => 'authMediaUpload']);
        });

        //评论
        Route::group(['prefix' => 'comment'], function () {
            Route::get('/', ['uses' => 'CommentController@index', 'as' => 'authCommentIndex']);
            Route::post('/delete', ['uses' => 'CommentController@delete', 'as' => 'authCommentDelete']);
        });
    });
});

