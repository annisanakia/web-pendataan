<?php

namespace Lib;

class activity
{
    public function addActivity($user_id, $object, $object_id, $activity, $activity_date, $activity_after = null, $activity_before = null)
    {
        $input = [
            'user_id' => $user_id,
            'object' => $object,
            'object_id' => $object_id,
            'activity' => $activity,
            'activity_date' => $activity_date,
            'activity_after' => $activity_after,
            'activity_before' => $activity_before
        ];
        \Models\log_activity::create($input);
    }
}