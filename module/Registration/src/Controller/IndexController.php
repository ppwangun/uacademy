<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Registration\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Entity\Student;
use Application\Entity\RegisteredStudentView;
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;
use Application\Entity\UnitRegistration;
use Application\Entity\AdminRegistration;
use Application\Entity\AcademicYear;
use Application\Entity\Semester;
use Application\Entity\StudentSemRegistration;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\ClassOfStudy;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\TeachingUnit;
use Application\Entity\ProfileAcademic;
use Application\Entity\User;
use Application\Entity\CursusAcademique;
use Application\Entity\ProspectiveStudent;
use Application\Entity\ProspetiveRegistration;
use Application\Entity\UserManagesClassOfStudy;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Student\Service\StudentManager;
use PhpOffice\PhpSpreadsheet\Reader\Csv;


use Laminas\Hydrator\Reflection as ReflectionHydrator;


class IndexController extends AbstractActionController
{
    private $entityManager;
    private $studentManager;
    private $sessionContainer;
    private $examManager;
    public function __construct($entityManager,$studentManager,$sessionContainer,$examManager) {
        $this->entityManager = $entityManager;
        $this->studentManager = $studentManager;
        $this->sessionContainer = $sessionContainer;
        $this->examManager = $examManager;
    }

    public function indexAction()
    {
        return [];
    }
    
