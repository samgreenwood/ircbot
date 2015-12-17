<?php

require 'vendor/autoload.php';

$server = 'localhost';
$port = 6667;
$nick = 'bot';
$channels = ['#dev'];

$uri = sprintf("tcp://%s:%d", $server, $port);

$irc = new \Hoa\Irc\Client(new \Hoa\Socket\Client($uri));

$bot = new \IrcBot\Bot($irc, $nick, $channels);

$bot->addCommand(new \IrcBot\Commands\PowerCommand());
$bot->addCommand(new \IrcBot\Commands\AirStreamAsNumber());

$bot->connect();