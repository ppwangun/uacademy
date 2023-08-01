<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payment\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Payment\Controller\PaymentJournalController;
use Payment\Service\PaymentManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class PaymentJournalControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                     $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $paymentManager = $container->get(PaymentManager::class);
        
        // Instantiate the controller and inject dependencies
        return new PaymentJournalController($entityManager,$paymentManager);
    }
}