    public function studentsAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
     public function prospectsAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    
     public function prospectAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    public function getProspectCursusAction()
    {
            $data = $this->params()->fromQuery(); 
            $prospect = $this->entityManager->getRepository(ProspectiveStudent::class)->find($data["id"]);
            $cursus = $this->entityManager->getRepository(CursusAcademique::class)->findByProspectiveStudent($prospect);
            foreach($cursus as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $cursus[$key] = $data;
            }

        
          $view = new JsonModel([
            $cursus 
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    
    public function showpaymentproofAction()
    {
        $data = $this->params()->fromRoute(); 
        $prospect = $this->entityManager->getRepository(ProspetiveRegistration::class)->findOneByNumDossier($data['id']);
        $paymentProofPath = $prospect->getPaymentProofPath();

          $view = new ViewModel([
              "paymentProofPath"=>$paymentProofPath
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
    public function admissionAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    public function stdListAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    public function pedagogicalregtplAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }    
    public function registrationStatAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    public function studentinfostplAction()
    {
        
            //Current loggedIn User

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
    public function searchStudentAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {

            $data = $this->params()->fromQuery();  
            if(!isset($data['acadYrId']))
            {
                $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
                $data['acadYrId'] = $acadYr->getId();
            }else $acadYr = $this->entityManager->getRepository(AcademicYear::class)->find($data['acadYrId']);
            if(!isset($data['classeId'])) $data['classeId'] = "%";
            $conn = $this->entityManager->getConnection();
            $sql = '
                SELECT s.id,s.matricule,s.nom,s.prenom
                FROM student s
                INNER JOIN admin_registration a
                ON s.id = a.student_id
                WHERE a.academic_year_id = :acadYrId 
                AND s.matricule like :matricule
                AND a.class_of_study_id like :classeId
                AND a.status=1;
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('acadYrId' => trim($data['acadYrId']),
                                 'classeId' => trim($data['classeId']),
                                 'matricule' => "%".trim($data['matricule'])."%"));
            $results = $stmt->fetchAll();
            
        return new JsonModel([
                $results
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      } 
    }
    
    public function printRegistrationFileAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $dataString = $this->params()->fromRoute();
            $ue=[];
                $registeredStd = $this->entityManager->getRepository(Student::class)->findOneByMatricule($dataString["id"]);
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($registeredStd);
                //$data['dateOfBirth']=$data['dateOfBirth']->format('Y-m-d');
                //$data["photo"]=null;
                $data["phone_number"] = trim($data['phoneNumber']);
                
                $data["fatherPhoneNumber"] = trim($data['fatherPhoneNumber']);
                $data["motherPhoneNumber"] = trim($data['fatherPhoneNumber']);
                $data["sponsorPhoneNumber"] = trim($data['sponsorPhoneNumber']);
                
                $activeAcadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
                $student = $this->entityManager->getRepository(RegisteredStudentView::class)->find($dataString["id"]);
            
                $hydrator = new ReflectionHydrator();
                $student = $hydrator->extract($student);
                
                $studentClasse = $dataString["classe"];
                
                $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($studentClasse);
                $studentFilere = $classe->getDegree()->getFieldStudy();
                $studentFaculty = $studentFilere->getFaculty();
                
                 
                $ue = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("matricule"=>$dataString["id"]));
                foreach($ue as $key=>$value)
                {
                    $hydrator = new ReflectionHydrator();
                    $dat = $hydrator->extract($value);
                    $ue[$key] = $dat;

                }
               
                $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$activeAcadYr) );
                $i=0;
                $sem=[];
                foreach($semesters as $key=>$value)
                {

                    $sem[$i] = array("code"=>$value->getSemester()->getCode(),"ranking"=>$value->getSemester()->getRanking());
                    $i++;
                }      
                
                
                $filiere= $studentFilere->getName();
                $faculty= $studentFaculty->getName();
                $diplome= $classe->getDegree()->getName();
                $classe = $classe->getCode();
                $student = $data;
                $studentMat = $dataString["id"];
           

                    //$output = json_encode($output,$depth=1000000); 
            $view = new ViewModel([
                'acadYr'=>$activeAcadYr->getName(),
                'classe'=>$classe,
                'diplome'=>$diplome,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'student'=>$student,
                'subjects'=>$ue,
                'semesters'=>$sem,
                'studentMat'=>$studentMat
                   
            ]);
            //var_dump($output); //exit();
            $view->setTerminal(true);
            return $view;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
    }
    
    public function printStudentCardAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $dataString = $this->params()->fromRoute();
            $ue=[];
                $registeredStd = $this->entityManager->getRepository(Student::class)->findOneByMatricule($dataString["id"]);
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($registeredStd);
                //$data['dateOfBirth']=$data['dateOfBirth']->format('Y-m-d');
                //$data["photo"]=null;
                $data["phone_number"] = trim($data['phoneNumber']);
                
                $data["fatherPhoneNumber"] = trim($data['fatherPhoneNumber']);
                $data["motherPhoneNumber"] = trim($data['fatherPhoneNumber']);
                $data["sponsorPhoneNumber"] = trim($data['sponsorPhoneNumber']);
                
                $activeAcadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
                $student = $this->entityManager->getRepository(RegisteredStudentView::class)->find($dataString["id"]);
            
                $hydrator = new ReflectionHydrator();
                $student = $hydrator->extract($student);
                
                $studentClasse = $dataString["classe"];
                
                $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($studentClasse);
                $studentFilere = $classe->getDegree()->getFieldStudy();
                $studentFaculty = $studentFilere->getFaculty();
                
                 
      
                
                
                $filiere= $studentFilere->getName();
                $faculty= $studentFaculty->getName();
                $diplome= $classe->getDegree()->getName();
                $classe = $classe->getCode();
                $student = $data;
                $studentMat = $dataString["id"];
    
                //$output = json_encode($output,$depth=1000000); 
            $view = new ViewModel([
                'acadYr'=>$activeAcadYr->getName(),
                'classe'=>$classe,
                'diplome'=>$diplome,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'student'=>$student,

                'studentMat'=>$studentMat
                   
            ]);
            //var_dump($output); //exit();
            $view->setTerminal(true);
            return $view;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
    }    
    
    public function stdregisteredbyclasseAction()
    {
 
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $datastring = $this->params()->fromQuery(); 
            
            $registeredStd = $this->entityManager->getRepository(RegisteredStudentView::class)->findBy(array("class"=>$datastring['classe']));
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($datastring["classe"]);
            $sem = $this->entityManager->getRepository(Semester::class)->findOneById($datastring["sem"]);
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
            
            //Total credit at the semester of the cycle
            $totalCredits = $this->totalCreditsPerCycle($classe->getCode(),$sem); 
          
            $studyLevel = $classe->getStudyLevel();
            $sem_rank = $sem->getRanking();
            $i= 0;
            $j=0;
           
            foreach($registeredStd as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std = $this->entityManager->getRepository(Student::class)->find($value->getStudentId());

                //All courses of the class
                 $currentYrCourses = $this->findCourses($classe->getStudyLevel(),$classe->getDegree()->getCode());
                $registeredStd[$key] = $data;
                $student[$j]["matricule"]= $value->getMatricule();
                $total_credits_sem = 0;
                $nbre_credits = 0;
                $nbre_points = 0;
                $total_credits_classe = 0;
                $mps = 0;
                //total credits validés par cycle
                $total_credits_valides_cycle = 0;
                //total credits validés par semestre
                $total_credits_valides_sem = 0;
                $total_credits_backlogs = 0;
                $unitRegistration = NULL;
                $totalCreditsCycle = 0;
                foreach($currentYrCourses as $course)
                {
                   // 
                    $sem_1 = $this->entityManager->getRepository(Semester::class)->find($course->getSemId());
                    $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($course->getId());
                    //check if the student is register to the course
                    if($sem->getRanking()%2==$course->getSemRanking()%2&&$this->checkIscurrentYearSem($course->getSemId()))
                        $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$std,"teachingUnit"=>$teachingUnit,"subject"=>[null," "],"semester"=>$sem_1),array("noteFinal"=>"DESC"));
                    else continue;
                    //return student grade if student is registered to the course or NULL ortherwise
                    //only display subjects of the classe
                    
                    if($unitRegistration)
                    {
                        //number of credit registered to the cycle
                        
                        $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($course->getClasse());
                        $unitRegistrationDetails = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("classOfStudy"=>$classe,"teachingUnit"=>$teachingUnit,"semester"=>$sem_1,"status"=>1));
                        $unitRegistrationDetails?$nbre_credits = $unitRegistrationDetails->getCredits():0;
                        
                        $nbre_points = $unitRegistration->getPoints();
                        $total_credits_sem  += $nbre_credits ;
                        $mps += $nbre_credits*$nbre_points;
                        if($unitRegistration->getGrade()!= "F" && $unitRegistration->getGrade()!= "E" && !is_null($unitRegistration->getGrade()))
                        {
                            $total_credits_valides_cycle += $nbre_credits ;
                            $total_credits_valides_sem += $nbre_credits ;
                        }
                        //compute the number of credits student has register to the classe
                        if($this->checkIsCurrentClassSubject($datastring["classe"],$sem->getCode(),$course->getId()))
                        {
                            $student[$j][$course->getCodeUe()] = $unitRegistration->getGrade();
                            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($datastring["classe"]);
                            $unitRegistrationDetails_1 = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("classOfStudy"=>$classe,"teachingUnit"=>$teachingUnit,"semester"=>$sem,"status"=>1));
                            $unitRegistrationDetails_1?$total_credits_classe += $unitRegistrationDetails_1->getCredits():0;
                           // $totalCreditsCycle+=$unitRegistrationDetails_1->getCredits();
                        }
                     
                   
                        
                    }
                    else
                    {
                        
                        ($this->checkIsCurrentClassSubject($datastring["classe"],$sem->getCode(),$course->getId()))?$student[$j][$course->getCodeUe()]=NULL:"";
                    }
 
                    
                } 
                $student[$j]["Cr"] = $total_credits_classe;
                $student[$j]["TCr"] = $total_credits_sem;
                $student[$j]["CrV"] = $total_credits_valides_sem;
                
                //Calcul MPS
                if(($total_credits_sem==0))
                    $mps=0;
                else
                    $mps = round($mps/$total_credits_sem,2, PHP_ROUND_HALF_UP);
                
                $student[$j]["MPS"] = $mps;
                //Calcul MPC
                $studentsSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$std,"semester"=>$sem));
                if($studentsSemRegistration)
                { 
                    $classe_1 = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($datastring["classe"]);

                    $total_credits_valides_cycle += $studentsSemRegistration->getNbCredtisCapitalizedPrevious();
                    
                    $studentsSemRegistration->setMpsCurrentSem($mps);
                    $studentsSemRegistration->setTotalCreditRegisteredCurrentSem($total_credits_sem);
                    $studentsSemRegistration->setTotalCreditRegisteredCurrentClass($total_credits_classe);
                    $studentsSemRegistration->setNbCreditsCapitalizedCurrentSem($total_credits_valides_cycle);
                    

                   
                    //Number of time student has spent in the current cycle
                    //$sem_rank_mpc = $this->computeSemRanking($sem_rank);
                        
                    $sem_rank_mpc = $studentsSemRegistration->getCountingSemRegistration();
                    
                                        
                    $creditRegisteredCycle = $studentsSemRegistration->getTotalCreditRegisteredPreviousCycle()+$total_credits_sem-$this->getStudentBacklogsMarks($std, $sem, $classe);
                    
                    $totalCreditsCycle = $studentsSemRegistration->getTotalCreditsCyclePreviousYear();
                    $totalCreditsCycle+=$this->totalCreditPerSem($classe_1->getCode(),$sem->getCode());
                    
                    if($value->getIsStudentRepeating()==1)
                    {
                        
                        $creditRegisteredCycle = $studentsSemRegistration->getTotalCreditRegisteredPreviousCycle();
                        
                        //$creditRegisteredCycle = $studentsSemRegistration->getNbCredtisCapitalizedPrevious()+$total_credits_sem;
                    }
                   // elseif($value->getIsStudentRepeating()==1 && $sem_rank%2==0) $creditRegisteredCycle = $studentsSemRegistration->getTotalCreditRegisteredPreviousCycle()+$total_credits_sem;
  
                    $studentsSemRegistration->setTotalCreditRegisteredCurrentCycle($creditRegisteredCycle);
                   
                    //MPC calculation
                    
                    //Get the semester rank
                    
                    //$total_credits_cycle = $this->computeSemRanking($sem_rank)*30;
                    //$total_credits_cycle = $totalCreditsCycle; 
                    
                    
                    if($creditRegisteredCycle==0) $creditRegisteredCycle=1;
                    $total_credits_cycle = $creditRegisteredCycle;
                    
                    $mpc = ($studentsSemRegistration->getMpcPrevious()*($sem_rank_mpc-1)+$mps)/$sem_rank_mpc;
                    if(($datastring["classe"]=="MED7")&&($sem->getRanking()==13))
                    {
                        $total_credits_cycle = ($this->computeSemRanking($sem_rank-1))*30;
                        $mpc = $studentsSemRegistration->getMpcPrevious();
                    
                    }
                    
                    $mpc = round($mpc,2, PHP_ROUND_HALF_UP);
                    $studentsSemRegistration->setMpcCurrentSem($mpc);
                    $studentsSemRegistration->setAcademicProfile($this->computeMention($mpc));
                    $studentsSemRegistration->setNbCreditsCapitalizedCurrentSem($total_credits_valides_sem);
                    
                    $studentsSemRegistration->setValidationPercentage(round(($total_credits_valides_cycle/$creditRegisteredCycle)*100,1, PHP_ROUND_HALF_UP));
                    
                    //After calculating MPC, get it register to the next sem 
                    $year = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
                    //This block of instructions is executed only for the 1rst semster of a year
                    if($sem_rank%2<>0)
                    {
                        
 
                    
                        $nextSem = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$year,"ranking"=>$sem_rank+1));
                        $studentsNextSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$std,"semester"=>$nextSem));
                        //check if student is registered to the next sem before recording value
                        $sem_rank_mpc++;
                        $mpc = round($mpc,2, PHP_ROUND_HALF_UP); 
                        if($studentsNextSemRegistration) 
                        {   
                            $studentsNextSemRegistration->setMpcPrevious($mpc);
                            $studentsNextSemRegistration->setSemester($nextSem);
                            $studentsNextSemRegistration->setNbCreditsCapitalizedPreviousSem($total_credits_valides_sem);                            
                            $studentsNextSemRegistration->setNbCredtisCapitalizedPrevious($total_credits_valides_cycle);
                            $studentsNextSemRegistration->setTotalCreditRegisteredPreviousSem($total_credits_sem);
                            $studentsNextSemRegistration->setTotalCreditRegisteredPreviousCycle($creditRegisteredCycle);
                            $studentsNextSemRegistration->setTotalCreditsCyclePreviousYear($total_credits_cycle);                        
                            $studentsNextSemRegistration->setCountingSemRegistration($sem_rank_mpc);
                            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($datastring["classe"]);
                            $studentsNextSemRegistration->setTotalCreditsCurrentClass($this->totalCreditsPerYear($classe));
                            $this->entityManager->flush();                             
                        }else{ 
                            $studentsNextSemRegistration = new StudentSemRegistration();
                            $studentsNextSemRegistration->setStudent($std);
                            $studentsNextSemRegistration->setMpcPrevious($mpc);
                            $studentsNextSemRegistration->setSemester($nextSem);
                            $studentsNextSemRegistration->setNbCreditsCapitalizedPreviousSem($total_credits_valides_sem);
                            $studentsNextSemRegistration->setNbCredtisCapitalizedPrevious($total_credits_valides_cycle);
                            $studentsNextSemRegistration->setTotalCreditRegisteredPreviousSem($total_credits_sem);
                            $studentsNextSemRegistration->setTotalCreditRegisteredPreviousCycle($creditRegisteredCycle);
                            $studentsNextSemRegistration->setTotalCreditsCyclePreviousYear($total_credits_cycle);
                            $studentsNextSemRegistration->setCountingSemRegistration($sem_rank_mpc);
                            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($datastring["classe"]);
                            $studentsNextSemRegistration->setTotalCreditsCurrentClass($this->totalCreditsPerYear($classe));
                            
                            $this->entityManager->persist($studentsNextSemRegistration);

                        }
                    }
                    $students[$j]["MPC"] = $mpc; 
                }
                    $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($datastring["classe"]);
                    $ratioFailed = 1;
                    if($sem_rank%2==0)
                    {

                        //$ratio = $this->totalCreditsSucceed($std, $classe)/$this->totalCreditsPerYear($classe);
                        $ratio = $total_credits_valides_cycle/$total_credits_cycle;
                        
                        if($value->getIsStudentRepeating()==1)
                        {
                            $ratioFailed = $this->totalCreditsFailed($std,$classe)/$this->totalCreditsPerYear($classe) ;
                            $ratio = ($this->totalCreditsPerYear($classe)-$this->totalCreditsFailed($std,$classe))/$this->totalCreditsPerYear($classe);
                        } 
                        $ratio = $total_credits_valides_cycle/$total_credits_cycle;
                        $stdAdminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$std,"academicYear"=>$acadYr));
                     
                        $stdAdminRegistration->setDecision("ADM");
                       
                        switch ($studyLevel)
                        {
                            case 1:
                                
                                if(!$this->isCompulsorySubjectCleared($std,$classe))
                                    $stdAdminRegistration->setDecision("AJR");
                                elseif(($datastring["isSpecialDelibAllow"]==1)&&($this->isSpecialDelibAllow($std,$classe,$datastring["nbreUeDelibSpecial"])))
                                   $stdAdminRegistration->setDecision("ADM");
                                elseif(($ratioFailed <= 0.5))
                                    $stdAdminRegistration->setDecision("ADM");                                
                                elseif($ratio < 0.5)
                                    $stdAdminRegistration->setDecision("AJR");

                                break;
                            case 2:
                                //check backlog if tere is a single backlog the failed
                                if(!$this->isCompulsorySubjectCleared($std,$classe))
                                    $stdAdminRegistration->setDecision("AJR");
                                elseif($total_credits_cycle-$total_credits_valides_cycle>=30)
                                        $stdAdminRegistration->setDecision("AJR");
                                //elseif($this->isBacklogAvailable($std,$classe)) 
                                   // $stdAdminRegistration->setDecision("AJR");                                
                                elseif(($datastring["isSpecialDelibAllow"]==1)&&($this->isSpecialDelibAllow($std,$classe,$datastring["nbreUeDelibSpecial"])))
                                   $stdAdminRegistration->setDecision("ADM");
                               // elseif($ratioFailed <= 0.5)
                                 //   $stdAdminRegistration->setDecision("ADM");                                
                                elseif($ratio < 0.5)
                                    $stdAdminRegistration->setDecision("AJR");
                                elseif(!$this->isCompulsorySubjectCleared($std,$classe))
                                    $stdAdminRegistration->setDecision("AJR");

                                break;
                            case 3:
                                //check backlog if tere is a single backlog the failed
                                if($this->isBacklogAvailable($std,$classe)) 
                                    $stdAdminRegistration->setDecision("AJR");
                                elseif(($ratioFailed <= 0))
                                    $stdAdminRegistration->setDecision("ADM");
                                elseif($ratio < 1)
                                    $stdAdminRegistration->setDecision("AJR");

                                break; 
                            case 4: 
                                
                                //if($ratioFailed <= 0.40)
                                   // $stdAdminRegistration->setDecision("ADM");                                
 
                                if(!$this->isCompulsorySubjectCleared($std,$classe))
                                    $stdAdminRegistration->setDecision("AJR");
                                elseif(($datastring["isSpecialDelibAllow"]==1)&&($this->isSpecialDelibAllow($std,$classe,$datastring["nbreUeDelibSpecial"])))
                                   $stdAdminRegistration->setDecision("ADM");                                
                                elseif($ratio < 0.6)
                                    $stdAdminRegistration->setDecision("AJR");
                                break;
                            case 5:
                                if(!$this->isCompulsorySubjectCleared($std,$classe))
                                    $stdAdminRegistration->setDecision("AJR");
                                elseif(($datastring["isSpecialDelibAllow"]==1)&&($this->isSpecialDelibAllow($std,$classe,$datastring["nbreUeDelibSpecial"])))
                                   $stdAdminRegistration->setDecision("ADM");
                                //check backlog if there is a single backlog the failed
                                elseif($this->isBacklogAvailable($std,$classe))
                                    $stdAdminRegistration->setDecision("AJR");
                                elseif(($ratioFailed <= 0))
                                    $stdAdminRegistration->setDecision("ADM");
                                elseif($ratio < 1)
                                    $stdAdminRegistration->setDecision("AJR");
                                break;
                           case 6:
                                //check backlog if tere is a single backlog the failed
                                
                                if($this->isBacklogAvailable($student, $classe))
                                    $stdAdminRegistration->setDecision("AJR");
                                elseif(($ratioFailed <= 0))
                                    $stdAdminRegistration->setDecision("ADM");
                                elseif($ratio < 1)
                                    $stdAdminRegistration->setDecision("AJR");
                                break;                                
                                
                        }
                        
                    } 
                $this->entityManager->flush();    
                $j++;
            }
            


            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $student
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function importStudentPvAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {     
          
            /* Getting file name */
           $filename = $_FILES['file']['name'];
           /* Location */
           $location = './public/upload/';

           $csv_mimetypes = array(
               'text/csv',
               'application/csv',
               'text/comma-separated-values',
               'application/excel',
               'application/vnd.ms-excel',
               'application/vnd.msexcel',
            );
        // Check if fill type is allowed  
          if(!in_array($_FILES['file']['type'],$csv_mimetypes))
          {
             $result = false;

              $view = new JsonModel([
                $result
              ]);
              return $view; 
          }

            /* Upload file */
            move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);


            $reader = new Csv(); 
            $spreadsheet = $reader->load($location.$filename);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            
            //set status to 0
            //Student is currently in draft mode
            $status = 0;
            if (!empty($sheetData)) {
                for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                    $row["matricule"] = $sheetData[$i][0];
                    $row["classe"] = $sheetData[$i][1];
                    $row["mpc"] = $sheetData[$i][2];
                    $row["credits_capitalized"] = $sheetData[$i][3];
                    $row["total_credits_registered"] = $sheetData[$i][4];
                    $row["total_credits"] = $sheetData[$i][5];
                    $row["repeating"] = $sheetData[$i][6];
                    $row["count_sem_registration"] = $sheetData[$i][7];
                    
            $this->studentManager->stdAdminRegistration($row,$status,$row["repeating"]);
            $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($row["matricule"]);
            if($std)
                    $this->studentManager->stdSemesterRegistration($row["classe"],$std,$row["mpc"],$row["credits_capitalized"],$row["total_credits_registered"],$row["total_credits"],$row["count_sem_registration"]);            
                }
            }
            
    
            
        $this->entityManager->getConnection()->commit();
       

        $arr = array("name"=>$filename);
        $result = true;
        
          $view = new JsonModel([
              $result
         ]);
        
