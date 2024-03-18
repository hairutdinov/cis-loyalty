<?php

namespace AppTest;

use Doctrine\ORM\EntityManager;
use PDO;
use PHPUnit\Framework\TestCase;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

require __DIR__ . '/../bootstrap.php';

class DatabaseTestCase extends TestCase
{
    protected EntityManager $entity_manager;

    protected function setUp(): void
    {
        parent::setUp();

        global $entityManager;
        $this->entity_manager = $entityManager;

        $config_array = require('./phinx.php');
        $config = new Config($config_array);
        $manager = new Manager($config, new StringInput(' '), new NullOutput());
        $manager->migrate("testing");
    }

}