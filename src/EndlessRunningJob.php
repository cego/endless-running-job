<?php

namespace Nbj;

use Nbj\Stopwatch;

abstract class EndlessRunningJob
{
    /**
     * How long it should take, as a minimum, for a cycle to repeat
     *
     * @var int
     */
    protected $minimumCycleTime = 100000;

    /**
     * Starts the endless loop
     *
     * @param bool $runOnce Pass true to test the function
     *
     * @return void
     */
    public function start($runOnce = false)
    {
        do {
            $processTime = Stopwatch::time(function () {
                $this->process();
            });

            $waitTime = $this->minimumCycleTime < $processTime->microseconds()
                ? 0
                : $this->minimumCycleTime - $processTime->microseconds();

            usleep($waitTime);
        } while ($runOnce == false);
    }

    /**
     * The process which runs in each cycle
     *
     * @return void
     */
    abstract protected function process();
}
