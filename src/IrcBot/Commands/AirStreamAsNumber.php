<?php namespace IrcBot\Commands;

class AirStreamAsNumber implements Command
{
    /**
     * @return string
     */
    public function trigger()
    {
        return "!as";
    }

    /**
     * @param array $args
     * @return string
     */
    public function run($args = [])
    {
        if (count($args) != 1) return "Invalid number of arguments.";

        return "Currently not supported.";
    }
}