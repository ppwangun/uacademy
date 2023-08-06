<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SchoolGlobalConfig\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\Hydrator\Reflection as ReflectionHydrator;
use SchoolGlobalConfig\Form\AnneeAcadForm;

use Application\Entity\Faculty;
use Application\Entity\Department;
use Application\Entity\FieldOfStudy;
use Application\Entity\Speciality;
use Application\Entity\Degree;
use Application\Entity\TrainingCurriculum;
use Application\Entity\AcademicYear;
use Application\Entity\Semester;
use Application\Entity\ClassOfStudy;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\AdminRegistration;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\UnitRegistration;
use Application\Entity\StudentSemRegistration;
use Application\Entity\CurrentYearTeachingUnitView;

class IndexController extends AbstractActionController
{
    
    private $entityManager;
    private $activeYear;
    private $currentYear;
    
    const _ETUDIANT_EXCLU_ = 6;
    const _ETUDIANT_DIPLOME_ = 7;


    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
    public function indexAction()
    {
        return [];
    }
    
    public function filfrmdegreeAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery(); 
            $conn = $this->entityManager->getConnection();
            $sql = '
                SELECT *
                FROM degree d
                INNER JOIN field_of_study f  ON d.field_study_id = f.id
                WHERE d.id = :id
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('id' => $data['id']));
            $results = $stmt->fetch();
        
        return new JsonModel([
                $results
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
    }
    public function changeOnlineRegistrationDefaultAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery();
            $onlineRegYrs = $this->entityManager->getRepository(AcademicYear::class)->findAll();
            foreach($onlineRegYrs as $regYer)
            {
                $regYer->setOnlineRegistrationDefaultYear(0);
                
            }
            $onlineRegYr = $this->entityManager->getRepository(AcademicYear::class)->find($data["id"]);
             
            $onlineRegYr->setOnlineRegistrationDefaultYear(1);
            $this->entityManager->flush();
            
            $this->entityManager->getConnection()->commit();
            
        return new JsonModel([
                
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
    }    
    public function onlineRegistrationDefaultAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery();

            $onlineRegYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBY(["onlineRegistrationDefaultYear"=>1]);

            $result = $onlineRegYr->getId();
            $this->entityManager->getConnection()->commit();
            
        return new JsonModel([
           $result
                
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
    }    
    public function searchAcademicYearAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $activeYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
            $data = $this->params()->fromQuery(); 
            $conn = $this->entityManager->getConnection();
            $sql = '
                SELECT *
                FROM academic_year a
                
                WHERE a.code like :id And A.starting_date <= :date 
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('id' => "%".$data['id']."%",'date' => $activeYr->getStartingDate()->format('Y-m-d H:i:s')));
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
    public function searchSemByClasseAndAcadYrAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery(); 
            $conn = $this->entityManager->getConnection();
            $sql = '
                SELECT *
                FROM academic_year a
                
                WHERE a.code like :id
                ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(array('id' => "%".$data['id']."%"));
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
    
    public function subjectbyueAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
        $data = $this->params()->fromQuery();  
        $ue = [];
        if($data)
        {
            $query = $this->entityManager->createQuery('SELECT  s.id,s.subjectName,s.subjectCode,c1.code as classCode,c.subjectCredits,c.subjectWeight,'
            . 'c.subjectHours,c.subjectCmHours,c.subjectTdHours,c.subjectTpHours  FROM Application\Entity\ClassOfStudyHasSemester c '
            . 'JOIN c.classOfStudy c1 '
            . 'JOIN c.semester sem '
            . 'JOIN sem.academicYear acad '
            . 'JOIN c.subject s '
            . 'WHERE s.teachingUnit = ?1 AND acad.isDefault=1 AND c.status = 1'
            );
            $query->setParameter(1, $data["id"]);
            $ue = $query->getResult(); 
           
        }
        $this->entityManager->getConnection()->commit();
        return new JsonModel([
                $ue
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
                    $this->entityManager->getConnection()->commit();
          
          
            throw $e; 
            
      }
        
    }
    public function semesterbyacademicyearAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
       try
        { 
           $data = $this->params()->fromQuery();  
            $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneById($data["id"]);
            $sem =  $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadyr);
           
            foreach($sem as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $sem[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                $sem
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
        
    }    
    //Collect degrees bases  on training curriculum
    public function cyclebydegreeAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery(); 
            $degree = $this->entityManager->getRepository(Degree::class)->findOneById($data['id']);
            $cycles = $this->entityManager->getRepository(TrainingCurriculum::class)->findBy(array('degree'=>$degree));
            foreach($cycles as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $cycles[$key] = $data;
            }
        $this->entityManager->getConnection()->commit();
        return new JsonModel([
                $cycles
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
        
    }
    public function searchDptByFacultyAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery();            
            $faculty = $this->entityManager->getRepository(Faculty::class)->find($data['fac_id']);
            $dpts = $this->entityManager->getRepository(Department::class)->findBy(array('faculty'=>$faculty),array("name"=>"ASC"));
            foreach($dpts as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $dpts[$key] = $data;
            }
        $this->entityManager->getConnection()->commit();
        return new JsonModel([
                $dpts
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
        
    }   
    
    public function searchFilByDptAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery();            
            $dpt= $this->entityManager->getRepository(Department::class)->find($data['dpt_id']);
            $filieres = $this->entityManager->getRepository(FieldOfStudy::class)->findBy(array('department'=>$dpt),array("name"=>"ASC"));
            foreach($filieres as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $data['fac_id'] = $value->getFaculty()->getId();
               ($value->getDepartment())? $data['dpt_id'] = $value->getDepartment()->getId():$data['dpt_id']=-1;
                $filieres[$key] = $data;
            }
        $this->entityManager->getConnection()->commit();
        return new JsonModel([
                $filieres
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
        
    } 
    public function searchSpeByFilAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery(); 
            (isset($data['fil_id']))?"":$data["fil_id"]=-1;
            $fil= $this->entityManager->getRepository(FieldOfStudy::class)->find($data['fil_id']);
            $spes = $this->entityManager->getRepository(Speciality::class)->findBy(array('fieldOfStudy'=>$fil),array("name"=>"ASC"));
            foreach($spes as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $spes[$key] = $data;
                $filiere = $value->getFieldOfStudy();
                $spes[$key]["fieldOfStudy"] = null;
                $spes[$key]['fil_id'] = $filiere->getId();
               $spes[$key]['fac_id'] = $filiere->getFaculty()->getId();
               ($filiere->getDepartment())? $spes[$key]['dpt_id'] = $filiere->getDepartment()->getId():$spes[$key]['dpt_id']=-1;
            }
        $this->entityManager->getConnection()->commit();
        return new JsonModel([
                $spes
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
        
    }
    public function searchFilByFacultyAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery();            
            $fac= $this->entityManager->getRepository(Faculty::class)->find($data['fac_id']);
            $filieres = $this->entityManager->getRepository(FieldOfStudy::class)->findBy(array('faculty'=>$fac),array("name"=>"ASC"));
            foreach($filieres as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $data['fac_id'] = $value->getFaculty()->getId();
               ($value->getDepartment())? $data['dpt_id'] = $value->getDepartment()->getId():$data['dpt_id']=-1;
                $filieres[$key] = $data;
            }
        $this->entityManager->getConnection()->commit();
        return new JsonModel([
                $filieres
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
        
    }    
    
    public function newacadyrAction()
    {
        //$form = new AnneeAcadForm();
        $view = new ViewModel([
            //'form'=>$form,
            
        ]);

        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;
    }
    
    public function anneedetailsAction()
    {
    
        $view = new ViewModel([
 
        ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }
    
    public function dashboardAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }
    public function facultytplAction()
    {

        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }
    public function filieretplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }
    
    public function newFilAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }   
    public function updateFilAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }     
    public function specialitetplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    } 
    public function newSpeAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }   
    public function updateSpeAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }     
    public function departmentplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }
    public function newDeptAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }   
    public function updateDptAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }    
    public function degreetplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }
    public function newdegreetplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }
    public function classetplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }
    public function newclassetplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
    }
  
    public function teachingunitplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }
    public function assignedteachingunitplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }
    public function newteachingunitplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }
        
    public function assignnewteachingunitplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }  
    public function institutiontplAction()
    {

        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }
    
    
    public function findsemesterAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromQuery(); 
           
            $query = $this->entityManager->createQuery('SELECT c.id,c.code,c.name FROM Application\Entity\Semester c'
                    .' JOIN c.academicYear a'
                    .' WHERE c.code LIKE :code'
                    .' AND a.isDefault = 1'
                    );
            $query->setParameter('code', '%'.$data["id"].'%');
            $sem = $query->getResult();

           

            return new JsonModel([
                $sem
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }

        
    }
    
    public function academicYrDataMigrationAction()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromQuery(); 
            
            $currentAcadYr = $this->entityManager->getRepository(AcademicYear::class)->find($data["id"]);

            $activeAcadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            $this->activeYear = $activeAcadYr;
            $this->currentYear = $currentAcadYr;
           //Migrating all available semester from the active year to the current year
            //Afeter migrating semester, linking semesters to classes
            
           if($currentAcadYr!=$activeAcadYr)
           {
               $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($activeAcadYr);
               
               foreach($semesters as $sem)
               {
                   $semester = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$currentAcadYr,"code"=>$sem->getCode()));
                   //Check if the semester already exists for the current year
                   $sem1 = $semester;
                   if(!$semester)
                   {
                        $newSem = new Semester();
                        $newSem->setAcademicYear($currentAcadYr);
                        $newSem->setCode($sem->getCode());
                        $newSem->setName($sem->getName());
                        $newSem->setRanking($sem->getRanking());

                        $this->entityManager->persist($newSem);
                        $this->entityManager->flush();
                        
                        $sem1 = $newSem;
                   }
                        
                   
                    $classes =  $this->entityManager->getRepository(ClassOfStudy::class)->findAll();
                    foreach($classes as $classe)
                    { 
                       $semToClasse = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("academicYear"=>$activeAcadYr,"semester"=>$sem,"classOfStudy"=>$classe));
                        
                       if($semToClasse)
                       {
                           //Assocaiate classes with new academic year semesters
                           if(!$this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("academicYear"=>$currentAcadYr,"semester"=>$sem1,"classOfStudy"=>$classe)))
                           {        
                                $newSemToclasse = new SemesterAssociatedToClass();
                                $newSemToclasse->setAcademicYear($currentAcadYr);
                                $newSemToclasse->setSemester($sem1); 
                                $newSemToclasse->setClassOfStudy($classe);
                                $this->entityManager->persist($newSemToclasse);
                                $this->entityManager->flush();

                            }
                            

                            //Migrating subjects from previous academic to the current one
                            $subjectPerClasseAndSemester = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(array("semester"=>$sem,"classOfStudy"=>$classe,"status"=>1));
                            foreach($subjectPerClasseAndSemester as $sub)
                            {   

                                $teachingUnit = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("semester"=>$sem1,"classOfStudy"=>$classe,"teachingUnit"=>$sub->getTeachingUnit(),"status"=>1));
                                $subject = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("semester"=>$sem1,"classOfStudy"=>$classe,"subject"=>$sub->getSubject(),"status"=>1));
                                 if(!$teachingUnit || !$subject)        
                                 {
                                    $subject = new ClassOfStudyHasSemester();
                                    $subject->setClassOfStudy($classe);
                                    $subject->setSemester($sem1);
                                    $subject->setTeachingUnit($sub->getTeachingUnit());
                                    $subject->setSubject($sub->getSubject());
                                    $subject->setCredits($sub->getCredits());
                                    $subject->setHoursVolume($sub->getHoursVolume());
                                    $subject->setSubjectCredits($sub->getSubjectCredits());
                                    $subject->setSubjectHours($sub->getSubjectHours());
                                    $subject->setCmHours($sub->getCmHours());
                                    $subject->setTpHours($sub->getTpHours());
                                    $subject->setTdHours($sub->getTdHours());
                                    $subject->setSubjectCmHours($sub->getSubjectCmHours());
                                    $subject->setSubjectTpHours($sub->getSubjectTpHours());
                                    $subject->setSubjectTdHours($sub->getSubjectTdHours());
                                    $subject->setIsPreviousYearSubject($sub->getIsPreviousYearSubject());
                                    $subject->setStatus($sub->getStatus());

                                    $this->entityManager->persist($subject);
                                    $this->entityManager->flush();
                                 }
                            } 
                            
                        }
                    }
               }
               $classes =  $this->entityManager->getRepository(ClassOfStudy::class)->findAll();
               foreach($classes as $classe)
               { 
                     //Register student to their new classes
                     $students = $this->entityManager->getRepository(AdminRegistration::class)->findBy(array("academicYear"=>$activeAcadYr,"classOfStudy"=>$classe));
                     foreach($students as $std)
                     {
                        //managing only student for which the academic year is marked as COMPLETED
                     
                        if($std->getRegistrationStatus()=="COMPLETED")
                        {
                            $formerClasse = $std->getClassOfStudy();
                            $degree = $std->getClassOfStudy()->getDegree();
                            $newClasse = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("degree"=>$degree,"studyLevel"=>($formerClasse->getStudyLevel()+1),"option"=>$formerClasse->getOption()));
                            
                            //Skip if student is already registered to his new class
                            $stud = $this->entityManager->getRepository(AdminRegistration::class)->findBy(array("academicYear"=>$currentAcadYr,"classOfStudy"=>$newClasse,"student"=>$std->getStudent()));
                            if($stud) continue;
                            
                            if($std->getDecision()=="ADM")
                            {
                                switch($formerClasse->getIsEndDegreeTraining())
                                {
                                    case 1:
                                        //Save student as a graduate student
                                        $status = self::_ETUDIANT_DIPLOME_;
                                        $std->getStudent()->setStatus($status);
                                        break;
                                    case 0: 
                                        $isRepeating = 0;
                                        //register student to the new class
                                        if($newClasse)
                                        { 
                                           $this->registerStudentToClass($std->getStudent(),$formerClasse,$newClasse,$isRepeating);
                                           if($formerClasse->getIsEndCycle()==1)$this->stdSemesterRegistrationNewCycle($formerClasse, $newClasse, $std->getStudent());
                                           else $this->stdSemesterRegistration($formerClasse, $newClasse, $std->getStudent());
                                        }
                                        break;
                                }


                            }
                            elseif($std->getDecision()=="RED") {
                                $isRepeating = 1;
                                $newClasse = $formerClasse;
                               $this->registerStudentToClass($std->getStudent(),$formerClasse,$newClasse,$isRepeating);
                                $this->stdSemesterRegistration($formerClasse, $newClasse, $std->getStudent());
                            }
                            elseif($std->getDecision()=="EXC"){
                                $status = self::_ETUDIANT_EXCLU_;
                                $std->getStudent()->setStatus($status);
                                $std->setStatus($status);
                            }

                           //Reporting all backlogs to the current year
                            $degree = $classe->getDegree();
                            $studyLevel = $classe->getStudyLevel();

                            while($studyLevel>0)
                            {
                               $formerClasse = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("degree"=>$degree,"studyLevel"=>$studyLevel));
                               if($formerClasse)
                               {
                                  $this->reportStudentBacklogsSubjects($std->getStudent(),$formerClasse);
                                   $studyLevel = $formerClasse->getStudyLevel()-1;
                               }else $studyLevel = $studyLevel-1;

                            }

                        }
                    }
                   
               }
  
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                "response"=>true
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
        
    }
    
    private function registerStudentToClass($std,$formerClasse,$newClasse,$isRepeating)
    {
        
        $isRegistered = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("academicYear"=>$this->currentYear,"student"=>$std));
