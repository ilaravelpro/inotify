<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/19/20, 8:20 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

Route::namespace('v1')->prefix('v1')->middleware('authIf:api')->group(function () {
    Route::apiResource('notify_messages', 'NotifyMessageController', ['as' => 'api']);
    Route::get('notify_messages/{record}/read', 'NotifyMessageController@read')->name('api.notify_messages.read');
    Route::get('notify_messages/{record}/receive', 'NotifyMessageController@receive')->name('api.notify_messages.receive');
});
