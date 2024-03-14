<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Teacher\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Application\Entity\AcademicRanck;
use Application\Entity\Countries;
use Application\Entity\Cities;
use Application\Entity\Faculty;
use Application\Entity\Teacher;
use Application\Entity\Contract;
use Application\Entity\FileDocument;
use Application\Entity\AllContractsView;


class TeacherController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager;  
        $this->sessionContainer = $sessionContainer;
    }
    
    
 
    
    public function get($id)
    {        
        
        if(is_numeric($id))
        {
         
            $teacher = $this->entityManager->getRepository(Teacher::class)->find($id);
            $documents = $this->entityManager->getRepository(FileDocument::class)->findOneByTeacher($id);
            if($documents)
                foreach($documents as $key=>$value)
                {

                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $documents[$key] = $data;
                }   
            else $documents = [];

                $hydrator = new ReflectionHydrator();
                $academic_rank_id = $teacher->getAcademicRanck()->getId();
                $requested_establishment_id = $teacher->getFaculty()->getId();
                $data = $hydrator->extract($teacher);
                $teacher = $data;
                $teacher["names"]=$data["name"];
              
                $country = $this->entityManager->getRepository(Countries::class)->findOneByName($data["livingCountry"]);
                $nationality = $this->entityManager->getRepository(Countries::class)->findOneByName($data["nationality"]);
                $city = $this->entityManager->getRepository(Cities::class)->findOneByName($data["livingCity"]);
                if($country)
                    $teacher["living_country"]=$hydrator->extract($country);
                if($city)
                $teacher["living_city"]=$hydrator->extract($city);
                if($nationality)
                $teacher["nationality"]= $nationality->getName() ; 
                $teacher["marital_status"]= $data["maritalStatus"] ;
                $teacher["phone"]= $data["phoneNumber"] ; 
                $teacher["highest_degree"]= $data["highDegree"] ;
                $teacher["grade_id"]= $academic_rank_id ;
                $teacher["actual_employer"]= $teacher["currentEmployer"] ;
                $teacher["requested_establishment_id"] = $requested_establishment_id;
              
                if($data["birthDate"])
                    $teacher["birthdate"]=$data["birthDate"]->format('Y-m-d');
                $teacher["documents"] = $documents;
                //$query = $this->entityManager->createQuery('SELECT c.coshs as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,con.volumeHrs  as totalHrs  FROM Application\Entity\ClassOfStudyHasSemster c'
                $query = $this->entityManager->createQuery('SELECT c.id as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs,c.teacher   FROM Application\Entity\AllContractsView c '
                        .'WHERE c.teacher = :teacher' );
                $query->setParameter('teacher',$id);

                $subjects_1 = $query->getResult();
                $teacher["teaching_units"] = $subjects_1;
            return new JsonModel([
                "date"=>$data["birthDate"]->format('Y-m-d'),
                $teacher
            ]);
        }


        
        //return $faculties;
    }
    public function getList()
    {       
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
        $teachers = $this->entityManager->getRepository(Teacher::class)->findAll();
                
            foreach($teachers as $key=>$value)
            {
                
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $teachers[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $teachers  
                
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }

    }    
    public function getFaculty($school)
    {
        $faculties = $this->entityManager->getRepository(Faculty::class)->findBySchool($school);
        foreach($faculties as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $faculties[$key] = $data;
        }
        return $faculties;
        
    }
    
    public function create($data)
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            
            $message = false;

            $teacher= new Teacher();
            $teacher->setCivility($data['civility']);
            $teacher->setName($data['names']);
            $teacher->setBirthDate(new \DateTime($data['birthdate']));
            $teacher->setLivingCountry($data["living_country"]["name"]);
            $teacher->setLivingCity($data["living_city"]["name"]);
            $teacher->setNationality($data["nationality"]);
            $teacher->setMaritalStatus($data["marital_status"]);
            $teacher->setPhoneNumber($data["phone"]);
            $teacher->setContactInEmergency($data["contactInEmergency"]);
            $teacher->setEmail($data["email"]);
            $teacher->setSpeciality($data["speciality"]);
            $teacher->setCurrentEmployer($data["actual_employer"]);
            $teacher->setHighDegree($data["highest_degree"]); 
            $teacher->setType($data["type"]);
            
            $grade =$this->entityManager->getRepository(AcademicRanck::class)->find($data['grade_id']); 
            $teacher->setAcademicRanck($grade);
            $faculty =$this->entityManager->getRepository(Faculty::class)->find($data['requested_establishment_id']);  
            
            $teacher->setFaculty($faculty);
           
            
           

            $this->entityManager->persist($teacher);
            if(isset($data["documents"]))
            {
       
                $documents = $data["documents"];
                //var_dump($data);
                
                foreach($documents as $key=>$value)
                {
                  if($value["type"] == "identity_document") 
                  {
                      $filename = $_FILES["documents"]["name"][$key]["file"];
                      $file = $_FILES["documents"]["tmp_name"][$key]["file"];
             
                      $destination = $_SERVER['DOCUMENT_ROOT'] .'/teacherDocs/'.$filename;
                      move_uploaded_file($file,$destination);
                      
                     
                  }
                }
             

               foreach ($_FILES["documents"]["name"] as $key=>$file)
               {  //echo $key." YES MAN ".$file["file"];
                /*   if($key="img_file")
                   {
                   $filename = $file["name"][0];  
                   $destination = $_SERVER['DOCUMENT_ROOT'] .'/paymentsproof/'.$filename;
                   move_uploaded_file($file['tmp_name'][0],$destination);
                   $pdf->addPDF($destination,'all');
                   }*/
               }
            }
           

 



            //$degree =$this->entityManager->getRepository(Degree::class)->findOneById($data['degreeId']);
            
            
            //$classe->setDegree($degree);  
            
            $this->entityManager->flush();
            $message = true;
            $this->entityManager->getConnection()->commit(); 
            
            return new JsonModel([
                 $message
            ]);  
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
        
        
        
    }
  
    public function delete($id)
    {

        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $rank = $this->entityManager->getRepository(AcademicRanck::class)->findOneById($id);
            if($rank )
            {
                
                $this->entityManager->remove($rank );
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
            }


        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;    
        }
        
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);
    }
    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try{
            $data = $data["data"];
          
            $teacher =$this->entityManager->getRepository(Teacher::class)->findOneById($id);
            $teacher->setCivility($data['civility']);
            $teacher->setName($data['names']);
            $teacher->setBirthDate(new \DateTime($data['birthdate']));
            $teacher->setLivingCountry($data["living_country"]["name"]);
            $teacher->setLivingCity($data["living_city"]["name"]);
            $teacher->setNationality($data["nationality"]);
            $teacher->setMaritalStatus($data["marital_status"]);
            $teacher->setPhoneNumber($data["phone"]);
            $teacher->setContactInEmergency($data["contactInEmergency"]);
            $teacher->setEmail($data["email"]);
            $teacher->setSpeciality($data["speciality"]);
            $teacher->setCurrentEmployer($data["actual_employer"]);
            $teacher->setHighDegree($data["highest_degree"]); 
            $teacher->setType($data["type"]);
            
            $grade =$this->entityManager->getRepository(AcademicRanck::class)->find($data['grade_id']); 
            $teacher->setAcademicRanck($grade);
            $faculty =$this->entityManager->getRepository(Faculty::class)->find($data['requested_establishment_id']);  
            
            $teacher->setFaculty($faculty);

            
            $this->entityManager->flush();
            
            $this->entityManager->getConnection()->commit();
            
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);

        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }
}
