<?php

namespace AvtoDev\DataMigrationsLaravel\Tests;

use Illuminate\Foundation\Application;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use AvtoDev\DataMigrationsLaravel\Contracts\RepositoryContract;
use AvtoDev\DataMigrationsLaravel\DataMigrationsServiceProvider;
use AvtoDev\DataMigrationsLaravel\Tests\Bootstrap\TestsBootstrapper;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends AbstractLaravelTestCase
{
    use Traits\ApplicationHelpersTrait;

    /**
     * Create data migrations table into database?
     *
     * @var bool
     */
    protected $create_repository = true;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->config()->set(
            DataMigrationsServiceProvider::getConfigRootKeyName() . '.migrations_path',
            __DIR__ . '/stubs/data_migrations'
        );

        $this->prepareDatabase(true);

        if ($this->create_repository === true) {
            $this->app->make(RepositoryContract::class)->createRepository();
        }
    }

    /**
     * Возвращает путь к директории, в которой хранятся временные файлы.
     *
     * @return string
     */
    public function getTemporaryDirectoryPath(): string
    {
        return (string) \realpath(__DIR__ . '/temp');
    }

    /**
     * Returns connections names and paths for SQLite databases.
     *
     * @return string[]
     */
    public function getDatabasesFilePath()
    {
        return [
            'default'      => $this->getTemporaryDirectoryPath() . '/database.sqlite',
            'connection_2' => $this->getTemporaryDirectoryPath() . '/database_2.sqlite',
            'connection_3' => $this->getTemporaryDirectoryPath() . '/database_3.sqlite',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeApplicationBootstrapped(Application $app)
    {
        $app->useStoragePath(TestsBootstrapper::getStorageDirectoryPath());
    }

    /**
     * {@inheritdoc}
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        $app->register(DataMigrationsServiceProvider::class);
    }
}
