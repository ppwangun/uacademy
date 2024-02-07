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
use Application\Entity\User;
use Application\Entity\CurrentYearUesAndSubjectsView;
use Application\Entity\ContractFollowUp;


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
    
    public function teacherListAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }
    public function teacherFollowUpAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    } 
    public function programmingtplAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }     
    
    public function teacherAssignedSubjectsTplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }   
    public function subjectBillingTplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }    
    public function teacherAssignedSubjectsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            $ue = [];
            
            if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {
                //collect all courses affected to any semester
                    $query = $this->entityManager->createQuery('SELECT t.id, c.id as ue_class_id,s.id as sem_id,s.code as sem_code,t.name,t.code,c1.code as class,c.credits, c.hoursVolume ,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs, teach.name as lecturer FROM Application\Entity\ClassOfStudyHasSemester c '
                        . 'JOIN c.classOfStudy c1 JOIN c.teachingUnit t JOIN c.semester s JOIN s.academicYear a JOIN c.teacher teach   WHERE a.isDefault = 1 '
                        . 'AND c.status = 1 ');
                $ue= $query->getResult(); 
              
                //collect all courses affected to any semester
            $query = $this->entityManager->createQuery('SELECT t.id, c.id as ue_class_id,s.id as sem_id,s.code as sem_code,t.subjectName as name,t.subjectCode as code,c1.code as class,c.subjectWeight as credits, c.subjectHours as hoursVolume ,c.subjectCmHours  as cm_hrs,c.subjectTpHours  as tp_hrs, c.subjectTdHours  as td_hrs, teach.name as lecturer FROM Application\Entity\ClassOfStudyHasSemester c '
                        . 'JOIN c.classOfStudy c1 JOIN c.subject t JOIN c.semester s JOIN s.academicYear a JOIN c.teacher teach   WHERE a.isDefault = 1 '
                        . 'AND c.status = 1 ');  
            $ue_1= $query->getResult();    
             $ue = array_merge($ue,$ue_1);
               
            }
            else
            {
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));
                
                if($userClasses)
                {
                    foreach($userClasses as $classe)
                    {
                        //collect all courses affected to any semester
                        $query = $this->entityManager->createQuery('SELECT t.id, c.id as ue_class_id,s.id as sem_id,s.code as sem_code,t.name,t.code,t.numberOfSubjects as subjects, c1.code as class,c.credits, c.hoursVolume ,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs FROM Application\Entity\ClassOfStudyHasSemester c '
                                . 'JOIN c.classOfStudy c1 JOIN c1.teacher  teach  JOIN c.teachingUnit t JOIN c.semester s JOIN s.academicYear a WHERE a.isDefault = 1 '
                                . 'AND c.status = 1 '
                                . 'AND c1.code = ?1 ');
                        $query->setParameter(1, $classe->getClassOfStudy()->getCode());
                        $ue_1= $query->getResult(); 
                        $ue = array_merge($ue,$ue_1);
                        
                    }
                }
            }
            for($i=0;$i<sizeof($ue);$i++)
            {
               // $ue[$i]['name']= utf8_encode($ue[$i]['name']);

            }            

            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $ue  
                
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    }
    public function unitFollowUpAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromPost(); 
        
            $coshs = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($data["teachingUnitId"]);    
            $progression = $this->entityManager->getRepository(ContractFollowUp::class)->findByClassOfStudyHasSemester($coshs);  
       
            foreach($progression as $key=>$value)
            {
                $hydrator = new ReflectionHydrator(); 
                $data = $hydrator->extract($value);
                $countries[$key] = $data; 
            }  
            $totalCm = 0;
            $totalTd = 0;
            $totalTp = 0;
            foreach($progression as $value)
            { 
                if($value->getLectureType() == "cm") 
                    $totalCm += $value->getTotalTime();
                if($value->getLectureType() == "td")
                    $totalTd+= $value->getTotalTime();
                if($value->getLectureType() == "tp")
                    $totalTp+= $value->getTotalTime();
                
                $total = $totalCm + $totalTd + $totalTp;
                
            }
            $subjectCm = $coshs->getSubjectCmHours();
            if ($coshs->getSubjectCmHours()==-1) $subjectCm = 0;
            $subjectTd = $coshs->getSubjectTdHours();
            if ($coshs->getSubjectTdHours()==-1) $subjectTd = 0;
            $subjectTp = $coshs->getSubjectTpHours();
            if ($coshs->getSubjectTpHours()==-1) $subjectTp = 0; 
            
            $dataOuput["cm"]["total"] = $coshs->getCmHours()+ $subjectCm;
            $dataOuput["cm"]["progress"] = $totalCm; 
            $dataOuput["td"]["total"] = $coshs->getTdHours() + +$subjectTd;
            $dataOuput["td"]["progress"] = $totalTd; 
            $dataOuput["tp"]["total"] = $coshs->getTpHours()+$subjectTp;
            $dataOuput["tp"]["progress"] = $totalTp;
            $total_prevu =  $coshs->getCmHours()+ $subjectCm + $coshs->getTdHours() + +$subjectTd;+$coshs->getTpHours()+$subjectTp ;
            $total_real = $totalCm + $totalTd + $totalTp;  
            if($total_prevu==0) 
                $percentage = 0;
            else
                $percentage = ($total_real/$total_prevu)*100;
            $dataOuput["percentage"] = $percentage; 
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $dataOuput
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        } 

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
            $diplomas = [["name"=>"PHD"],["name"=>"MSC"],["name"=>"DEA"],["name"=>"INGENIEUR"],["name"=>"MASTER PRO"],["name"=>"VALIDATION DES ACQUIS PRO"]];
            
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
            $proceeByForce =(int) $data["proceedByForce"];           
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
                    if($coshs->getContract() && !$proceeByForce) {
                        return  new JsonModel([
                                 false
                         ]);                         
                    }
                    $coshs->setContract($contract);
                    $coshs->setTeacher($teacher);
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
                    true
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        }    
    }
    
    public function searchAllSubjectsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();  
            $id = $data["id"];
            $subjects=[];
            //retrive the current loggedIn User
            $userId = $this->sessionContainer->userId; 
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
            //check first the user has global permission or specific permission to access exams informations
            if($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {            
                // retrieve subjects based on subject code

                //$rsm = new ResultSetMapping();
                // build rsm here

                $query = $this->entityManager->createQuery('SELECT c.id,c.coshs,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs FROM Application\Entity\CurrentYearUesAndSubjectsView c'
                        .' WHERE c.codeUe LIKE :code');
                $query->setParameter('code', '%'.$id.'%');

                $subjects = $query->getResult();
            }
            else
            {
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));  
                if($userClasses)
                {

                    foreach($userClasses as $classe)
                    {
                        $query = $this->entityManager->createQuery('SELECT c.id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId  FROM Application\Entity\Application\Entity\CurrentUesAndSubjectsView c'
                                .' WHERE c.classe = :classe AND c.codeUe LIKE :code');
                        $query->setParameter('code', '%'.$id.'%')
                                ->setParameter('classe',$classe->getClassOfStudy()->getCode());

                        $subjects_1 = $query->getResult();
                        $subjects= array_merge($subjects , $subjects_1);
                    }
                }                
            }

            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $subjects
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
        
    }
    
    public function teacherUnitFollowUpAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromPost(); var_dump($data); exit; 
            $id = $data["id"];
            $subjects=[];
            //retrive the current loggedIn User
            $userId = $this->sessionContainer->userId; 
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
            //check first the user has global permission or specific permission to access exams informations
            if($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {            
                // retrieve subjects based on subject code

                //$rsm = new ResultSetMapping();
                // build rsm here

                $query = $this->entityManager->createQuery('SELECT c.id,c.coshs,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs FROM Application\Entity\CurrentYearUesAndSubjectsView c'
                        .' WHERE c.codeUe LIKE :code');
                $query->setParameter('code', '%'.$id.'%');

                $subjects = $query->getResult();
            }
            else
            {
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));  
                if($userClasses)
                {

                    foreach($userClasses as $classe)
                    {
                        $query = $this->entityManager->createQuery('SELECT c.id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId  FROM Application\Entity\Application\Entity\CurrentUesAndSubjectsView c'
                                .' WHERE c.classe = :classe AND c.codeUe LIKE :code');
                        $query->setParameter('code', '%'.$id.'%')
                                ->setParameter('classe',$classe->getClassOfStudy()->getCode());

                        $subjects_1 = $query->getResult();
                        $subjects= array_merge($subjects , $subjects_1);
                    }
                }                
            }

            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $subjects
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }
}