/*        if($isRegistered)
        {
            $isRegistered->setStudent($std);
            $isRegistered->setClassOfStudy($newClasse);            
            $isRegistered->setStatus(0);
            $isRegistered->setIsStudentRepeating($isRepeating);
            $currentDate = date_create(date('Y-m-d H:i:s'));
            $isRegistered->setRegisteringDate($currentDate);
            $isRegistered->setDecision(NULL);
            $isRegistered->setIsStudentRepeating($isRepeating);
            //$this->entityManager->flush();

        }
        else */
            if(!$isRegistered){
            $adminRegistration = new AdminRegistration();
            $adminRegistration->setStudent($std);
            $adminRegistration->setClassOfStudy($newClasse);
            $currentDate = date_create(date('Y-m-d H:i:s'));
            $adminRegistration->setRegisteringDate($currentDate);
            $adminRegistration->setAcademicYear($this->currentYear);
            $adminRegistration->setStatus(0);
            $adminRegistration->setDecision(NULL);
            $adminRegistration->setIsStudentRepeating($isRepeating);
            $this->entityManager->persist($adminRegistration);
           // $this->entityManager->flush();


        }
        $this->entityManager->flush();
        
    }

   // register student to semesters
   private function stdSemesterRegistration($formerClasse,$newClasse,$student)
   { 

        //Getting the second semester of the active year
        $semester= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$formerClasse,"academicYear"=>$this->activeYear));

        foreach($semester as $sem)
        {

           if($sem->getSemester()->getRanking()%2==0)
           {
                $lastSem = $sem->getSemester();
           }
        }

        //Getting the first semester of the current year
        $semester= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$newClasse,"academicYear"=>$this->currentYear));
        foreach($semester as $sem)
        {

           if($sem->getSemester()->getRanking()%2==1)
           {
                $newSem = $sem->getSemester();
           }
        } 

        //Registering student to the first semester of the current year and migrate date from
        //seconsemster of the active year to the first semester of the current year

        //Verify is the student is already registered and update data otherwise, create a new registration
        $stdLastSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$lastSem));
        if ($stdLastSemRegistration )
        { 
            if($newSem)
                $stdNewSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$newSem));

            if($stdNewSemRegistration)
            {
                $stdNewSemRegistration->setMpcPrevious($stdLastSemRegistration->getMpcCurrentSem());
                $stdNewSemRegistration->setNbCredtisCapitalizedPrevious($stdLastSemRegistration->getNbCreditsCapitalizedCurrentSem()+$stdLastSemRegistration->getNbCredtisCapitalizedPrevious());
                $stdNewSemRegistration->setTotalCreditRegisteredPreviousCycle($stdLastSemRegistration->getTotalCreditRegisteredCurrentCycle());
                $stdNewSemRegistration->setTotalCreditsCyclePreviousYear($stdLastSemRegistration->getTotalCreditsCyclePreviousYear()+$this->totalCreditPerSem($formerClasse,$lastSem));
                $stdNewSemRegistration->setCountingSemRegistration(($stdLastSemRegistration->getCountingSemRegistration()+1));               

                //$this->entityManager->flush();

            }
            else
            if(!$stdNewSemRegistration)    
            {
                if($newSem)
                {
                    $stdNewSemRegistration = new StudentSemRegistration();
                    $stdNewSemRegistration->setStudent($student);
                    $stdNewSemRegistration->setSemester($newSem);
                    $stdNewSemRegistration->setMpcPrevious($stdLastSemRegistration->getMpcCurrentSem());
                    $stdNewSemRegistration->setNbCredtisCapitalizedPrevious($stdLastSemRegistration->getNbCreditsCapitalizedCurrentSem()+$stdLastSemRegistration->getNbCredtisCapitalizedPrevious());
                    $stdNewSemRegistration->setTotalCreditRegisteredPreviousCycle($stdLastSemRegistration->getTotalCreditRegisteredCurrentCycle());
                    $stdNewSemRegistration->setTotalCreditsCyclePreviousYear($stdLastSemRegistration->getTotalCreditsCyclePreviousYear()+$this->totalCreditPerSem($formerClasse,$lastSem));
                    $stdNewSemRegistration->setCountingSemRegistration(($stdLastSemRegistration->getCountingSemRegistration()+1));               

                    $this->entityManager->persist($stdNewSemRegistration);
                }
                //$this->entityManager->flush();

            }  
            $this->entityManager->flush();
        }

    }
 
