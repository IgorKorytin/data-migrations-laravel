<?php

namespace AvtoDev\DataMigrationsLaravel\Tests\Commands;

use AvtoDev\DataMigrationsLaravel\Tests\AbstractTestCase;

abstract class AbstractCommandTestCase extends AbstractTestCase
{
    /**
     * Check command exists test.
     *
     * @return void
     */
    public function testHelpCommand()
    {
        $this->assertNotFalse(
            $this->artisan($signature = $this->getCommandSignature(), ['--help']),
            sprintf('Command "%s" does not return help message', $signature)
        );
    }

    /**
     * Command signature.
     *
     * @return string
     */
    abstract protected function getCommandSignature();
}
