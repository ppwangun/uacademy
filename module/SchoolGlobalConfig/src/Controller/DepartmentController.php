<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\Reflection as ReflectionHydrator;
use Application\Entity\FieldOfStudy;
use Application\Entity\Faculty;

class DepartmentController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($id)
    {
        $faculty= $this->entityManager->getRepository(Filiere::class)->findOneById($id);

            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($faculty);

        return new JsonModel([
                $data
        ]);
        
        //return $faculties;
    }
    public function getList()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $filieres = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($filieres as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $filieres[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();

            return new JsonModel([
                    $filieres
            ]);
            }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
        //return $faculties;
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
            
            $filiere= new FieldOfStudy();
            $filiere->setName($data['name']);
            $filiere->setCode($data['code']);
 
            $faculty = $this->entityManager->getRepository(Faculty::class)->findOneById($data['fac_id']);
     
            $filiere->setFaculty($faculty);
            $this->entityManager->persist($filiere);
            $this->entityManager->flush();
            
            $filieres = $this->getList();
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                $filieres
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
            $filiere = $this->entityManager->getRepository(FieldOfStudy::class)->findOneById($id);
            if($filiere)
            {
                
                $this->entityManager->remove($filiere);
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
}
