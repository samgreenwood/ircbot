<?php

require 'vendor/autoload.php';

$server = 'irc.freenode.net';
$port = 6667;
$nick = 'botfty';
$channels = ['#goondev'];

$uri = sprintf("tcp://%s:%d", $server, $port);

$irc = new \Hoa\Irc\Client(new \Hoa\Socket\Client($uri));

$bot = new \IrcBot\Bot($irc, $nick, $channels);

$bot->addCommand(\IrcBot\Commands\PowerCommand::class);
$bot->addCommand(\IrcBot\Commands\AirStreamAsNumber::class);

if (count($argv) > 1) {
    array_shift($argv);
    $trigger = array_shift($argv);

    foreach ($bot->registeredCommands() as $command) {
        $command = new $command;
        if ($command->trigger() == $trigger) {
            echo $command->run($argv);
        }
    }
    return;
}

$bot->connect();