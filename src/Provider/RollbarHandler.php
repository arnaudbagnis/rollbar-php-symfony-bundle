<?php
namespace SymfonyRollbarBundle\Provider;

use Rollbar\Rollbar;
use Rollbar\Monolog\Handler\RollbarHandler as RollbarMonologHandler;

use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SymfonyRollbarBundle\DependencyInjection\SymfonyRollbarExtension;

class RollbarHandler
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Monolog\Handler\RollbarHandler
     */
    public function getHandler()
    {
        $config = $this->getContainer()->getParameter(SymfonyRollbarExtension::ALIAS . '.config');
        
        if (isset($_ENV['ROLLBAR_TEST_TOKEN']) && $_ENV['ROLLBAR_TEST_TOKEN']) {
            $config['rollbar']['access_token'] = $_ENV['ROLLBAR_TEST_TOKEN'];
        }
        
        Rollbar::init($config['rollbar'], false, false, false);
        
        $handler = new RollbarMonologHandler(
            Rollbar::logger(),
            Logger::ERROR
        );

        return $handler;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
