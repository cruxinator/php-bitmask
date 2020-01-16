<?php
require_once(dirname(__DIR__ ). '/vendor/autoload.php');

\SebastianBergmann\Comparator\Factory::getInstance()->register(new \MyCLabs\Enum\PHPUnit\Comparator());
