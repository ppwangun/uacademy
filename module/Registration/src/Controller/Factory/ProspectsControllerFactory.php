<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Registration\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Registration\Controller\ProspectsController;
use Exam\Service\ExamManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class ProspectsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                     $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionContainer = $container->get('LoggedInUser');
        $examManager = $container->get(ExamManager::class);
        
        // Instantiate the controller and inject dependencies
        return new ProspectsController($entityManager,$sessionContainer,$examManager);
    }
}