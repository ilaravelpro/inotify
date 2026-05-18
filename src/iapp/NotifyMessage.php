<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/19/20, 8:18 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iNotify\iApp;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

class NotifyMessage extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = 'INM';
    public static $s_start = 1155;
    public static $s_end = 18446744073709551615;
    protected $guarded = [];

    protected $casts = ["ips" => "array"];

    public function creator()
    {
        return $this->belongsTo(imodal('User'), 'creator_id');
    }

    public function user()
    {
        return $this->belongsTo(imodal('User'), 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(imodal('Role'), 'role_id');
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function kids()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function users()
    {
        return $this->belongsToMany(imodal("User"), 'notify_messages_users', "message_id", "user_id")->withPivot(["view_count", "ips", "read_at"]);
    }

    public function rules(Request $request, $action, $parent = null)
    {
        $rules = [];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    'user_id' => "nullable|exists:users,id",
                    'role_id' => "nullable|exists:roles,id",
                    'parent_id' => "nullable|exists:notify_messages,id",
                    'title' => "required|string",
                    'type' => "nullable|string",
                    'description' => "nullable|string",
                    'is_global' => "nullable|boolean",
                    'views' => "nullable|numeric|min:0",
                    'status' => 'nullable|in:' . join(',', $this->_statuses()),
                ]);
                break;
        }
        return $rules;
    }

    public function additionalUpdate($request = null, $additional = null, $parent = null)
    {
        parent::additionalUpdate($request, $additional, $parent);
    }
}
