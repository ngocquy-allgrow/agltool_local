<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// event webhook
Route::post('/tool/slack_translate_v1/webhookevent2', 'Tool\SlackTranslateV1\SlackTranslateV1WebhookController@webhookevent')->name('slack_translate_v1_webhook_event');

Route::post('/tool/chatwork_translate_v4/autobot', 'Tool\ChatWorkTranslateV3\ChatWorkTranslateV4CoreController@autobot')->name('chatwork_translate_v4_autobot');

// check code
Route::post('/tool/check_frontend_code/save_file_screen_shot', 'Tool\CheckFrontEndCode\CheckFrontEndCodeReportController@savefileScreenShot')->name('checkfrontendcode_save_file_screen_shot');
