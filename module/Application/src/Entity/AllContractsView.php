<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrentYearTeachingUnitView
 *
 * @ORM\Table(name="all_contracts_view")
 * @ORM\Entity
 */
class AllContractsView
{
    /**
    * @var integer
    *
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;
    /**
    * @var integer
    *
    * @ORM\Column(name="coshs", type="string", length=45, nullable=true)
    */
    private $coshs;    
    
    /**
    * @var string
    *
    * @ORM\Column(name="code_ue", type="string", length=45, nullable=true)
    */
    private $codeUe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="degree_code", type="string", length=45, nullable=true)
    */
    private $degreeCode;    
    
    
    /**
    * @var string
    *
    * @ORM\Column(name="nom_ue", type="string", length=255, nullable=true)
    */
    private $nomUe;
    
        /**
    * @var float
    *
    * @ORM\Column(name="credits", type="float",  nullable=true)
    */
    private $credits;
    /**
    * @var integer
    *
    * @ORM\Column(name="total_hrs", type="float",  nullable=true)
    */
    private $totalHrs;  
     
    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", length=255, nullable=true)
    */
    private $classe;
    
   
    
       /**
    * @var integer
    *
    * @ORM\Column(name="study_level", type="integer", nullable=true)
    */
    private $studyLevel;
    
    /**
    * @var string
    *
    * @ORM\Column(name="semester", type="string", nullable=true)
    */
    private $semester;
 
    /**
    * @var integer
    *
    * @ORM\Column(name="semID", type="integer", nullable=true)
    */
    private $semId;
    /**
    * @var integer
    *
    * @ORM\Column(name="sem_ranking", type="integer", nullable=true)
    */
    private $semRanking;
    /**
    * @var integer
    *
    * @ORM\Column(name="teacher", type="integer", nullable=true)
    */
    private $teacher;    
     /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    } 
     /**
     * Get id
     *
     * @return integer
     */
    public function getCoshs()
    {
        return $this->coshs;
    }    
    
     /**
     * Get string
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }
    
     /**
     * Get string
     *
     * @return string
     */
    public function getSemester()
    {
        return $this->semester;
    } 
    
    
     /**
     * Get string
     *
     * @return string
     */
    public function getCodeUe()
    {
        return $this->codeUe;
    }
    
    /**
     * Get string
     *
     * @return string
     */
    public function getNomUe()
    {
        return $this->nomUe;
    }
    
    
     /**
     * Get string
     *
     * @return string
     */
    public function getDegreeCode()
    {
        return $this->degreeCode;
    }    
    
     /**
     * Get float
     *
     * @return float
     */
    public function getCredits()
    {
        return $this->credits;
    }
     /**
     * Get integer
     *
     * @return integer
     */
    public function getTotalHrs()
    {
        return $this->totalHrs;
    } 
    
     /**
     * Get integer
     *
     * @return integer
     */
    public function getSemId()
    {
        return $this->semId;
    }
         /**
     * Get integer
     *
     * @return integer
     */
    public function getStudyLevel()
    {
        return $this->studyLevel;
    }
    
         /**
     * Get integer
     *
     * @return integer
     */
    public function getSemRanking()
    {
        return $this->semRanking;
    } 
    /**
     * Get integer
     *
     * @return integer
     */
    public function getTeacher()
    {
        return $this->teacher;
    }    
}

