<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Form\LoginForm;

class IndexController extends AbstractActionController
{
    private $sessionContainer;
    
    /**
     * Constructor.
     */    
    public function __construct($sessionContainer)
    {

        $this->sessionContainer = $sessionContainer;
    }
    public function indexAction()
    {
        
        //redirect to the login action of authController
        return  $this->redirect()->toRoute('login');

    }
    
    public function homeAction()
    {
        
        return new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);

    }

}
