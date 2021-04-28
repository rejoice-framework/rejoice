<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Rejoice\Support\Eloquent;

/**
 * Base Test case.
 */
abstract class TestCase extends BaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        Eloquent::connect('test');
    }
}
