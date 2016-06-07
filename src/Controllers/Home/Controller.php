<?php

namespace Spear\Skeleton\Controllers\Home;

use Spear\Silex\Application\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Controller
{
    use
        Traits\RequestAware,
        LoggerAwareTrait;

    private
        $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;

        $this->logger = new NullLogger();
    }

    private function buildProducers()
    {
        $producers = [];
        $producers[] = $this->buildProducer('queue1','exchange1', 'routingKey1', 'app1');
        $producers[] = $this->buildProducer('queue1','exchange2', 'routingKey2', 'app2');
        $producers[] = $this->buildProducer('queue2','exchange3', 'routingKey3', 'app3');

        return $producers;
    }

    private function buildProducer($queueName, $exchange, $routingKey, $appName)
    {
        $result = [];
        $result['queueName'] = $queueName;
        $result['appName'] = $appName;
        $result['exchange'] = $exchange;
        $result['routingKey'] = $routingKey;

        return $result;
    }

    public function homeAction()
    {
        $producers = $this->buildProducers();
        $producerApplications = [];
        $consumerApplications = [];
        $exchanges = [];
        $queues = [];

        foreach($producers as $producer)
        {
            $producerApplications[] = $producer['appName'];
            $queues[] = $producer['queueName'];
            $exchanges[] = $producer['exchange'];
        }

        return $this->twig->render('home.html.twig', [
            'producers' => $producers,
            'consumerApplications' => ['appConsumer1', 'appConsumer2'],
            'producerApplications' => array_unique($producerApplications),
            'queues' => array_unique($queues),
            'exchanges' => array_unique($exchanges),
        ]);
    }
}
