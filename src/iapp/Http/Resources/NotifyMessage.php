<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/15/20, 1:10 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iNotify\iApp\Http\Resources;

use iLaravel\Core\iApp\Http\Resources\Resource;

class NotifyMessage extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        if ($request->has("is_me")) {
            if ($user = $this->resource->users->where("pivot.user_id", auth()->id())->where("pivot.view_count", ">", 0)->first()) {
                $data["status"] = "viewed";
                $data["read_at"] = format_datetime($user->getOriginal("pivot_read_at"), $this->resource->datetime??[], "created_at", ipreference('lang'));
            }else {
                $data["status"] = "unread";
            }
            unset($data['creator_id'], $data["actions"], $data["users"]);
        }
        if (isset($data['status'])) $data['status_text'] = _t($data["status"]);
        return $data;
    }
}
