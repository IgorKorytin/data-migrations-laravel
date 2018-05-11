<?php

namespace AvtoDev\DataMigrationsLaravel\Tests\Commands;

use Mockery as m;
use RuntimeException;
use AvtoDev\DataMigrationsLaravel\Contracts\RepositoryContract;

class InstallCommandTest extends AbstractCommandTestCase
{
    /**
     * @var RepositoryContract
     */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->app->make(RepositoryContract::class);
    }

    /**
     * Test regular command execution.
     *
     * @return void
     */
    public function testCommandExecution()
    {
        $this->repository->deleteRepository();

        $this->artisan($this->getCommandSignature());
        $this->assertContains('Repository created successfully', $this->console()->output());

        $this->artisan($this->getCommandSignature());
        $this->assertContains('Repository already exists in your database', $this->console()->output());
    }

    /**
     * Test exception throwing.
     *
     * @return void
     */
    public function testExceptionThrows()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~Cannot create repository in your database~');

        $this->repository->deleteRepository();

        $mock = m::mock(clone $this->repository)
            ->makePartial()
            ->shouldReceive('createRepository')
            ->andReturnUsing(function () {
                throw new \Exception('Mockery is cool');
            })
            ->getMock();

        $this->app->instance(RepositoryContract::class, $mock);

        $this->artisan($this->getCommandSignature());
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandSignature()
    {
        return 'data-migrate:install';
    }
}
