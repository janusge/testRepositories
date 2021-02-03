<?php

namespace AppTest\Unit\Repository\Base;

use Laminas\ServiceManager\ServiceManager;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as PDOSqliteDriver;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Laminas\Stdlib\ArrayUtils;
use Doctrine\ORM\Tools\SchemaTool;

class DbTestCase extends AbstractHttpControllerTestCase
{
    protected $configuredServiceManager;
    protected $entityManager;
    protected $databaseFailure;
    protected $schemaTool;
    protected $entityMetaData;
    protected $dbType;
    protected $databaseName;

    protected function setUp(): void
    {
        $this->configuredServiceManager = $this->configureServiceManager(
            include __DIR__ . '/../../../../../config/container.php'
        );

        $this->setEntityManager();

        $this->initializeDb();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->schemaTool->dropSchema($this->entityMetaData);
    }

    protected function setEntityManager()
    {
        $this->entityManager = $this->configuredServiceManager
        ->get('doctrine.entity_manager.orm_default');
    }

    protected function initializeDb()
    {
        $this->entityMetaData = $this->entityManager
        ->getMetadataFactory()
        ->getAllMetadata();

        $this->schemaTool = new SchemaTool($this->entityManager);

        $this->databaseFailure = false;
        if ($this->dbType === 'sqlite') {
            $this->checkSqlite();
        }

        if ($this->dbType === 'mysql') {
            $this->checkMysql();
        }

        if ($this->databaseFailure) {
            $this->markTestSkipped('Integration test skipped: ' . $this->databaseFailure);
        }

        $this->schemaTool->createSchema($this->entityMetaData);
    }

    protected function checkSqlite()
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->databaseFailure = "Required extension pdo_sqlite missing";
        }
    }

    protected function checkMysql()
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->databaseFailure = "Required extension pdo_mysql missing";
        }
    }

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);
        $config = $services->get('config');
        $services->setService('config', $this->updateConfig($config));
        $services->setAllowOverride(false);
        return $services;
    }

    protected function updateConfig($config)
    {
        $this->dbType = array_key_exists('test_db_type', $config)?
            $config['test_db_type']: 'sqlite';
        if ($this->dbType === 'sqlite') {
            $config['doctrine']['connection']['orm_default']['params'] = [
                'memory'     => true
            ];
            $config['doctrine']['connection']['orm_default']['driver_class'] = PDOSqliteDriver::class;
        }
        if ($this->dbType === 'mysql') {
            $this->databaseName = 'test';
            $config['doctrine']['connection']['orm_default']['params']['dbname'] = $this->databaseName;
        }
        return $config;
    }

    protected function getDbParams(ServiceManager $services)
    {
        $config = $services->get('config');
        return $config['doctrine']['connection']['orm_default'];
    }
}