// register student to semesters
   private function stdSemesterRegistrationNewCycle($formerClasse,$newClasse,$student)
   { 
   
        //Getting the second semester of the active year

        $semester= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$formerClasse,"academicYear"=>$this->activeYear));
        foreach($semester as $sem)
        {

           if($sem->getSemester()->getRanking()%2==0)
           {
                $lastSem = $sem->getSemester();
           }
        }

        //Getting the first semester of the current year
        $semester= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$newClasse,"academicYear"=>$this->currentYear));
        foreach($semester as $sem)
        {

           if($sem->getSemester()->getRanking()%2==1)
           {
                $newSem = $sem->getSemester();
           }
        } 

        //Registering student to the first semester of the current year and migrate date from
        //seconsemster of the active year to the first semester of the current year

        //Verify is the student is already registered and update data otherwise, create a new registration
        $stdLastSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$lastSem));
        if ($stdLastSemRegistration )
        { 
            if($newSem)
                $stdNewSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$newSem));
            if($stdNewSemRegistration)
            {
                $stdNewSemRegistration->setMpcPrevious(0);
                $stdNewSemRegistration->setNbCredtisCapitalizedPrevious(0);
                $stdNewSemRegistration->setTotalCreditRegisteredPreviousCycle(0);
                $stdNewSemRegistration->setTotalCreditsCyclePreviousYear(0);
                $stdNewSemRegistration->setCountingSemRegistration(1);               

                //$this->entityManager->flush();

            }
            else
            {
                if($newSem)
                {
                    $stdNewSemRegistration = new StudentSemRegistration();
                    $stdNewSemRegistration->setStudent($student);
                    $stdNewSemRegistration->setSemester($newSem);
                    $stdNewSemRegistration->setMpcPrevious(0);
                    $stdNewSemRegistration->setNbCredtisCapitalizedPrevious(0);
                    $stdNewSemRegistration->setTotalCreditRegisteredPreviousCycle(0);
                    $stdNewSemRegistration->setTotalCreditsCyclePreviousYear(0);
                    $stdNewSemRegistration->setCountingSemRegistration(1);  
                    $this->entityManager->persist($stdNewSemRegistration);
                }
                //$this->entityManager->flush();

            }                
            $this->entityManager->flush();
        }

    }    
   private function reportStudentBacklogsSubjects($student,$classe)
   { 
 
        //Getting the second semester of the active year
        $semesters= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$this->activeYear));
        foreach($semesters as $formerSem)
        {
            //Getting the first semester of the current year
            $semesters_1= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$this->currentYear));
            foreach($semesters_1 as $currentSem)
            {

               if(($formerSem->getSemester()->getRanking()==$currentSem->getSemester()->getRanking()))
               {    
                    $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"semester"=>$formerSem->getSemester()));
                    foreach($unitRegistration as $unitR)
                    { 
                        if($unitR->getGrade()=="F"||$unitR->getGrade()=="E"|| is_null($unitR->getGrade()))
                        { 

                            $unit = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,"teachingUnit"=>$unitR->getTeachingUnit(),"semester"=>$currentSem->getSemester()));
                            if(!$unit)
                            {
                                 $unit = new UnitRegistration();
                                 $unit->setStudent($student);
                                 
                                 $unit->setTeachingUnit($unitR->getTeachingUnit());
                                 $unit->setSemester($currentSem->getSemester());
                                 $unit->setIsRepeated(1);
								 
                                 $this->entityManager->persist($unit);
                                 $this->entityManager->flush(); 
                            }
                            else{
                                 
                                 $unit->setStudent($student);
                                 
                                 $unit->setTeachingUnit($unitR->getTeachingUnit());
                                 $unit->setSemester($currentSem->getSemester());
                                 $unit->setIsRepeated(1);  
                                 $this->entityManager->flush();
                            }
                            
                        }
                    }


               }
            } 
        }
    //$this->entityManager->flush();
        
        //Registering student to the first semester of the current year and migrate date from
        //seconsemster of the active year to the first semester of the current year
        

    }   
    private function totalCreditPerSem($classe,$sem)
    {

        $courses = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(array("classOfStudy"=>$classe,"semester"=>$sem,"isPreviousYearSubject"=>0,"status"=>1));
        $credits=0;
        
        foreach($courses as $course)
        {
            
            if($course->getTeachingUnit())
                $credits+=$course->getCredits();
        }

        return $credits;
    }
        
       

}
