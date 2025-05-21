<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;

class Session extends Model
{
    protected $table = 'sessions';
    public  $incrementing = false;
    protected $hiddem = ['playload'];

    protected $fillable =
    [
        'id',
        'user_id',
        'user_agent',
        'ip_address',
        'payload',
        'last_activity',
        'is_this_device',
    ];

    protected $appends = ['is_this_device'];
    public function getLastActivityAttribute($value)
    {
        return Carbon::createFromTimestamp($value)->diffForHumans();
    }
    public function getUserAgentAttribute($value) {
        $agent = new Agent();
        $agent->setUserAgent($value);
        return [
            'platform' => $agent->platform(),
            'browser'=>$agent->browser(),
            'is_disktop' => $agent->is_disktop(),
        ];
    }
    public function getIsThisDeviceِAttribute(){
        return $this->id = request()->session()->getId();
    }
}
