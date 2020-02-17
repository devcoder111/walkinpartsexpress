<?php

namespace App\Services;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use wrapi\slack\slack as Slack;

class SlackService
{
    protected $slackHookUrl;
    protected $slack;

    public function __construct()
    {
        $this->slack = new Slack(env('SLACK_API_TOKEN'));
        $this->slackHookUrl = env('SLACK_HOOK_URL');
    }
}
