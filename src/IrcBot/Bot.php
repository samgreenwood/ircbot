<?php namespace IrcBot;

use Hoa\Core\Event\Bucket;
use Hoa\Irc\Client;
use Illuminate\Support\Str;
use IrcBot\Commands\Command;

class Bot
{
    /**
     * @var Client
     */
    private $irc;

    /**
     * @var string
     */
    private $nick;

    /**
     * @var array
     */
    private $channels;

    /**
     * @var array
     */
    private $commands;

    /**
     * Bot constructor.
     * @param Client $irc
     * @param $nick
     * @param array $channels
     * @param array $commands
     */
    public function __construct(Client $irc, $nick, $channels = [], $commands = [])
    {
        $this->irc = $irc;
        $this->channels = $channels;
        $this->commands = $commands;
        $this->nick = $nick;
    }

    /**
     * Connect to IRC
     */
    public function connect()
    {
        $channels = $this->channels;

        $this->irc->on('open', function (Bucket $bucket) use ($channels) {
            foreach ($channels as $channel) {
                $bucket->getSource()->join($this->nick, $channel);
            }
            return;
        });

        $this->irc->on('invite', function (Bucket $bucket) use ($channels) {
            $data = $bucket->getData();
            $bucket->getSource()->join($this->nick, $data['invitation_channel']);
            return;
        });

        $this->irc->on('private-message', function (Bucket $bucket) {
            $data = $bucket->getData();
            $message = $data['message'];

            if (Str::startsWith($message, 'join')) {
                $channel = explode(' ', $message)[1];
                $bucket->getSource()->join($this->nick, $channel);
            }

            return;
        });

        $this->irc->on('message', function (Bucket $bucket) {
            $data = $bucket->getData();
            $message = trim($data['message']);

            foreach ($this->commands as $command) {

                if (Str::startsWith($message, $command->trigger())) {

                    $args = array_values(array_filter(explode(' ', $message), function ($item) use ($command) {
                        return $item != $command->trigger();
                    }));

                    try {
                        $response = $command->run($args);
                        $bucket->getSource()->say($response);
                    } catch (\Exception $e) {
                        $bucket->getSource()->say("Something went wrong " . $e->getMessage());
                    }
                }
            }
            return;
        });

        $this->irc->run();
    }

    /**
     * @param Command $command
     */
    public function addCommand(Command $command)
    {
        $this->commands[] = $command;
    }
}