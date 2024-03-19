<?php

namespace AppTest;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

require __DIR__ . '/../bootstrap.php';

class DatabaseTestCase extends TestCase
{
    protected EntityManager $entity_manager;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        if ($_ENV["APP_ENV"] !== 'testing') {
            throw new \Exception('Для выполнения тестов переменная окружения "APP_ENV" должны быть выставлена в значение "testing"');
        }

        global $entityManager;
        $this->entity_manager = $entityManager;

        $config_array = require('./phinx.php');
        $config = new Config($config_array);
        $manager = new Manager($config, new StringInput(' '), new NullOutput());
        $manager->migrate("testing");

        $this->client = new Client([
            'base_uri' => 'http://nginx',
            'timeout' => 5.0
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $config_array = require('./phinx.php');
        $config = new Config($config_array);
        $manager = new Manager($config, new StringInput(' '), new NullOutput());
        $manager->rollback("testing", 0);
    }

}