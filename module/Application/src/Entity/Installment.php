<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Installment
 *
 * @ORM\Table(name="installment", indexes={@ORM\Index(name="fk_installment_fees1_idx", columns={"fees_id"})})
 * @ORM\Entity
 */
class Installment
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    private $deadline;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var \Fees
     *
     * @ORM\ManyToOne(targetEntity="Fees")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fees_id", referencedColumnName="id")
     * })
     */
    private $fees;



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
     * Set amount
     *
     * @param float $amount
     *
     * @return Installment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Installment
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Installment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fees
     *
     * @param \Fees $fees
     *
     * @return Installment
     */
    public function setFees(\Fees $fees = null)
    {
        $this->fees = $fees;

        return $this;
    }

    /**
     * Get fees
     *
     * @return \Fees
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * Set feesId
     *
     * @param integer $feesId
     *
     * @return Installment
     */
    public function setFeesId($feesId)
    {
        $this->feesId = $feesId;

        return $this;
    }

    /**
     * Get feesId
     *
     * @return integer
     */
    public function getFeesId()
    {
        return $this->feesId;
    }

    /**
     * Set classOfStudy
     *
     * @param \ClassOfStudy $classOfStudy
     *
     * @return Installment
     */
    public function setClassOfStudy(\ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy
     *
     * @return \ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set degree
     *
     * @param \Degree $degree
     *
     * @return Installment
     */
    public function setDegree(\Degree $degree = null)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return \Degree
     */
    public function getDegree()
    {
        return $this->degree;
    }
}
