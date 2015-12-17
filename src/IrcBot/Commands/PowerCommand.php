<?php namespace IrcBot\Commands;

class PowerCommand implements Command
{
    /**
     * @return string
     */
    public function trigger()
    {
        return "!power";
    }

    /**
     * @param array $args
     * @return string
     */
    public function run($args = [])
    {
        $response = "Power Info: ";

        $json = file_get_contents("https://outage.apps.sapowernetworks.com.au/OutageReport/AllCurrentOutages/?searchCriteria=&requireLatLong=false&AspxAu");
        $outages = json_decode($json, true);

        foreach ($outages as $outage) {
            $response .= sprintf("Suburb: %s ", $outage['suburb']);
        }

        return $response;
    }
}