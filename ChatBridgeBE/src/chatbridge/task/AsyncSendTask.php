<?php

namespace chatbridge\task;

use pocketmine\Server;
use pocketmine\scheduler\AsyncTask;

use chatbridge\provider\SettingProvider;

class AsyncSendTask extends AsyncTask
{

	private $content;
	private $url;
	private $result;

	public function __construct($content)
	{
		$this->content = $content;
		$this->url = SettingProvider::get()->getURL();
	}

	public function onRun()
	{
        $options = [
                'http' => [
                              'method' => 'POST',
                              'header' => 'Content-Type: application/json',
                              'content' => json_encode($this->content),
                              'timeout' => 2
                        ]
                    ];
        $options['ssl']['verify_peer']=false;
        $options['ssl']['verify_peer_name']=false;
        @$this->result = file_get_contents($this->url, false, stream_context_create($options));
	}

	public function onCompletion(Server $server)
	{
		if($this->result === false) $server->getLogger()->info("§cDiscordBotとの接続に失敗しました");
	}

}