<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Teacher\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Teacher\Controller\TeacherController;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class TeacherControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                     $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionContainer = $container->get('LoggedInUser');
        
        // Instantiate the controller and inject dependencies
        return new teacherController($entityManager,$sessionContainer);
    }

}