<?php

declare(strict_types=1);
require_once dirname(__DIR__).'/vendor/autoload.php';
if (class_exists(\MyCLabs\Enum\PHPUnit\Comparator::class)) {
    \SebastianBergmann\Comparator\Factory::getInstance()->register(new \MyCLabs\Enum\PHPUnit\Comparator());
}
