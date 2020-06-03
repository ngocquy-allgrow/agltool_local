<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// DEV ROUTE
Route::get('reset', function (Request $request) {
	// remove all session 
	Session::flush();

	/* migration */
	\Artisan::call('migrate:reset', ['--force' => true]);
    \Artisan::call('migrate');

    /* create seed */
    \Artisan::call('db:seed');

	/* success */
    return redirect('/');
});

Route::get('create_cwtable', function (Request $request) {
    Schema::dropIfExists('tool_chatwork_configs');
    Schema::create('tool_chatwork_configs', function($table) {
        $table->bigIncrements('id');
        $table->bigInteger('user_id');
        $table->bigInteger('account_id');
        $table->longText('account_name');
        $table->longText('room_id_array')->nullable();
        $table->longText('token');
        $table->timestamps();
    });
});

Route::get('create_cktable', function (Request $request) {
	Schema::dropIfExists('info_sourcecodes');
	Schema::create('info_sourcecodes', function($table) {
        $table->bigIncrements('id');
        $table->string('name');
        $table->longText('result');
        $table->longText('result_css');
        $table->timestamps();
    });
});

// ADMIN ROUTE
Route::get('/manager_users', 'Admin\UsersManagerController@index')->name('manager_users');
Route::post('/manager_users/add', 'Admin\UsersManagerController@add')->name('add_users');
Route::post('/manager_users/edit', 'Admin\UsersManagerController@edit')->name('edit_users');
Route::post('/manager_users/delete', 'Admin\UsersManagerController@delete')->name('delete_users');
Route::post('/manager_users/resetPassword', 'Admin\UsersManagerController@resetPassword')->name('reset_password_users');

Route::get('/manager_tools', 'Admin\ToolsManagerController@index')->name('manager_tools');
Route::post('/manager_tools/add', 'Admin\ToolsManagerController@add')->name('add_tools');
Route::post('/manager_tools/edit', 'Admin\ToolsManagerController@edit')->name('edit_tools');
Route::post('/manager_tools/delete', 'Admin\ToolsManagerController@delete')->name('delete_tools');
Route::post('/manager_tools/upload_image', 'Admin\ToolsManagerController@uploadImage')->name('edit_tools_uploadfile');

// USER ROUTE
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home2');
Route::get('/changePassword', 'HomeController@changePassword')->name('changePassword');
Route::post('/changePassword', 'HomeController@changePasswordFunc')->name('changePasswordFunc');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

// tool check source code
Route::get('/tool/check_frontend_code', 'Tool\CheckFrontEndCode\CheckFrontEndCodeController@index')->name('checkfrontendcode_index');
Route::post('/tool/check_frontend_code/upload_source', 'Tool\CheckFrontEndCode\CheckFrontEndCodeController@uploadSource')->name('checkfrontendcode_upload');
Route::post('/tool/check_frontend_code/upload_upload_file', 'Tool\CheckFrontEndCode\CheckFrontEndCodeController@uploadFile')->name('checkfrontendcode_upload_file');
Route::post('/tool/check_frontend_code/choose_design', 'Tool\CheckFrontEndCode\CheckFrontEndCodeController@chooseDesign')->name('checkfrontendcode_choose_design');
Route::post('/tool/check_frontend_code/delete_design', 'Tool\CheckFrontEndCode\CheckFrontEndCodeController@deleteDesign')->name('checkfrontendcode_delete_design');
Route::post('/tool/check_frontend_code/compare', 'Tool\CheckFrontEndCode\CheckFrontEndCodeController@compare')->name('checkfrontendcode_compare');
Route::post('/tool/check_frontend_code/checkvalidation', 'Tool\CheckFrontEndCode\CheckFrontEndCodeController@checkValidation')->name('checkfrontendcode_checkvalidation');
Route::get('/tool/check_frontend_code/report/{id}', 'Tool\CheckFrontEndCode\CheckFrontEndCodeReportController@report')->name('checkfrontendcode_report');
Route::get('/tool/check_frontend_code/delete/{id}', 'Tool\CheckFrontEndCode\CheckFrontEndCodeController@delete')->name('checkfrontendcode_delete');