// Disable layouts; `MvcEvent` will use this View Model instead
       // $view->setTerminal(true);

        return $view;      
    }
    catch(Exception $e)
    {
       $this->entityManager->getConnection()->rollBack();
        throw $e;

    }
    }
    
    public function importStudentMpcAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {     
          
            /* Getting file name */
           $filename = $_FILES['file']['name'];
           /* Location */
           $location = './public/upload/';

           $csv_mimetypes = array(
               'text/csv',
               'application/csv',
               'text/comma-separated-values',
               'application/excel',
               'application/vnd.ms-excel',
               'application/vnd.msexcel',
            );
        // Check if fill type is allowed  
          if(!in_array($_FILES['file']['type'],$csv_mimetypes))
          {
             $result = false;

              $view = new JsonModel([
                $result
              ]);
              return $view; 
          }

            /* Upload file */
            move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);


            $delimiter = ';';
            $file = new \SplFileObject($location.$filename);
            $reader = new CsvReader($file,$delimiter);
            // Tell the reader that the first row in the CSV file contains column headers
            $reader->setHeaderRowNumber(0);
            $workflow = new Workflow($reader);

            // Create a writer: you need Doctrine’s EntityManager.
            $doctrineWriter = new DoctrineWriter($this->entityManager, Student::class);
            $doctrineWriter->disableTruncate();
            $workflow->addWriter($doctrineWriter,['matricule']);

        //set status to 0
        //Student is currently in draft mode
        $status = 0;
        foreach ($reader as $row) {

            
            $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($row["matricule"]);
            if($std)
                $this->studentManager->stdSemesterRegistration($row["classe"],$std,$row["mpc"],$row["credits_capitalized"],$row["total_credits_registered"],$row["total_credits"],$row["count_sem_registration"]);

        }
        
        $this->entityManager->flush();    
        $this->entityManager->getConnection()->commit();
       

        $arr = array("name"=>$filename);
        $result = true;
        
          $view = new JsonModel([
              $result
         ]);
        
