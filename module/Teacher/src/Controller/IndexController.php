<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Teacher\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\Reflection as ReflectionHydrator;

use Application\Entity\Countries;
use Application\Entity\States;
use Application\Entity\Cities;
use Application\Entity\Faculty;
use Application\Entity\AcademicRanck;
use Application\Entity\AcademicYear;
use Application\Entity\Teacher;
use Application\Entity\Contract;
use Application\Entity\ClassOfStudyHasSemester;


class IndexController extends AbstractActionController
{
    private $sessionContainer;
    private $entityManager;
    
    /**
     * Constructor.
     */    
    public function __construct($entityManager,$sessionContainer)
    {

        $this->sessionContainer = $sessionContainer;
        $this->entityManager = $entityManager;
    }
    public function indexAction()
    {
        
        //redirect to the login action of authController
        return  $this->redirect()->toRoute('login');

    }
    
    public function teacherDetailsAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }
    public function acadranktplAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }    
    public function newAcadRankAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }    
    
    public function newteachertplAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }
    
    public function newTeacherFormAssetsAction()
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $countries = $this->entityManager->getRepository(Countries::class)->findAll(); 
            $faculties = $this->entityManager->getRepository(Faculty::class)->findBy([],array("name"=>"ASC"));
            $rank =      $this->entityManager->getRepository(AcademicRanck::class)->findBy([],array("name"=>"ASC")); 
            $diplomas = [["name"=>"PHD"],["name"=>"MSC"],["name"=>"DEA"],["name"=>"INGENIEUR"],["name"=>"MASTER PRO"],["name"=>"LICENCE"],["name"=>"BTS"]];
            
            foreach($countries as $key=>$value)
            {
                $hydrator = new ReflectionHydrator(); 
                $data = $hydrator->extract($value);
                $countries[$key] = $data; 
            }
            foreach($faculties as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $faculties[$key] = $data;
            }     
            foreach($rank as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $rank[$key] = $data;
            }             
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                'countries'=>$countries,
                'diplomas'=>$diplomas,
                'establishments'=>$faculties,
                'grades'=>$rank
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        } 

    } 
    public function citiesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $cities = [];
            $countryID= $this->params()->fromQuery('id', 'default_val'); 
            $country = $this->entityManager->getRepository(Countries::class)->find($countryID);

            $regions = $this->entityManager->getRepository(States::class)->findByCountry($country);
            foreach($regions as $region)
            {
                $cities_1 = $this->entityManager->getRepository(Cities::class)->findByState($region); 
                $cities = array_merge($cities,$cities_1);
            }
            
    
            foreach($cities as $key=>$value)
            {
              
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $cities[$key] = $data;
            }
               usort($cities, function($a, $b)
                    {
                        return strnatcmp($a['name'], $b['name']);
                    }
                ); 
              
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $cities
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        }    
    }    

    public function assignSubjectToTeacherAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $cities = [];
            $data= $this->params()->fromPost();                 
            //$data = json_decode($data,true);
           
            $teacher = $this->entityManager->getRepository(Teacher::class)->find($data['teacherid']);
            $acadYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            
            //Cheking if a contract already exist for the current year
            $contract = $this->entityManager->getRepository(Contract::class)->findOneBy(["academicYear"=>$acadYear,"teacher"=>$teacher]);  
            if($contract)
            {
                
            }
            else{
                $contract = $this->entityManager->getRepository(Contract::class)->findBy(["academicYear"=>$acadYear]); 
                $contract = sizeof($contract);
                if($contract<10)
                $contract = str_pad($contract,4,0,STR_PAD_LEFT);
                else if ($contract<100)
                    $contract = str_pad($contract,3,0,STR_PAD_LEFT);
                else if ($contract<1000) 
                    $contract = str_pad($contract,2,0,STR_PAD_LEFT);
                
                $faculty = $teacher->getFaculty()->getCode();
                $refNum = $acadYear->getCode()."/".$faculty."/".$contract;
                $contract = new Contract();
                $contract->setAcademicYear($acadYear);
                $contract->setTeacher($teacher);
                $contract->setRefNumber($refNum);
                
                $this->entityManager->persist($contract);
            }
            
           
            foreach($data["subjects"] as $key=>$value)
            {
                $coshs = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($value);
                
                if($coshs)
                {
                    $coshs->setContract($contract);
                }
            }

            /*$regions = $this->entityManager->getRepository(States::class)->findByCountry($country);
            foreach($regions as $region)
            {
                $cities_1 = $this->entityManager->getRepository(Cities::class)->findByState($region); 
                $cities = array_merge($cities,$cities_1);
            }
            
    
            foreach($cities as $key=>$value)
            {
              
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $cities[$key] = $data;
            }
               usort($cities, function($a, $b)
                    {
                        return strnatcmp($a['name'], $b['name']);
                    }
                ); 
              */
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $cities
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        }    
    }
}
