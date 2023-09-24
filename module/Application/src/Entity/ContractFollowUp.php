<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ContractFollowUp
 *
 * @ORM\Table(name="contract_follow_up", indexes={@ORM\Index(name="fk_contract_follow_up_contract1_idx", columns={"contract_id"}), @ORM\Index(name="fk_contract_follow_up_teacher_payment_bill1_idx", columns={"teacher_payment_bill_id"})})
 * @ORM\Entity
 */
class ContractFollowUp
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var float|null
     *
     * @ORM\Column(name="cm_hours", type="float", precision=10, scale=0, nullable=true)
     */
    private $cmHours;

    /**
     * @var float|null
     *
     * @ORM\Column(name="td_hours", type="float", precision=10, scale=0, nullable=true)
     */
    private $tdHours;

    /**
     * @var float|null
     *
     * @ORM\Column(name="tp_hours", type="float", precision=10, scale=0, nullable=true)
     */
    private $tpHours;

    /**
     * @var float|null
     *
     * @ORM\Column(name="hr_volume", type="float", precision=10, scale=0, nullable=true)
     */
    private $hrVolume;

    /**
     * @var float|null
     *
     * @ORM\Column(name="payment_amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $paymentAmount;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="payment_status", type="boolean", nullable=true)
     */
    private $paymentStatus = '0';

    /**
     * @var \Contract
     *
     * @ORM\ManyToOne(targetEntity="Contract")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     * })
     */
    private $contract;

    /**
     * @var \TeacherPaymentBill
     *
     * @ORM\ManyToOne(targetEntity="TeacherPaymentBill")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_payment_bill_id", referencedColumnName="id")
     * })
     */
    private $teacherPaymentBill;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime|null $date
     *
     * @return ContractFollowUp
     */
    public function setDate($date = null)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set cmHours.
     *
     * @param float|null $cmHours
     *
     * @return ContractFollowUp
     */
    public function setCmHours($cmHours = null)
    {
        $this->cmHours = $cmHours;
    
        return $this;
    }

    /**
     * Get cmHours.
     *
     * @return float|null
     */
    public function getCmHours()
    {
        return $this->cmHours;
    }

    /**
     * Set tdHours.
     *
     * @param float|null $tdHours
     *
     * @return ContractFollowUp
     */
    public function setTdHours($tdHours = null)
    {
        $this->tdHours = $tdHours;
    
        return $this;
    }

    /**
     * Get tdHours.
     *
     * @return float|null
     */
    public function getTdHours()
    {
        return $this->tdHours;
    }

    /**
     * Set tpHours.
     *
     * @param float|null $tpHours
     *
     * @return ContractFollowUp
     */
    public function setTpHours($tpHours = null)
    {
        $this->tpHours = $tpHours;
    
        return $this;
    }

    /**
     * Get tpHours.
     *
     * @return float|null
     */
    public function getTpHours()
    {
        return $this->tpHours;
    }

    /**
     * Set hrVolume.
     *
     * @param float|null $hrVolume
     *
     * @return ContractFollowUp
     */
    public function setHrVolume($hrVolume = null)
    {
        $this->hrVolume = $hrVolume;
    
        return $this;
    }

    /**
     * Get hrVolume.
     *
     * @return float|null
     */
    public function getHrVolume()
    {
        return $this->hrVolume;
    }

    /**
     * Set paymentAmount.
     *
     * @param float|null $paymentAmount
     *
     * @return ContractFollowUp
     */
    public function setPaymentAmount($paymentAmount = null)
    {
        $this->paymentAmount = $paymentAmount;
    
        return $this;
    }

    /**
     * Get paymentAmount.
     *
     * @return float|null
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * Set paymentStatus.
     *
     * @param bool|null $paymentStatus
     *
     * @return ContractFollowUp
     */
    public function setPaymentStatus($paymentStatus = null)
    {
        $this->paymentStatus = $paymentStatus;
    
        return $this;
    }

    /**
     * Get paymentStatus.
     *
     * @return bool|null
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Set contract.
     *
     * @param \Contract|null $contract
     *
     * @return ContractFollowUp
     */
    public function setContract(\Contract $contract = null)
    {
        $this->contract = $contract;
    
        return $this;
    }

    /**
     * Get contract.
     *
     * @return \Contract|null
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set teacherPaymentBill.
     *
     * @param \TeacherPaymentBill|null $teacherPaymentBill
     *
     * @return ContractFollowUp
     */
    public function setTeacherPaymentBill(\TeacherPaymentBill $teacherPaymentBill = null)
    {
        $this->teacherPaymentBill = $teacherPaymentBill;
    
        return $this;
    }

    /**
     * Get teacherPaymentBill.
     *
     * @return \TeacherPaymentBill|null
     */
    public function getTeacherPaymentBill()
    {
        return $this->teacherPaymentBill;
    }
}