// Disable layouts; `MvcEvent` will use this View Model instead
       // $view->setTerminal(true);

        return $view;      
    }
    catch(Exception $e)
    {
       $this->entityManager->getConnection()->rollBack();
        throw $e;

    }
    }    
    public function importStudentFinanceAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {     
          
            /* Getting file name */
           $filename = $_FILES['file']['name'];
           /* Location */
           $location = './public/upload/';

           $csv_mimetypes = array(
               'text/csv',
               'application/csv',
               'text/comma-separated-values',
               'application/excel',
               'application/vnd.ms-excel',
               'application/vnd.msexcel',
            );
        // Check if fill type is allowed  
          if(!in_array($_FILES['file']['type'],$csv_mimetypes))
          {
             $result = false;

              $view = new JsonModel([
                $result
              ]);
              return $view; 
          }

            // Upload file 
            move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);


            $delimiter = ';';
            $file = new \SplFileObject($location.$filename);
            $reader = new CsvReader($file,$delimiter);
            // Tell the reader that the first row in the CSV file contains column headers
            $reader->setHeaderRowNumber(0);
            $workflow = new Workflow($reader);

            // Create a writer: you need Doctrine’s EntityManager.
            $doctrineWriter = new DoctrineWriter($this->entityManager, Student::class);
            $doctrineWriter->disableTruncate();
            $workflow->addWriter($doctrineWriter,['matricule']);

        //set status to 0
        //Student is currently in draft mode
        $status = 0;
        foreach ($reader as $row) {

            $this->studentManager->updateStdFinancialInfos($row);
       
        }
        
            
        $this->entityManager->getConnection()->commit();
       

        $arr = array("name"=>$filename);
        $result = true;
        
          $view = new JsonModel([
              $result
         ]);
        
