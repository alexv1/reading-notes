<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
 * 获取登陆界面
 */
Route::get('/login', function()
{
    return View::make('login.login');
});

/*
 * 退出登录
 */
Route::get('/logout', function()
{
    $backend_token=Cookie::get('backend_token');
    if(empty($backend_token)){
        return Redirect::guest('login');
    }else{
        $token=array();
        $token['user_name']=json_decode(base64_decode(Cookie::get('backend_token')))->user_name;
        $token['expiresAt']=date("Y-m-d H:i:s",time()-100);//设置登录的时间为过期时间
        Cookie::queue('backend_token',base64_encode(json_encode($token)));
        return Redirect::guest('login');
    }
});

/*
 * 验证登陆用户
 */
Route::post('/user/login','UserController@login');

Route::any('/api/user/login','ApiController@doLogin');
Route::any('/api/user/islogin','ApiController@isLogin');

Route::any('/api/shelf/home','ApiController@getShelfIndex');
Route::any('/api/shelf/status','ApiController@getMyBooksByStatus');
Route::any('/api/shelf/booklist','ApiController@getBooklists');
Route::any('/api/shelf/note','ApiController@getNotes');

Route::any('/api/book/{bid}','ApiController@getMyBookById');
Route::any('/api/book/{bid}/notes','ApiController@getMyNotesByBook');
Route::any('/api/book/action/change_read_status','BookController@changeReadState');

Route::any('/api/note/{nid}','ApiController@getNoteById');
Route::any('/api/booklist/{blid}','ApiController@getBooklistById');
Route::any('/api/comment/action/update','BookController@updateBookComment');
Route::any('/api/note/action/add','NoteController@doAddNoteInJson');
Route::any('/api/note/action/delete','NoteController@doDelete');

Route::any('/api/search','ApiController@doSearch');



Route::any('/api/booklist/{bid}','ApiController@getBooklist');


/*
 * 用户注册
 */
Route::get('/user/register','UserController@register');

//Route::get('/test','BookController@testApi');
Route::any('/test', 'BookController@doAdd');

Route::any('/uploadToken', 'QiniuController@uploadToken');
Route::any('/convertFile', 'QiniuController@convertFile');

Route::any('/api/book/action/getTidBySidInJson', 'CategoryController@getTidBySidInJson');

// 登录验证
Route::group(array('before' => 'auth'), function() {
    Route::get('/', 'DashboardController@root');

    Route::get('/dashboard/index', 'BookController@toMyListView');

    // 书籍检索
    Route::any('/search/book', 'SearchController@toSearchNameView');
    // 标签检索
    Route::any('/search/tag', 'SearchController@toSearchTagView');
    // 二级分类检索
    Route::any('/search/category_2', 'SearchController@toSearchSecondCategoryView');

    Route::any('/search/category_3', 'SearchController@toSearchThirdCategoryView');

    // 获得 所有2级节点
    Route::any('/category/2_all', 'CategoryController@');



    // 我的
    Route::any('/book/my_list', 'BookController@toMyListView');
    Route::get('/book/my_view', 'BookController@toMyView');
    Route::any('/book/do_list', 'BookController@toDoListView');
    Route::any('/book/wish_list', 'BookController@toWishListView');
    Route::any('/book/collect_list', 'BookController@toCollectListView');

    Route::any('/comment/my_list', 'CommentController@toMyListView');
    Route::get('/comment/my_view', 'CommentController@toMyView');
    Route::any('/note/my_list', 'NoteController@toMyListView');
    Route::get('/note/my_view', 'NoteController@toMyView');
    Route::any('/booklist/my_list', 'BooklistController@toMyListView');
    Route::any('/booklist/my_view', 'BooklistController@toMyView');

    // 书架
    Route::get('/book/all_list', 'BookController@toAllListView');

    Route::get('/book/view', 'BookController@toView');

    Route::get('/book/add_view', 'BookController@toAddView');
    Route::any('/book/add', 'BookController@doAdd');
    Route::get('/book/edit_view', 'BookController@toEditView');
    Route::post('/book/edit', 'BookController@doEdit');
    Route::post('/book/delete', 'BookController@doDelete');
    Route::any('/book/disable', 'BookController@doDisable');

    // 书评
    Route::get('/comment/all_list', 'CommentController@toAllListView');

    Route::get('/comment/view', 'CommentController@toView');
    Route::get('/comment/add_view', 'CommentController@toAddView');
    Route::post('/comment/add', 'CommentController@doAdd');
    Route::get('/comment/edit_view', 'CommentController@toEditView');
    Route::post('/comment/edit', 'CommentController@doEdit');
    Route::post('/comment/delete', 'CommentController@doDelete');
    Route::any('/comment/disable', 'CommentController@doDisable');

    // 笔记
    Route::get('/note/all_list', 'NoteController@toAllListView');

    Route::get('/note/view', 'NoteController@toView');
    Route::get('/note/add_view', 'NoteController@toAddView');
    Route::post('/note/add', 'NoteController@doAdd');
    Route::get('/note/edit_view', 'NoteController@toEditView');
    Route::post('/note/edit', 'NoteController@doEdit');
    Route::post('/note/delete', 'NoteController@doDelete');
    Route::post('/note/public', 'NoteController@doPublic');
    Route::post('/note/private', 'NoteController@doPrivate');

    // 书单
    Route::get('/booklist/all_list', 'BooklistController@toAllListView');
    Route::get('/booklist/my_list', 'BooklistController@toMyListView');
    Route::get('/booklist/view', 'BooklistController@toView');
    Route::post('/booklist/add', 'BooklistController@doAdd');
    Route::get('/booklist/edit', 'BooklistController@toEditView');
    Route::post('/booklist/update', 'BooklistController@update');
    Route::post('/booklist/delete', 'BooklistController@doDelete');
    Route::post('/booklist/attachBook', 'BooklistController@attachBookToBooklist');
    Route::get('/booklist/detachBook', 'BooklistController@detachBookFromBooklist');

    // 丛书
    Route::get('/series/all_list', 'SeriesController@toAllListView');
    Route::get('/series/my_list', 'SeriesController@toMyListView');
    Route::get('/series/view', 'SeriesController@toView');
    Route::get('/series/add_view', 'SeriesController@toAddView');
    Route::post('/series/add', 'SeriesController@doAdd');
    Route::get('/series/edit_view', 'SeriesController@toEditView');
    Route::post('/series/edit', 'SeriesController@doEdit');
    Route::post('/series/delete', 'SeriesController@doDelete');

    // 标签
    Route::get('/tag/all_list', 'TagController@toAllListView');
    Route::get('/tag/my_list', 'TagController@toMyListView');
    Route::get('/tag/view', 'TagController@toView');
    Route::get('/tag/add_view', 'TagController@toAddView');
    Route::post('/tag/add', 'TagController@doAdd');
    Route::get('/tag/edit_view', 'TagController@toEditView');
    Route::post('/tag/edit', 'TagController@doEdit');
    Route::post('/tag/delete', 'TagController@doDelete');


    // 统计
    Route::any('/stats/read_list', 'StatsController@toReadListView');
    Route::get('/stats/tag_list', 'StatsController@toTagListView');
    Route::get('/stats/category_list', 'StatsController@toCategoryListView');

    Route::get('/book/getMyBookCount', 'BookController@getMyBookCount');
    Route::post('/book/changeReadState', 'BookController@changeReadState');
    Route::post('/book/updateBookComment', 'BookController@updateBookComment');
    Route::post('/book/importDoubanBook', 'BookController@importDoubanBook');

    Route::post('/note/draft_save', 'NoteController@saveNoteDraft');

});
