<?php
namespace Application\Entity;

use Application\Entity\AdminRegistration;


use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payment", indexes={@ORM\Index(name="fk_payment_installment1_idx", columns={"installment_id"}), @ORM\Index(name="fk_payment_admin_registration1_idx", columns={"admin_registration_id"})})
 * @ORM\Entity
 */
class Payment
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_transaction", type="datetime", nullable=true)
     */
    private $dateTransaction;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=45, nullable=true)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_payment_id", type="string", length=45, nullable=true)
     */
    private $mobilePaymentId;    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="from_balance", type="integer", nullable=true)
     */
    private $fromBalance;    

    /**
     * @var AdminRegistration
     *
     * @ORM\ManyToOne(targetEntity="AdminRegistration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_registration_id", referencedColumnName="id")
     * })
     */
    private $adminRegistration;

    /**
     * @var AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;
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
     * Set dateTransaction
     *
     * @param \DateTime $date
     *
     * @return Payment
     */
    public function setDateTransaction($dateTransaction)
    {
        $this->dateTransaction = $dateTransaction;

        return $this;
    }

    /**
     * Get dateTransaction
     *
     * @return \DateTime
     */
    public function getDateTransaction()
    {
        return $this->dateTransaction;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set fromBalance
     *
     * @param integer $fromBalance
     *
     * @return Payment
     */
    public function setFromBalance($fromBalance)
    {
        $this->fromBalance = $fromBalance;

        return $this;
    }
    /**
     * Set mobilePaymentId
     *
     * @param string $mobilePaymentId
     *
     * @return Payment
     */
    public function setMobilePaymentId($mobilePaymentId)
    {
        $this->mobilePaymentId = $mobilePaymentId;

        return $this;
    }

    /**
     * Get mobilePaymentId
     *
     * @return string
     */
    public function getMobilePaymentId()
    {
        return $this->mobilePaymentId;
    }
    
    
    /**
     * Get fromBalance
     *
     * @return integer
     */
    public function getFromBalance()
    {
        return $this->fromBalance;
    }
    
    /**
     * Set adminRegistration
     *
     * @param AdminRegistration $adminRegistration
     *
     * @return Payment
     */
    public function setAdminRegistration(AdminRegistration $adminRegistration = null)
    {
        $this->adminRegistration = $adminRegistration;

        return $this;
    }

    /**
     * Get adminRegistration
     *
     * @return AdminRegistration
     */
    public function getAdminRegistration()
    {
        return $this->adminRegistration;
    }
    
    /**
     * Set academicYear
     *
     * @param AcademicYear $academicYear
     *
     * @return AdminRegistration
     */
    public function setAcademicYear(AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear
     *
     * @return AcademicYear
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }



}
