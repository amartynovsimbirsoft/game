<?php

class Configuration
{
}

function conf(): Configuration
{
    static $conf;
    if ($conf === null) {
        $conf = new Configuration();
    }
    return $conf;
}