// tool chatwork translate 3 route
Route::get('/tool/chatwork_translate_v3', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV3Controller@index')->name('chatwork_translate_v3_index');
Route::get('/tool/chatwork_translate_v3/detail', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV3Controller@detail')->name('chatwork_translate_v3_detail');
Route::post('/tool/chatwork_translate_v3/register', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV3Controller@register')->name('chatwork_translate_v3_register');
Route::get('/tool/chatwork_translate_v3/logout', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV3Controller@logout')->name('chatwork_translate_v3_logout');
Route::post('/tool/chatwork_translate_v3/updateRoom', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV3Controller@updateRoomChatworkAccount')->name('chatwork_translate_v3_updateRoom');

Route::get('/tool/chatwork_translate_v4/cronjob_index', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV4CoreController@index')->name('chatwork_translate_v4_cronjob_index');
Route::get('/tool/chatwork_translate_v4/cronjob', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV4CoreController@cronjob')->name('chatwork_translate_v4_cronjob');
Route::get('/tool/chatwork_translate_v4/admin', 'Tool\ChatWorkTranslateV3\ChatWorkAdminController@index')->name('chatwork_admin_index');
Route::get('/tool/chatwork_translate_v4/admin/{room_id}', 'Tool\ChatWorkTranslateV3\ChatWorkAdminController@detail')->name('chatwork_admin_detail');
Route::post('/tool/chatwork_translate_v4/editMsg', 'Tool\ChatWorkTranslateV3\ChatWorkAdminController@editMessage')->name('chatwork_admin_edit_message');
Route::post('/tool/chatwork_translate_v4/delMsg', 'Tool\ChatWorkTranslateV3\ChatWorkAdminController@delMessage')->name('chatwork_admin_del_message');
// Route::get('/tool/chatwork_translate_v4/deleteRoom', 'Tool\ChatWorkTranslateV3\ChatWorkAdminController@deleteRoom')->name('chatwork_admin_del_room');



Route::get('/tool/chatwork_translate_v5/cronjob_index', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV5CoreController@index')->name('chatwork_translate_v5_cronjob_index');
Route::get('/tool/chatwork_translate_v5/cronjob', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV5CoreController@cronjob')->name('chatwork_translate_v5_cronjob');
Route::post('/tool/chatwork_translate_v5/translateMessage', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV5CoreController@autoTranslateMessage')->name('chatwork_admin_translate_message');
Route::post('/tool/chatwork_translate_v5/postMessage', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV5CoreController@postMessage')->name('chatwork_post_message');


// slack translation
Route::get('/tool/slack_translate_v1', 'Tool\SlackTranslateV1\SlackTranslateV1Controller@index')->name('slack_translate_v1_index');
Route::get('/tool/slack_translate_v1/editlang', 'Tool\SlackTranslateV1\SlackTranslateV1Controller@edit')->name('slack_translate_v1_edit_lang');
Route::get('/tool/slack_translate_v1/delete', 'Tool\SlackTranslateV1\SlackTranslateV1Controller@delete')->name('slack_translate_v1_delete');

Route::get('/tool/slack_translate_v1/manager_slack', 'Tool\SlackTranslateV1\SlackTranslateV1Controller@admin')->name('manager_slack');
Route::get('/tool/slack_translate_v1/editlang_admin', 'Tool\SlackTranslateV1\SlackTranslateV1Controller@edit_admin')->name('slack_translate_v1_edit_lang_admin');
Route::get('/tool/slack_translate_v1/delete_admin', 'Tool\SlackTranslateV1\SlackTranslateV1Controller@delete_admin')->name('slack_translate_v1_delete_admin');
// resgiter
Route::get('/tool/slack_translate_v1/resgiter', 'Tool\SlackTranslateV1\SlackTranslateV1WebhookController@resgiter')->name('slack_translate_v1_webhook_resgiter');
Route::get('/tool/slack_translate_v1/readlog', 'Tool\SlackTranslateV1\SlackTranslateV1Controller@readlog')->name('slack_translate_v1_readlog');


// social
Route::get('/social/questions', 'Social\Questions\QuestionsController@index')->name('social_question_index');



