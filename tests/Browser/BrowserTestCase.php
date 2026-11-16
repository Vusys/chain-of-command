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
}
