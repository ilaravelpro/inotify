<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/21/20, 12:47 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iNotify\iApp\Policies;

use iLaravel\Core\Vendor\iRole\iRolePolicy;

class NotifyMessagePolicy extends iRolePolicy
{
    public $prefix = 'notify_messages';
    public $model = 'NotifyMessage';

    public function received($user, $item, ...$args)
    {
        return true;
    }

    public function read($user, $item, ...$args)
    {
        return true;
    }
}
