<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/19/20, 8:32 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iNotify\iApp\Http\Controllers\API\v1;

use Carbon\Carbon;
use iLaravel\Core\iApp\Exceptions\iException;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\Core\iApp\Http\Controllers\API\ApiController;


class NotifyMessageController extends ApiController
{
    public $statusFilter = false;

    public function filters($request, $model, $parent = null, $operators = [])
    {
        $filters = [
            [
                'name' => 'all',
                'title' => _t('all'),
                'type' => 'text',
            ],
            [
                'name' => 'title',
                'title' => _t('title'),
                'type' => 'text'
            ],
            [
                'name' => 'description',
                'title' => _t('description'),
                'type' => 'text'
            ],
            [
                'name' => 'type',
                'title' => _t('type'),
                'type' => 'text'
            ],
            [
                'name' => 'user_id',
                'title' => _t('user'),
                'type' => 'text'
            ],
            [
                'name' => 'role_id',
                'title' => _t('role'),
                'type' => 'text'
            ]
        ];
        if ($request->has("is_me") && $request->is_me == 1) {
            $model->with("users");
        }
        if ($request->has("time")) {
            $model->where("created_at", ">=", Carbon::createFromTimestamp($request->time));
        }
        if ($request->has("is_unread")) {
            $model->whereDoesntHave("users", function ($query) {
                $query->where("user_id", auth()->id());
            });
        }
        return [$filters, [], $operators];
    }

    public function _queryIndex($request, $parent = null)
    {
        $result = parent::_queryIndex($request, $parent);
        if ($request->has("is_unread")) {
            foreach ($result[1] as $item) {
                try {
                    if (!($item->users->where("pivot.user_id", auth()->id())->count()))
                        $item->users()->attach([auth()->id() => ["view_count" => 0]]);
                } catch (\Exception $exception) {}
            }
        }
        return $result;
    }

    public function received(Request $request, $record)
    {

    }

    public function read(Request $request, $record)
    {
        if ($item = $this->model::findBySerial($record)) {
            $user = $item->users()->wherePivot('user_id', auth()->id())->first();
            $ips = empty(@$user->pivot["ips"]) ? [] : @$user->pivot["ips"];
            $ips = is_array($ips) ? $ips : explode(",", $ips);
            $data = [auth()->id() => ['view_count' => ($user->pivot["view_count"] ?? 0) + 1, "ips" => implode(",", array_unique(array_merge($request->ips(), $ips))), "read_at" => $user->pivot["read_at"] ?? now()->format('Y-m-d H:i:s')]];
            $item->users()->detach([auth()->id()]);
            $item->users()->attach($data);
            $item->update(["views" => $item->users()->count()]);
            $request->merge(["is_me" => 1]);
            \request()->merge(["is_me" => 1]);
            return $this->_show($request, $item);
        }
        throw new iException("Not found");
    }
}