// Disable layouts; `MvcEvent` will use this View Model instead
       // $view->setTerminal(true);

        return $view;      
    }
    catch(Exception $e)
    {
       $this->entityManager->getConnection()->rollBack();
        throw $e;

    }
    }
    public function importAdmittedStudentAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {     

            // Getting file name 
           $filename = $_FILES['file']['name'];
           // Location 
           $location = './public/upload/';

           $csv_mimetypes = array(
               'text/csv',
               'application/csv',
               'text/comma-separated-values',
               'application/excel',
               'application/vnd.ms-excel',
               'application/vnd.msexcel',
            );
        // Check if fill type is allowed  
          if(!in_array($_FILES['file']['type'],$csv_mimetypes))
          {
             $result = false;

              $view = new JsonModel([
                $result
              ]);
              return $view; 
          }

            // Upload file 
            move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);


            $reader = new Csv(); 
            $spreadsheet = $reader->load($location.$filename);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            if (!empty($sheetData)) {
                for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                    $row["nom"] = $sheetData[$i][0];
                    $row["prenom"] = $sheetData[$i][1];
                    $row["telephone"] = $sheetData[$i][2];
                    $row["classe"] = $sheetData[$i][3];
                    $row["frais_admission"] = $sheetData[$i][4];
                    $row["date_admission"] = $sheetData[$i][5];
                    $row["type_concours"] = $sheetData[$i][6];
                   

                    $this->studentManager->addAdmittedStudent($row);

                }
            }


        $this->entityManager->getConnection()->commit();

        $result = true;

          $view = new JsonModel([
              $result
         ]);

