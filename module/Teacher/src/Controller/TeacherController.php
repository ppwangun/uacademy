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
use Application\Entity\Faculty;
use Application\Entity\Teacher;
use Application\Entity\FileDocument;


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
                $data = $hydrator->extract($teacher);
                $teacher = $data;
                $data["documents"] = $documents;
                
            $query = $this->entityManager->createQuery('SELECT c.id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs  FROM Application\Entity\CurrentYearUesAndSubjectsView c'
                    .' WHERE c.teacher = :teacher');
            $query->setParameter('teacher',$id);

            $subjects_1 = $query->getResult();
            $data["teaching_units"] = $subjects_1;
            
            return new JsonModel([
                $data
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
            $teacher->setName($data['names']);
            $teacher->setBirthDate(new \DateTime($data['birthdate']));
            $teacher->setLivingCountry($data["living_country_id"]);
            $teacher->setLivingCity($data["living_city_id"]);
            $teacher->setNationality($data["country_id"]);
            $teacher->setMaritalStatus($data["marital_status"]);
            $teacher->setPhoneNumber($data["phone"]);
            $teacher->setEmail($data["email"]);
            $teacher->setSpeciality($data["speciality"]);
            $teacher->setCurrentEmployer($data["actual_employer"]);
            $teacher->setHighDegree($data["highest_degree"]); 
            
            $grade =$this->entityManager->getRepository(AcademicRanck::class)->find($data['grade_id']); 
            $teacher->setAcademicRanck($grade);
            $faculty =$this->entityManager->getRepository(Faculty::class)->find($data['requested_establishment_id']);  
            
            $teacher->setFaculty($faculty);
           
            
           

            $this->entityManager->persist($teacher); 
              
            $documents = $data["documents"];

           foreach ($_FILES as $key=>$file)
           {  
            /*   if($key="img_file")
               {
               $filename = $file["name"][0];  
               $destination = $_SERVER['DOCUMENT_ROOT'] .'/paymentsproof/'.$filename;
               move_uploaded_file($file['tmp_name'][0],$destination);
               $pdf->addPDF($destination,'all');
               }*/
           }
        
                



            //$degree =$this->entityManager->getRepository(Degree::class)->findOneById($data['degreeId']);
            
            
            //$classe->setDegree($degree);  
            
            $this->entityManager->flush();
            $message = true;
            $this->entityManager->getConnection()->commit(); exit;
            
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
          
            $rank =$this->entityManager->getRepository(AcademicRanck::class)->findOneById($id);
            $rank->setName($data['name']);
            $rank->setCode($data['code']);
            $rank->setPaymentRate($data['paymentRate']);

            
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
