<?php

namespace AvtoDev\DataMigrationsLaravel\Tests;

use AvtoDev\DataMigrationsLaravel\Migrator;
use AvtoDev\DataMigrationsLaravel\Repository;
use AvtoDev\DataMigrationsLaravel\Sources\Files;
use AvtoDev\DataMigrationsLaravel\Contracts\SourceContract;
use AvtoDev\DataMigrationsLaravel\Contracts\MigratorContract;
use AvtoDev\DataMigrationsLaravel\Contracts\RepositoryContract;
use AvtoDev\DataMigrationsLaravel\DataMigrationsServiceProvider;

/**
 * Class DataMigrationsServiceProviderTest.
 *
 * @group provider
 */
class DataMigrationsServiceProviderTest extends AbstractTestCase
{
    /**
     * @var string
     */
    protected $config_root_key;

    /**
     * @var DataMigrationsServiceProvider
     */
    protected $provider_instance;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->config_root_key = DataMigrationsServiceProvider::getConfigRootKeyName();

        $this->provider_instance = new DataMigrationsServiceProvider($this->app);
    }

    /**
     * Проверка существования и корректности значений конфигурации.
     *
     * @return void
     */
    public function testConfigExists()
    {
        $config = $this->app->make('config')->get($this->config_root_key);

        $this->assertIsArray($config);

        foreach (['table_name', 'connection', 'migrations_path', 'executor_class'] as $key) {
            $this->assertArrayHasKey($key, $config);
        }

        foreach (['table_name', 'migrations_path', 'executor_class'] as $key) {
            $this->assertNotEmpty($config[$key]);
        }
    }

    /**
     * Tests service-provider loading.
     *
     * @return void
     */
    public function testServiceProviderLoading()
    {
        $this->assertInstanceOf(Repository::class, $this->app[RepositoryContract::class]);
        $this->assertInstanceOf(Repository::class, app(RepositoryContract::class));

        $this->assertInstanceOf(Migrator::class, $this->app[MigratorContract::class]);
        $this->assertInstanceOf(Migrator::class, app(MigratorContract::class));

        $this->assertInstanceOf(Files::class, $this->app[SourceContract::class]);
        $this->assertInstanceOf(Files::class, app(SourceContract::class));
    }
}