// Disable layouts; `MvcEvent` will use this View Model instead
       // $view->setTerminal(true);

            return $view;      
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    } 
    
    public function furthersubjectregistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables
	  $data = $this->params()->fromQuery(); 
          var_dump($data);          exit();
           // }
            
          $view = new JsonModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }       
    }
    public function printStdListAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables
	  $data = $this->params()->fromRoute();
          $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("onlineRegistrationDefaultYear"=>1));
          $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($data["classeCode"]);
          $degree = $classOfStudy->getDegree();
          $fieldOfStudy= $degree->getFieldStudy();
          $faculty = $fieldOfStudy->getFaculty();
          $filiere = $fieldOfStudy->getName();
          $school = $faculty->getSchool();
          $classe = $data["classeCode"];
         
          $students = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->findBy(array("class"=>$data["classeCode"],"status"=>1),array("nom"=>"ASC"));
                foreach($students as $key=>$value)
                {
                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $students[$key] = $data;

                }
                
                $brandInfo = "Report generated with UdMAcademy By W-TECH(" . date("d-m-Y H:i") .")";
            
          $view = new ViewModel([
              "acadYr"=>$acadYr->getCode(),
              "students"=>$students,
              "school"=>$school->getName(),
              "faculty"=>$faculty->getName(),
              "filiere"=>$fieldOfStudy->getName(),
              "classe"=>$classe,
              "logo"=>$school->getLogo(),
              "schoolName"=>$school->getName(),
              "brandInfo" =>$brandInfo
              
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }       
    } 
    
    public function clearUnitRegistrationDuplicatesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables

          $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
          $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr);
        
          $students = $this->entityManager->getRepository(RegisteredStudentView::class)->findAll();
                foreach($students as $key=>$value)
                {
                    foreach($semesters as $sem)
                    {
                        
                        $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($value->getMatricule());
                        $classe= $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$std,"academicYear"=>$acadYr));
                        $classe = $classe->getClassOfStudy();

                        $subjects = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(array("classOfStudy"=>$classe,"semester"=>$sem));
                        foreach($subjects as $sub)
                        {
                            $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"semester"=>$sem,'teachingUnit'=>$sub->getTeachingUnit()));
                            if(sizeof($unitRegistration)>1)
                            {
                                for( $i=1;$i<sizeof($unitRegistration);$i++)
                                {
                                   
                                    $this->entityManager->remove($unitRegistration[$i]);
                                    
                                }


                            }
                        }
                    }
                    
                }
            
          $view = new JsonModel([
              
             "msge"=>true
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);
          $this->entityManager->flush();
          $this->entityManager->commit();
        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }          
    }
    
    public function picturesGeneratorAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables

          $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
          $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr);
        
          $students = $this->entityManager->getRepository(RegisteredStudentView::class)->findByStatus(1);
          
          $zip = new \ZipArchive();
          $zip->open($_SERVER["DOCUMENT_ROOT"].'/zip/photos.zip');
                foreach($students as $key=>$value)
                {
                    $stud = $this->entityManager->getRepository(Student::class)->findOneByMatricule($value->getId());
                    
                    if($stud->getPhoto())
                    {
                        $imgdataBin= base64_decode(stream_get_contents($stud->getPhoto()));

                        
                            $im = imagecreatefromstring($imgdataBin);

                        // Specify the location where you want to save the image
                        $img_file_jpeg = $_SERVER["DOCUMENT_ROOT"].'/img/'.$stud->getMatricule().'.jpeg';
                        $img_file_png = $_SERVER["DOCUMENT_ROOT"].'/img/'.$stud->getMatricule().'.png';
                       // $img_file_bmp = $_SERVER["DOCUMENT_ROOT"].'/img/'.$stud->getMatricule().'.bmp';

                        // Save the GD resource as jpeg in the best possible quality (no compression)
                        // This will strip any metadata or invalid contents (including, the PHP backdoor)
                        // To block any possible exploits, consider increasing the compression level
                                                //imagebmp($im, $img_file_bmp);
                                                imagepng($im, $img_file_png, 0);
						imagejpeg($im, $img_file_jpeg);
						// Libération de la mémoire
						imagedestroy($im);
                        if ($zip->open($_SERVER["DOCUMENT_ROOT"].'/zip/photos.zip') === TRUE) {
                            $zip->addFile($img_file_jpeg, $stud->getMatricule().'.jpeg');
                            $zip->addFile($img_file_jpeg, $stud->getMatricule().'.png');
                            //$zip->addFile($_SERVER["DOCUMENT_ROOT"].'/img/'.$stud->getMatricule().'.bmp', $stud->getMatricule().'.bmp');
                        
                        
                        }
                    }
                    
                }
                $zip->close();
            
          $view = new JsonModel([
              
             "msge"=>true
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);
          $this->entityManager->commit();
        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }          
    }    
    
   
    public function studentsDataGeneratorAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // create spreadsheet header
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle("Udmacademy By ppwangun");
            $sheet->setCellValue('A1', 'Matricule');
            $sheet->setCellValue('B1', 'Genre');
            $sheet->setCellValue('C1', 'Nom');
            $sheet->setCellValue('D1', 'Prénom');
            $sheet->setCellValue('E1', 'Classe');
            $sheet->setCellValue('F1', 'Tel Etudiant');
            $sheet->setCellValue('G1', 'Email');
            $sheet->setCellValue('H1', 'Date de naissance');
            $sheet->setCellValue('I1', 'Lieu de naissance');
            $sheet->setCellValue('J1', 'Région d\'origine');
            $sheet->setCellValue('K1', 'Nationnalité');
            $sheet->setCellValue('L1', 'Réligion');
            $sheet->setCellValue('M1', 'Status matrimonial');
            $sheet->setCellValue('N1', 'Satatut d\'emploi');
            $sheet->setCellValue('O1', 'Nom du père');
            $sheet->setCellValue('P1', 'Tel père');
            $sheet->setCellValue('Q1', 'Email du père');
            $sheet->setCellValue('R1', 'Nom de la mère');
            $sheet->setCellValue('T1', 'Tel de la mère');
            $sheet->setCellValue('U1', 'Email de la mère');
            $sheet->setCellValue('V1', 'Nom du sponsor');
            $sheet->setCellValue('X1', 'Tel du sponsor');
            $sheet->setCellValue('X1', 'Email du sponsor');            
            
          
           // Retrieve all registered students
          //start writting from the 2nd row
          $i=2;

          $students = $this->entityManager->getRepository(RegisteredStudentView::class)->findByStatus(1);
          foreach($students as $std)
          {
             $sheet->setCellValueByColumnAndRow(1, $i, $std->getMatricule());
             $sheet->setCellValueByColumnAndRow(2, $i, $std->getGender());
             $sheet->setCellValueByColumnAndRow(3, $i, $std->getNom());
             $sheet->setCellValueByColumnAndRow(4, $i, $std->getPrenom());
             $sheet->setCellValueByColumnAndRow(5, $i, $std->getclass());
             $sheet->setCellValueByColumnAndRow(6, $i, $std->getStdPhoneNumber());
             $sheet->setCellValueByColumnAndRow(7, $i, $std->getEmail());
             $sheet->setCellValueByColumnAndRow(8, $i, $std->getBirthDate());
             $sheet->setCellValueByColumnAndRow(9, $i, $std->getBornPlace());
             $sheet->setCellValueByColumnAndRow(10, $i, $std->getRegionOfOrigin());
             $sheet->setCellValueByColumnAndRow(11, $i, $std->getNationality());
             $sheet->setCellValueByColumnAndRow(12, $i, $std->getReligion());
             $sheet->setCellValueByColumnAndRow(13, $i, $std->getMaritalStatus());
             $sheet->setCellValueByColumnAndRow(14, $i, $std->getWorkingStatus());
             $sheet->setCellValueByColumnAndRow(15, $i, $std->getFatherName());
             $sheet->setCellValueByColumnAndRow(16, $i, $std->getFatherPhoneNumber());
             $sheet->setCellValueByColumnAndRow(17, $i, $std->getFatherEmail()); 
             $sheet->setCellValueByColumnAndRow(18, $i, $std->getMotherName());
             $sheet->setCellValueByColumnAndRow(19, $i, $std->getMotherPhoneNumber());
             $sheet->setCellValueByColumnAndRow(20, $i, $std->getMotherEmail());   
             $sheet->setCellValueByColumnAndRow(21, $i, $std->getSponsorName());
             $sheet->setCellValueByColumnAndRow(22, $i, $std->getSponsorPhoneNumber());
             $sheet->setCellValueByColumnAndRow(23, $i, $std->getSponsorEmail());             
             
             
             
             $i++;
          }
          
                
          $writer = new Xlsx($spreadsheet);
          $writer->save($_SERVER["DOCUMENT_ROOT"].'/zip/studentsDetails.xlsx');
          $view = new JsonModel([
              
             "msge"=>true
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);
          $this->entityManager->commit();
        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }          
    }  
 public function resetPedagogicRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables
            $data = $this->params()->fromQuery();  

          $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("onlineRegistrationDefaultYear"=>1));
          $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr);
          $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["matricule"]);       
          $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$student,"academicYear"=>$acadYr));
          /*$semToClasse = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findByClassOfStudy($adminRegistration->getClassOfStudy());
          $i=0;
          foreach($semToClasse as $sem)
          {
              $semesters[$i] = $sem->getSemester();
              $i++;
          }*/
          $adminRegistration->setStatus(2);
          $this->resetAcademicRegistration($student,$semesters);
          
          $this->entityManager->flush();
          $this->entityManager->commit();
            
          $view = new JsonModel([
              
             "msge"=>true
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);
          
        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }          
    }  
    
    public function resetAdministrativeRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables
            $data = $this->params()->fromQuery();  

          $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("onlineRegistrationDefaultYear"=>1));
          $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr);
                  
          $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["matricule"]);
          $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$student,"academicYear"=>$acadYr));
          /*$semToClasse = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findByClassOfStudy($adminRegistration->getClassOfStudy());
          $i=0;
          foreach($semToClasse as $sem)
          {
              $semesters[$i] = $sem->getSemester();
              $i++;
          }*/
          
          $adminRegistration->setStatus(0);
          $this->resetAcademicRegistration($student,$semesters);
          $this->entityManager->flush();
          $this->entityManager->commit();
            
          $view = new JsonModel([
              
             "msge"=>true
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);
          
        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }          
    }  
    public function suspendRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables
            $data = $this->params()->fromQuery();  

          $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("onlineRegistrationDefaultYear"=>1));
          $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr);
                  
          $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["matricule"]);
          $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$student,"academicYear"=>$acadYr));
          $adminRegistration->setStatus(4);
          $this->resetAcademicRegistration($student,$semesters);
          $this->entityManager->flush();
          $this->entityManager->commit();
            
          $view = new JsonModel([
              
             "msge"=>true
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);
          
        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }          
    } 
    
    public function leaveTrainingAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables
            $data = $this->params()->fromQuery();  

          $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("onlineRegistrationDefaultYear"=>1));
          $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr);
                  
          $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["matricule"]);
          $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$student,"academicYear"=>$acadYr));
          $adminRegistration->setStatus(5);
          
          $this->entityManager->flush();
          $this->entityManager->commit();
            
          $view = new JsonModel([
              
             "msge"=>true
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);
          
        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }          
    }     
    private function resetAcademicRegistration($student,$semesters)
    {
        foreach($semesters as $sem)
        {
            $unitRegistrations =  $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("semester"=>$sem,"student"=>$student));
            foreach($unitRegistrations as $unitR)
            {
                if($unitR->getIsRepeated()!=1)
                    $this->entityManager->remove($unitR);
            }
        }
    }
    
    private function findCourses($studyLevel,$degreeCode)
    {
        return $this->entityManager->createQuery('SELECT e FROM Application\Entity\CurrentYearTeachingUnitView e'
                        .' WHERE e.studyLevel <= :studyLevel and e.degreeCode like :degreeCode')
                ->setParameter('studyLevel', $studyLevel)
                ->setParameter('degreeCode', $degreeCode)

                ->getResult();
    }
    
    //this function takes as paramter class and course and returns truve if the subject belongs to the class
    private function checkIsCurrentClassSubject($classe,$sem,$courseID)
    {
          
        return $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)
                ->findOneBy(array("classe"=>$classe,"semester"=>$sem,"id"=>$courseID));

       
        
    }
    private function checkIscurrentYearSem($sem)
    {
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)
                ->findOneByIsDefault(1);
        return $this->entityManager->getRepository(Semester::class)
                ->findOneBy(array("academicYear"=>$acadYr,"id"=>$sem));
    }
    
    
    private function computeMention($mpc)
    {
        $profilAcademic = $this->entityManager->getRepository(ProfileAcademic::class)->findAll();
        foreach($profilAcademic as $prof)
        {
            if($mpc>=$prof->getMinval()&& $mpc<=$prof->getMaxval()) return $prof->getGrade();
        }
    } 
    
    
    private function computeSemRanking($rank)
    {
        if($rank==6) return 6;
        if($rank==11) return 1;
        if($rank==12) return 2;
        if($rank==13) return 3;
        if($rank==14) return 4;
        return $rank%6;
        
    } 
    
    private function totalCreditPerSem($classe,$sem)
    {
        
        $courses = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findBy(array("classe"=>$classe,"semester"=>$sem,"isPreviousYearSubject"=>0));
        $credits=0;
        foreach($courses as $course)
        {
            $credits+=$course->getCredits();
        }
        
        return $credits;
    }
    
    private function totalCreditsPerCycle($classe,$sem)
    {
        $sem_rank = $sem->getRanking();
        $credits = 0;
        //$credits = $this->totalCreditPerSem($classe, $sem->getCode());
        $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("code"=>$classe));
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
        $studyLevel = $classe->getStudyLevel(); 
        
        $degree = $classe->getDegree();
        $cycle = 0;
        if($studyLevel<=3) $beginingCycle =1;
        if($studyLevel>3 && $studyLevel<=5) $beginingCycle = 4;
        if($studyLevel>5) $beginingCycle= 6;
        for($i=$studyLevel;$i>=$beginingCycle;$i--)
        {
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("studyLevel"=>$i,"degree"=>$degree));
           
            $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr)); 
            
            foreach($semesters as $sem)
            {
               if($sem->getSemester()->getRanking()<=$sem_rank)
                $credits += $this->totalCreditPerSem($classe->getCode(), $sem->getSemester()->getCode());
                
            }
 
        }
 
        return $credits;
    }
    
    //Total credit for the current class. All semesters are included
    private function totalCreditsPerYear($classe)
    {

        $credits = 0;
        //$credits = $this->totalCreditPerSem($classe, $sem->getCode());
        
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
        $studyLevel = $classe->getStudyLevel(); 

        $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr)); 
            
        foreach($semesters as $sem)
        {
 
            $credits += $this->totalCreditPerSem($classe->getCode(), $sem->getSemester()->getCode());

        }

        
        return $credits;

    } 

    //Calculates the total credits failed in the current class
    private function totalCreditsRegisteredCurrentClass($std,$classe)
    {
        $totalCredits = 0;

       
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
        $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr)); 
        foreach($semesters as $sem)
        {         
            $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"semester"=>$sem->getSemester(),"subject"=>[NULL," "]));
         
            foreach ($unitRegistration as $unitR)
            {
                $credits  = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("teachingUnit"=>$unitR->getTeachingUnit(),"semester"=>$sem->getSemester(),"classOfStudy"=>$classe));
                if($credits)
                    $totalCredits += $credits->getCredits();
            }
          
        }

          
        return $totalCredits;
    }    

    //Calculates the total credits failed in the current class
    private function totalCreditsSucceed($std,$classe)
    {
        $totalCredits = 0;

       
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
        $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr)); 
        foreach($semesters as $sem)
        {         
            $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"semester"=>$sem->getSemester(),"subject"=>[NULL," "]));
         
            foreach ($unitRegistration as $unitR)
            {
                if($unitR->getGrade()!="F"&&$unitR->getGrade()!="E"&&$unitR->getGrade()!=NULL&&$unitR->getGrade()!="")
                {
                    $credits  = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("teachingUnit"=>$unitR->getTeachingUnit(),"semester"=>$sem->getSemester(),"classOfStudy"=>$classe));
                    if($credits)
                        $totalCredits += $credits->getCredits();
                }
            }
          
        }
        return $totalCredits;
    }
    //Calculates the total credits failed in the current class
    private function totalCreditsFailed($std,$classe)
    {
        $totalCredits = 0;

       
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
        $semesters = $this->entityManager->getRepository(Semester::class)->findBy(array("academicYear"=>$acadYr)); 
        foreach($semesters as $sem)
        {         
            $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"semester"=>$sem,"subject"=>[NULL," "]));
         
            foreach ($unitRegistration as $unitR)
            {
                if($unitR->getGrade()=="F"||$unitR->getGrade()=="E"||$unitR->getGrade()==" "||$unitR->getGrade()==NULL)
                {
                    $credits  = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("teachingUnit"=>$unitR->getTeachingUnit(),"semester"=>$sem,"classOfStudy"=>$classe));
                    if($credits)
                        $totalCredits += $credits->getCredits();
                }
            }
          
        }
       
        return $totalCredits;
    }
    //this function takes as parameter, student and classe 
    //It checks wtether or not student has non validated subject from previous classes
    private function isBacklogAvailable($student,$classe)
    {
        // level of the current classe 
        $studyLevel = $classe->getStudyLevel();
        //As we are planing to check 
        $degree = $classe->getDegree();
        
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
        if($studyLevel<=3) $beginingCycle =1;
        if($studyLevel>3 && $studyLevel<=5) $beginingCycle = 4;
        if($studyLevel>5) $beginingCycle= 6;
        
        for($i=$beginingCycle;$i<$studyLevel;$i++)
        {
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("degree"=>$degree,"studyLevel"=>$i));
            
            $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr)); 
            foreach($semesters as $sem)
            {
                $unitRegistrations =  $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("semester"=>$sem->getSemester(),"student"=>$student,"subject"=>[NULL," "]));    

                    foreach($unitRegistrations as $unitR)
                        if($unitR->getGrade()=="F"||$unitR->getGrade()=="E"||$unitR->getGrade()==NULL||$unitR->getGrade()=="")
                            return true;
            }
        }

        
        return false;
    } 
    
    private function isCompulsorySubjectCleared($student,$classe)
    {
        $totalCredits = 0;

       
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
        $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr)); 
        foreach($semesters as $sem)
        {         
            $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"semester"=>$sem->getSemester(),"subject"=>[NULL," "]));
         
            foreach ($unitRegistration as $unitR)
            {
                if($unitR->getTeachingUnit()->getIsCompulsory()==1)
                {
                    if($unitR->getGrade()=="F"||$unitR->getGrade()=="E"||$unitR->getGrade()==NULL||$unitR->getGrade()=="")
                            return false;
                }
            }
          
        }
        return true;
    } 
    
    private function isSpecialDelibAllow($std,$classe,$nbreSubjects)
    {
        $totalCredits = 0;

       
        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
        $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr)); 
        foreach($semesters as $sem)
        {         
            $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"semester"=>$sem->getSemester(),"subject"=>[NULL," "]));
         
            foreach ($unitRegistration as $unitR)
            {
                if($unitR->getGrade()=="F" || $unitR->getGrade()=="E" || $unitR->getGrade()==NULL || $unitR->getGrade()=="")
                {
                    $credits  = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("teachingUnit"=>$unitR->getTeachingUnit(),"semester"=>$sem->getSemester(),"classOfStudy"=>$classe));
                    if($credits)
                        $totalCredits ++;
                }
            }
          
        }
        if($totalCredits<=$nbreSubjects) return true;
        return false;
    }   
    
     private function  getStudentBacklogsMarks($std,$sem,$classe)
    {
        $flag =false;
        $total_credits = 0;
        $mps = 0;
        $total_credits_valides = 0;
        $k=0;
        $credits = 0;
        
        if($sem->getRanking()%2==0) 
            $maxRanking = $sem->getRanking()-2;
        else $maxRanking = $sem->getRanking()-1;
             //hjh               
        for($rank=1;$rank<=$maxRanking;$rank++)
        {          
            if($rank%2==$sem->getRanking()%2)
            { 
                $sems = $this->entityManager->getRepository(Semester::class)->findByRanking($rank);
                foreach($sems as $sem)
                {
                    //Retrive all courses to which student is registered for a given semester
                    if($sem->getAcademicYear()->getIsDefault()==1)
                    {   
                    $backlogs = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"semester"=>$sem,"subject"=>[NULL," "]));
                    foreach($backlogs as $unitR)
                    {
                        $coshs= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("teachingUnit"=>$unitR->getTeachingUnit(),"semester"=>$sem,"status"=>1));
                        if($coshs)
                        $credits  +=$coshs->getCredits();
                    }

                    }
                }
            }
        }
       
        return $credits;
    }
}    

