<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Vusys\Zefram\ZeframAware;

abstract class TestCase extends BaseTestCase
{
    // Skips a test whose executed files are unchanged since it last passed.
    use ZeframAware;
}
