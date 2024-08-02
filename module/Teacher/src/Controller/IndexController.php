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
use Application\Entity\ClassOfStudy;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\Semester;
use Application\Entity\Contract;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\User;
use Application\Entity\TeacherPaymentBill;
use Application\Entity\CurrentYearUesAndSubjectsView;
use Application\Entity\ContractFollowUp;
use Application\Entity\AllContractsView;
use Application\Entity\CourseScheduled;




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
            $ue = []; $ue_1 = [];
            
            if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {
                //collect all courses affected to any semester
                    $query = $this->entityManager->createQuery('SELECT c.id, c.semId as sem_id,c.semester as sem_code,c.nomUe as name,c.codeUe as code, c.classe as class,c.credits, c.totalHrs AS hoursVolume ,c.cmHrs as cm_hrs,c.tpHrs as tp_hrs, c.tdHrs as td_hrs, c.teacherName as lecturer FROM Application\Entity\AllContractsView c '
                        );
                $ue= $query->getResult(); 
              
                //collect all courses affected to any semester
           /* $query = $this->entityManager->createQuery('SELECT con.id, c.id as ue_class_id,s.id as sem_id,s.code as sem_code,t.subjectName as name,t.subjectCode as code,c1.code as class,c.subjectWeight as credits, c.subjectHours as hoursVolume ,c.subjectCmHours  as cm_hrs,c.subjectTpHours  as tp_hrs, c.subjectTdHours  as td_hrs, teach.name as lecturer FROM Application\Entity\ClassOfStudyHasSemester c '
                        . 'JOIN c.classOfStudy c1 JOIN c.subject t JOIN c.semester s JOIN s.academicYear a JOIN t.contract con JOIN con.teacher teach    WHERE a.isDefault = 1 '
                        . 'AND c.status = 1 ');  
            $ue_1= $query->getResult();   */ 
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
                        $query = $this->entityManager->createQuery('SELECT c.id, c.semId as sem_id,c.semester as sem_code,c.nomUe as name,c.codeUe as code, c.classe as class,c.credits, c.totalHrs AS hoursVolume ,c.cmHrs as cm_hrs,c.tpHrs as tp_hrs, c.tdHrs as td_hrs, c.teacherName as lecturer FROM Application\Entity\AllContractsView c '
                                . 'AND c.classe= ?1 ');
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
            $data = $this->params()->fromPost(); // 
            //var_dump($data); exit;
        
            $contract = $this->entityManager->getRepository(Contract::class)->find($data["contractId"]);    
            $progression = $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($contract);  
   
            foreach($progression as $key=>$value)
            {
                $hydrator = new ReflectionHydrator(); 
                $data = $hydrator->extract($value);
                //$countries[$key] = $data; 
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


            $dataOuput["cm"]["total"] = $contract->getCmHrs();
            $dataOuput["cm"]["progress"] = $totalCm; 
            $dataOuput["td"]["total"] = $contract->getTdHrs();
            $dataOuput["td"]["progress"] = $totalTd; 
            $dataOuput["tp"]["total"] = $contract->getTpHrs();
            $dataOuput["tp"]["progress"] = $totalTp;
            $total_prevu =  $contract->getCmHrs()+  $contract->getTdHrs() + $contract->getTpHrs() ;
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
            $flag = 0;
            //$data = json_decode($data,true);
      
            $teacher = $this->entityManager->getRepository(Teacher::class)->find($data['teacherid']);
            $acadYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            
          
                foreach($data["subjects"] as $key=>$value)
                { 
                    $coshs = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($value["id"]);
                    $unit = null;
                    $subject= null;
                
                   if($coshs->getTeachingUnit()) 
                    {            
                        $unit = $coshs->getTeachingUnit();
                        $tpHrs = $coshs->getTpHours();
                        $cmHrs = $coshs->getCmHours();
                        $tdHrs = $coshs->getTdHours();                      

                        $contract = $this->entityManager->getRepository(Contract::class)->findBy(["academicYear"=>$acadYear,"teachingUnit"=>$unit]);

                    }
                   if($coshs->getSubject()) 
                    {  
                        $subject = $coshs->getSubject();
                        $tpHrs = $coshs->getSubjectTpHours();
                        $tdHrs = $coshs->getSubjectTDHours();
                        $cmHrs = $coshs->getSubjectCmHours();         
                        $contract = $this->entityManager->getRepository(Contract::class)->findBy(["academicYear"=>$acadYear,"subject"=>$subject]);

                    } 
                 
                        $totalHoursAffected = 0;
                        $courseHoursVolume = 0; 
                        ($coshs->getSubject())?$courseHoursVolume = $coshs->getSubjectHours():$courseHoursVolume = $coshs->getHoursVolume();  

                        
                        //calculate the total time already affected
                        foreach($contract as $c) $totalHoursAffected+=$c->getVolumeHrs();                         
                        //check whetehr or not the sbject is already 
                       
                        //non affectd time
                        $nonAffectedTime = $courseHoursVolume-$totalHoursAffected;

                                $contractSize = sizeof($contract);
                                if($contractSize<10)
                                $contractSize= str_pad($contractSize,4,0,STR_PAD_LEFT);
                                else if ($contractSize<100)
                                    $contractSize = str_pad($contractSize,3,0,STR_PAD_LEFT);
                                else if ($contractSize<1000) 
                                    $contractSize = str_pad($contractSize,2,0,STR_PAD_LEFT);


                                $faculty = $teacher->getFaculty()->getCode(); 
                                $refNum = $acadYear->getCode()."/".$faculty."/".$contractSize; 
                                
                               $hrToAffect = intval($value["totalHrs"]);
                               
                       
                        if(isset($data["partialAttribution"])&&$data["partialAttribution"]&&$nonAffectedTime>=$value["volumeHrs"] )
                        {
                            $hrToAffect = intval($value["volumeHrs"]);
                            
                            foreach($contract as $key=>$con){$this->entityManager->remove($con);array_splice($contract, $key);}
                            if(sizeof($contract)<=0) $contract = new Contract();
                            

                        }
                        elseif((isset($data["partialAttribution"])&&$data["partialAttribution"]&&$nonAffectedTime<intval($value["volumeHrs"]) ))
                        {
                            $hrToAffect = $nonAffectedTime;
                                                     
                            foreach($contract as $key=>$con){$this->entityManager->remove($con);array_splice($contract, $key);}
                            if(sizeof($contract)<=0) $contract = new Contract();

                                                        
                        }

                        elseif(sizeof($contract)<=0) $contract = new Contract(); 
                        elseif($proceeByForce && !$data["partialAttribution"])
                        {
                            foreach($contract as $key=>$con){$this->entityManager->remove($con);array_splice($contract, $key);}
                            $this->entityManager->flush();
                            if(sizeof($contract)<=0) $contract = new Contract();

                        }
                        elseif(!$proceeByForce) return  new JsonModel([false]);
                        else{
                            
                        }
                                $contract->setAcademicYear($acadYear);
                                $contract->setTeacher($teacher);
                                $contract->setTeachingUnit($unit);
                                $contract->setSubject($subject);
                                $contract->setSemester($coshs->getSemester());
                                
                             //   ($contract->getSubject())?$courseHoursVolume = $contract->getSubjectHours():$courseHoursVolume = $contract->getHoursVolume(); 
                                $contract->setVolumeHrs($hrToAffect);
                                $contract->setCmHrs($cmHrs);
                                $contract->setTdHrs($tdHrs);
                                $contract->setTpHrs($tpHrs);
                                //$contract->setClassOfStudyHasSemester($contract);
                                $contract->setRefNumber($refNum); 
                                if($flag) $this->entityManager->flush();
                                else
                                {
                                    $this->entityManager->persist($contract); 
                                    $this->entityManager->flush();
                                }


                        
      
                }
            
            

            //$this->entityManager->flush();
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

    public function unAssignSubjectToTeacherAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $cities = [];
            $data= $this->params()->fromPost();           
      
  
            //$data = json_decode($data,true);
         
          /*  $teacher = $this->entityManager->getRepository(Teacher::class)->find($data['teacherId']);
            $acadYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);*/

            $contract = $this->entityManager->getRepository(Contract::class)->find($data["contractId"]); 
             $contractFollowUp = $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($contract);
             foreach($contractFollowUp as $con) $this->entityManager->remove($con);
                
                    $this->entityManager->remove($contract); 
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

                $query = $this->entityManager->createQuery('SELECT c.id,c.subjectId,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs FROM Application\Entity\CurrentYearUesAndSubjectsView c'
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
                        $query = $this->entityManager->createQuery('SELECT c.id,c.subjectId,c.codeUe,c.nomUe,c.classe,c.semester,c.semId  FROM Application\Entity\Application\Entity\CurrentUesAndSubjectsView c'
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

                $query = $this->entityManager->createQuery('SELECT c.id,c.contract,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs FROM Application\Entity\CurrentYearUesAndSubjectsView c'
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
    
    public function searchTeacherAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();  
            $id = $data["id"];
            $subjects=[];
           
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
         
          //  if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) {
                
                $query = $this->entityManager->createQuery('SELECT t.id,t.name FROM Application\Entity\Teacher t'
                        .' WHERE t.name LIKE :name');
                $query->setParameter('name', '%'.$id.'%');
                //$query->setParameter('userId', $userId);
                $teachers = $query->getResult();  
                

           // }


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $teachers
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }  
    
    public function generateBillAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();           
           
            $subjects=[];
            $bills = [];
            $actualBilledTime = null;
            $overtime = 0;
          
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            
            $contract= $this->entityManager->getRepository(Contract::class)->find($data["contractID"] );
            $teacher= $this->entityManager->getRepository(Teacher::class)->find($data["teacherID"] );
            $contractFollowUp= $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($data["contractID"] );
            $contractNotYetPaid= $this->entityManager->getRepository(ContractFollowUp::class)->findBy(["contract"=>$data["contractID"],"teacherPaymentBill"=>NULL] );
            $totalTime = 0; 
            //Check if other bills exist on this contract
            $bills = $this->entityManager->getRepository(TeacherPaymentBill::class)->findBy(["teacher"=>$teacher,"contract"=>$contract]);
            $alreadyBilledTime = 0;
            foreach($bills as $bill) $alreadyBilledTime += $bill->getTotalTime();
            
            $pymtRate = $teacher->getAcademicRanck()->getPaymentRate();
            
             $paymentDetails = [];
             
             

            if($contract->getVolumeHrs()<= $alreadyBilledTime)
            {
                $output = new JsonModel([
                    ["error"=>0]
                ]);
                return $output;

            }  
            if(sizeof($contractNotYetPaid)<=0){ 
                $output = new JsonModel([
                    ["error"=>1]
                ]);
                return $output;                        
            }             
            
            if($contract->getVolumeHrs()> $alreadyBilledTime && sizeof($contractNotYetPaid)>0)
            {
                $pymtBill = new TeacherPaymentBill();
                $refNum = "2ABC";
                $pymtBill->setRefNumber($refNum);
                $pymtBill->setContract($contract);
                $pymtBill->setTeacher($teacher);
                $this->entityManager->persist($pymtBill);
                $this->entityManager->flush(); 
            
            
                $amount = 0;

               
                $k = 0;
                foreach($contractNotYetPaid as $con)
                {
                    if(!$con->getTeacherPaymentBill())
                    {
                        //Very the already billed time does not exceed the contract time


                        $alreadyBilledTime += $con->getTotalTime(); 
                        $billedTime = $con->getTotalTime();

                        if($contract->getVolumeHrs()> $alreadyBilledTime)
                        {
                            $con->setTeacherPaymentBill($pymtBill);
                            $this->entityManager->flush();
                            $hydrator = new ReflectionHydrator();
                            $data = $hydrator->extract($con);
                            $data["paymentRate"] = $pymtRate;
                            $data["paymentAmount"] = $billedTime * $pymtRate;
                            $paymentDetails[$k] = $data; $k++; 
                            
 
                            $amount += $billedTime*$pymtRate;
                            $totalTime+= $billedTime;                            

                        }
                        else 
                        { 
                            if(!$actualBilledTime)
                            {
                                $overtime  = ($alreadyBilledTime-$contract->getVolumeHrs()); 
                                $actualBilledTime = $con->getTotalTime()-$overtime;

                                $billedTime = $actualBilledTime; 
                                $amount += $billedTime*$pymtRate;
                                $totalTime+= $billedTime;                                
                            }
                            else
                            {
                                $overtime += $con->getTotalTime();
                            }
                            
                            $hydrator = new ReflectionHydrator();
                            $data = $hydrator->extract($con);
                            $data["paymentRate"] = $pymtRate;
                            $data["overtime"] = $overtime;
                            $data["paymentAmount"] = $billedTime * $pymtRate;
                            $paymentDetails[$k] = $data; $k++;                             
  
                        }

                        $con->setTeacherPaymentBill($pymtBill);
                        $this->entityManager->flush(); 
                        
                         
                    }


                }
              
                $pymtBill->setOverTime($overtime);
                $pymtBill->setDate(new \DateTime( date('Y-m-d')));
                $pymtBill->setPaymentAmount($amount);
                $pymtBill->setTotalTime($totalTime);

                $this->entityManager->flush();
            }
           


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
               [ 
                   
                "paymentDetails"=>$paymentDetails,
                "totalBilledTime"=>$totalTime,
                "alreadyBilledTime"=>$alreadyBilledTime,
                "overtime"=>$overtime,
                "paymentRate"=>$pymtRate,
                "totalHoursAffected"=>$contract->getVolumeHrs()]
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    } 
    public function searchBillAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();           
           
            $subjects=[];
            $bills = [];
           
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            
            $contract= $this->entityManager->getRepository(Contract::class)->find($data["contractID"] );
            $teacher= $this->entityManager->getRepository(Teacher::class)->find($data["teacherID"] );
           
            $bills = $this->entityManager->getRepository(TeacherPaymentBill::class)->findBy(["teacher"=>$teacher,"contract"=>$contract]);
            $hydrator = new ReflectionHydrator();
            foreach($bills as $key=>$value)
            $bills[$key]= $hydrator->extract($value);            
         
            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $bills
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }     
    
    public function billDetailsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();                               
           
            $subjects=[];
            $billItems = [];
            $teacher =[];
            $subject = [];
           
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            
           /* $contract= $this->entityManager->getRepository(Contract::class)->find($data["contractID"] );
            $teacher= $this->entityManager->getRepository(Teacher::class)->find($data["teacherID"] );*/
        
            $bill = $this->entityManager->getRepository(TeacherPaymentBill::class)->findOneBy(["refNumber"=>$data["numRef"]]);
            $teacher["id"]= $bill->getTeacher()->getId();
            $teacher["name"]= $bill->getTeacher()->getName();
            $contract = $bill->getContract();
            
            
            
            $pymtRate = $bill->getTeacher()->getAcademicRanck()->getPaymentRate();
            if($contract->getTeachingUnit())
            {
                $subject["id"] =  $contract->getTeachingUnit()->getId();
                $subject["codeUe"] =  $contract->getTeachingUnit()->getCode();
                $subject["nomUe"] =  $contract->getTeachingUnit()->getName();
            }
            else if($contract->getSubject())
            {
                $subject["id"] =  $contract->getSubject()->getId();
                $subject["codeUe"] =  $contract->getSubject()->getCode();
                $subject["nomUe"] =  $contract->getgetSubject()->getName();              
            }

            $billItems = $this->entityManager->getRepository(ContractFollowUp::class)->findBy(["teacherPaymentBill"=>$bill]);
           
            $hydrator = new ReflectionHydrator();
           // $teacher = $bill->getTeacher(); 
           // $teacher= $this->entityManager->getRepository(Teacher::class)->findBy($teacher->getId());
           // $data = $hydrator->extract($teacher);  
            //print_r($data); exit;
            $totalHrsDone = 0;
            foreach($billItems as $key=>$value)
            {
                $billItems[$key]= $hydrator->extract($value);
                $billItems[$key]["paymentRate"] = $pymtRate;
                $billItems[$key]["paymentAmount"] = $value->getTotalTime() * $pymtRate;  
                $totalHrsDone +=$value->getTotalTime();
            }
            $paymentRate = $bill->getTeacher()->getAcademicRanck()->getPaymentRate();
            $overTime = $bill->getOverTime();
            $totalHrs["totalHrsPreviouslyBilled"] = $bill->getTotalTimePreviouslyBilled();
            $totalHrs["totalHrsCurrentlyBilled"] = $bill->getTotalTimeCurrentlyBilled();
            $totalHrs["vacationDeduction"] = $bill->getVacationDeduction();
            $totalHrs["totalHrsDone"] = $totalHrsDone;
            $bill = $hydrator->extract($bill); 
            $totalHrs["totalHrsAffected"] = $contract->getVolumeHrs(); 
            
            
            $totalHrs["overTime"] =  $overTime;
            
            
            $this->entityManager->getConnection()->commit();

            $output = new JsonModel([
               $billItems,
                $teacher,
                $subject,
                $bill,
                $paymentRate,
                $totalHrs
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }
    
    public function schedulingCourseAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();  
            $subject = null;
            $teacher =null;
            $resource = null;
            $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classe"]);
           // $teacher = $this->entityManager->getRepository(Teacher::class)->find($data["teacher"]);
            
            $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ue"]);
            if(isset($data["subject"]))
                $subject = $this->entityManager->getRepository(Subject::class)->find($data["subject"]);
            $semester = $this->entityManager->getRepository(Semester::class)->find($data["sem"]);
    
            $contract = $this->entityManager->getRepository(Contract::class)->findOneBy(["teachingUnit"=>$teachingUnit,"subject"=>$subject,"semester"=>$semester]); 
             if($contract)
                $teacher = $contract->getTeacher();
             
              $dateScheduled  = new \DateTime( $data["date"]." ".$data["startingTime"]);
                        $startingTime =new \DateTime( $data["date"]." ".$data["startingTime"]);
            $endingTime = new \DateTime($data["date"]." ".$data["endingTime"]); 

            if($this->checkTimeConflictByClass($data["classe"], $startingTime))
              return new JsonModel([ "timeConflict"=>true ]); 
              
              
            $courseScheduled = new CourseScheduled();
            
            $courseScheduled->setClassOfStudy($classOfStudy);
            $courseScheduled->setTeacher($teacher);
            $courseScheduled->setTeachingUnit($teachingUnit);
            $courseScheduled->setSubject($subject);
            $courseScheduled->setSemester($semester);
            
            
            $courseScheduled->setDateScheduled($dateScheduled);
            $courseScheduled->setStartingTime($startingTime);
            $courseScheduled->setEndingTime($endingTime);
            
            $courseScheduled->setResource($resource);
            
            $this->entityManager->persist($courseScheduled);
            $this->entityManager->flush();
            
           $hydrator = new ReflectionHydrator();

            $data = $hydrator->extract($courseScheduled);
            $data["eventName"] = $classOfStudy->getCode()." \n".$teachingUnit->getCode();
                

           // }


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                $data
                    
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }     
    
public function getSchedulingCoursesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $key = 0;
            $myCourse = [];
            $data= $this->params()->fromQuery(); 
            
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1); 
            $semester = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr); 
            $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classe"]);
            foreach ($semester as $sem)
            {
                $courseScheduled = $this->entityManager->getRepository(CourseScheduled::class)->findBy(["classOfStudy"=>$classOfStudy,"semester"=>$sem]);
                
                foreach($courseScheduled as $course)
                {
                    $hydrator = new ReflectionHydrator();
                    $teachingUnit = $course->getTeachingUnit();
                    
                    if($course->getTeacher())$teacher = $course->getTeacher()->getName()." ".$course->getTeacher()->getSurname(); else $teacher = ""; 
                    $course = $hydrator->extract($course);
                    $course["eventName"] = $classOfStudy->getCode()." \n".$teachingUnit->getCode();
                   
                    $course["eventName"] .= "\n".$teacher;
                    
                    $myCourse[$key] = $course;
                    $key++;
                }
                
            }

                

           // }


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                $myCourse
                    
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }
    
    private function checkTimeConflictByClass($classe,$startingTime)
    {
        
                    $query = $this->entityManager->createQuery('SELECT c.id  FROM Application\Entity\CourseScheduled c'
                    .' JOIN c.classOfStudy cl'
                    .' WHERE cl.id =:classe'
                    .' AND :startingTime BETWEEN c.startingTime AND  c.endingTime'
                    );
            $query->setParameter('classe', $classe);
            $query->setParameter('startingTime', $startingTime);
            $courseScheduled = $query->getResult();

            if(sizeof($courseScheduled)>0) return 1;
            return 0;
        
        
    }
}
