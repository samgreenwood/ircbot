<?php namespace IrcBot\Commands;

interface Command
{
    /**
     * @return string
     */
    public function trigger();

    /**
     * @param array $args
     * @return string
     */
    public function run($args = []);

}