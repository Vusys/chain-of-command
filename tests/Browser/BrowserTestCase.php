<?php

declare(strict_types=1);

namespace Tests\Browser;

use Vusys\Tetryon\Laravel\BrowserTestCase as TetryonBrowserTestCase;
use Vusys\Zefram\ZeframAware;

abstract class BrowserTestCase extends TetryonBrowserTestCase
{
    // The served app runs out of process, where zefram can't see it, so zefram never
    // skips these tests (zefram#80). The trait keeps them in its run summary.
    use ZeframAware;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Under --parallel, Laravel names worker N's database "<name>_test_N" but only
        // creates it after a QueryException, and a missing SQLite file throws
        // something else. Create it, so the worker and its managed server share it.
        $token = getenv('TEST_TOKEN');
        if (is_string($token) && $token !== '') {
            touch(dirname(__DIR__, 2).'/database/tetryon.sqlite_test_'.$token);
        }
    }
}
