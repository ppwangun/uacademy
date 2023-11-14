<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class ProspectiveStudent extends \Application\Entity\ProspectiveStudent implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'nom', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'prenom', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'dateOfBirth', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'bornAt', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'phoneNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'idNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'gender', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'email', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'adresse', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'nationality', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'regionOfOrigin', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'photo', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'handicap', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'religion', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'language', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'maritalStatus', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'workingStatus', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherName', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherProfession', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherPhoneNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherEmail', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherCountry', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherName', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherProfession', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherPhoneNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherEmail', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherCountry', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'lastSchool', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'enteringDegree', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeId', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeExamCenter', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeOption', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeReferenceId', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'studentcol', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeJuryNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeSession', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sportiveInformation', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'culturalInformation', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'associativeInformation', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'itKnowledge', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'status', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorName', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorProfession', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorPhoneNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorEmail', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorCountry', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorCity', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherCity', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherCity'];
        }

        return ['__isInitialized__', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'nom', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'prenom', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'dateOfBirth', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'bornAt', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'phoneNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'idNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'gender', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'email', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'adresse', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'nationality', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'regionOfOrigin', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'photo', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'handicap', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'religion', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'language', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'maritalStatus', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'workingStatus', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherName', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherProfession', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherPhoneNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherEmail', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherCountry', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherName', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherProfession', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherPhoneNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherEmail', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherCountry', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'lastSchool', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'enteringDegree', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeId', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeExamCenter', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeOption', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeReferenceId', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'studentcol', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeJuryNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'degreeSession', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sportiveInformation', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'culturalInformation', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'associativeInformation', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'itKnowledge', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'status', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorName', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorProfession', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorPhoneNumber', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorEmail', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorCountry', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'sponsorCity', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'fatherCity', '' . "\0" . 'Application\\Entity\\ProspectiveStudent' . "\0" . 'motherCity'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (ProspectiveStudent $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setNom($nom = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNom', [$nom]);

        return parent::setNom($nom);
    }

    /**
     * {@inheritDoc}
     */
    public function getNom()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNom', []);

        return parent::getNom();
    }

    /**
     * {@inheritDoc}
     */
    public function setPrenom($prenom = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPrenom', [$prenom]);

        return parent::setPrenom($prenom);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrenom()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPrenom', []);

        return parent::getPrenom();
    }

    /**
     * {@inheritDoc}
     */
    public function setDateOfBirth($dateOfBirth = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateOfBirth', [$dateOfBirth]);

        return parent::setDateOfBirth($dateOfBirth);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateOfBirth()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateOfBirth', []);

        return parent::getDateOfBirth();
    }

    /**
     * {@inheritDoc}
     */
    public function setBornAt($bornAt = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBornAt', [$bornAt]);

        return parent::setBornAt($bornAt);
    }

    /**
     * {@inheritDoc}
     */
    public function getBornAt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBornAt', []);

        return parent::getBornAt();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhoneNumber($phoneNumber = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhoneNumber', [$phoneNumber]);

        return parent::setPhoneNumber($phoneNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhoneNumber()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhoneNumber', []);

        return parent::getPhoneNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setIdNumber($idNumber = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIdNumber', [$idNumber]);

        return parent::setIdNumber($idNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdNumber()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIdNumber', []);

        return parent::getIdNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setGender($gender = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGender', [$gender]);

        return parent::setGender($gender);
    }

    /**
     * {@inheritDoc}
     */
    public function getGender()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGender', []);

        return parent::getGender();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', [$email]);

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', []);

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setAdresse($adresse = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAdresse', [$adresse]);

        return parent::setAdresse($adresse);
    }

    /**
     * {@inheritDoc}
     */
    public function getAdresse()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAdresse', []);

        return parent::getAdresse();
    }

    /**
     * {@inheritDoc}
     */
    public function setNationality($nationality = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNationality', [$nationality]);

        return parent::setNationality($nationality);
    }

    /**
     * {@inheritDoc}
     */
    public function getNationality()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNationality', []);

        return parent::getNationality();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegionOfOrigin($regionOfOrigin = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegionOfOrigin', [$regionOfOrigin]);

        return parent::setRegionOfOrigin($regionOfOrigin);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegionOfOrigin()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegionOfOrigin', []);

        return parent::getRegionOfOrigin();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhoto($photo = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhoto', [$photo]);

        return parent::setPhoto($photo);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhoto()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhoto', []);

        return parent::getPhoto();
    }

    /**
     * {@inheritDoc}
     */
    public function setHandicap($handicap = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHandicap', [$handicap]);

        return parent::setHandicap($handicap);
    }

    /**
     * {@inheritDoc}
     */
    public function getHandicap()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHandicap', []);

        return parent::getHandicap();
    }

    /**
     * {@inheritDoc}
     */
    public function setReligion($religion = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReligion', [$religion]);

        return parent::setReligion($religion);
    }

    /**
     * {@inheritDoc}
     */
    public function getReligion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReligion', []);

        return parent::getReligion();
    }

    /**
     * {@inheritDoc}
     */
    public function setLanguage($language = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLanguage', [$language]);

        return parent::setLanguage($language);
    }

    /**
     * {@inheritDoc}
     */
    public function getLanguage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLanguage', []);

        return parent::getLanguage();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaritalStatus($maritalStatus = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaritalStatus', [$maritalStatus]);

        return parent::setMaritalStatus($maritalStatus);
    }

    /**
     * {@inheritDoc}
     */
    public function getMaritalStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaritalStatus', []);

        return parent::getMaritalStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setWorkingStatus($workingStatus = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWorkingStatus', [$workingStatus]);

        return parent::setWorkingStatus($workingStatus);
    }

    /**
     * {@inheritDoc}
     */
    public function getWorkingStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWorkingStatus', []);

        return parent::getWorkingStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setFatherName($fatherName = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFatherName', [$fatherName]);

        return parent::setFatherName($fatherName);
    }

    /**
     * {@inheritDoc}
     */
    public function getFatherName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFatherName', []);

        return parent::getFatherName();
    }

    /**
     * {@inheritDoc}
     */
    public function setFatherProfession($fatherProfession = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFatherProfession', [$fatherProfession]);

        return parent::setFatherProfession($fatherProfession);
    }

    /**
     * {@inheritDoc}
     */
    public function getFatherProfession()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFatherProfession', []);

        return parent::getFatherProfession();
    }

    /**
     * {@inheritDoc}
     */
    public function setFatherPhoneNumber($fatherPhoneNumber = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFatherPhoneNumber', [$fatherPhoneNumber]);

        return parent::setFatherPhoneNumber($fatherPhoneNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getFatherPhoneNumber()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFatherPhoneNumber', []);

        return parent::getFatherPhoneNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setFatherEmail($fatherEmail = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFatherEmail', [$fatherEmail]);

        return parent::setFatherEmail($fatherEmail);
    }

    /**
     * {@inheritDoc}
     */
    public function getFatherEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFatherEmail', []);

        return parent::getFatherEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setFatherCountry($fatherCountry = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFatherCountry', [$fatherCountry]);

        return parent::setFatherCountry($fatherCountry);
    }

    /**
     * {@inheritDoc}
     */
    public function getFatherCountry()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFatherCountry', []);

        return parent::getFatherCountry();
    }

    /**
     * {@inheritDoc}
     */
    public function setMotherName($motherName = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMotherName', [$motherName]);

        return parent::setMotherName($motherName);
    }

    /**
     * {@inheritDoc}
     */
    public function getMotherName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMotherName', []);

        return parent::getMotherName();
    }

    /**
     * {@inheritDoc}
     */
    public function setMotherProfession($motherProfession = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMotherProfession', [$motherProfession]);

        return parent::setMotherProfession($motherProfession);
    }

    /**
     * {@inheritDoc}
     */
    public function getMotherProfession()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMotherProfession', []);

        return parent::getMotherProfession();
    }

    /**
     * {@inheritDoc}
     */
    public function setMotherPhoneNumber($motherPhoneNumber = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMotherPhoneNumber', [$motherPhoneNumber]);

        return parent::setMotherPhoneNumber($motherPhoneNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getMotherPhoneNumber()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMotherPhoneNumber', []);

        return parent::getMotherPhoneNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setMotherEmail($motherEmail = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMotherEmail', [$motherEmail]);

        return parent::setMotherEmail($motherEmail);
    }

    /**
     * {@inheritDoc}
     */
    public function getMotherEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMotherEmail', []);

        return parent::getMotherEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setMotherCountry($motherCountry = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMotherCountry', [$motherCountry]);

        return parent::setMotherCountry($motherCountry);
    }

    /**
     * {@inheritDoc}
     */
    public function getMotherCountry()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMotherCountry', []);

        return parent::getMotherCountry();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastSchool($lastSchool = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastSchool', [$lastSchool]);

        return parent::setLastSchool($lastSchool);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastSchool()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastSchool', []);

        return parent::getLastSchool();
    }

    /**
     * {@inheritDoc}
     */
    public function setEnteringDegree($enteringDegree = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEnteringDegree', [$enteringDegree]);

        return parent::setEnteringDegree($enteringDegree);
    }

    /**
     * {@inheritDoc}
     */
    public function getEnteringDegree()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEnteringDegree', []);

        return parent::getEnteringDegree();
    }

    /**
     * {@inheritDoc}
     */
    public function setDegreeId($degreeId = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDegreeId', [$degreeId]);

        return parent::setDegreeId($degreeId);
    }

    /**
     * {@inheritDoc}
     */
    public function getDegreeId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDegreeId', []);

        return parent::getDegreeId();
    }

    /**
     * {@inheritDoc}
     */
    public function setDegreeExamCenter($degreeExamCenter = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDegreeExamCenter', [$degreeExamCenter]);

        return parent::setDegreeExamCenter($degreeExamCenter);
    }

    /**
     * {@inheritDoc}
     */
    public function getDegreeExamCenter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDegreeExamCenter', []);

        return parent::getDegreeExamCenter();
    }

    /**
     * {@inheritDoc}
     */
    public function setDegreeOption($degreeOption = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDegreeOption', [$degreeOption]);

        return parent::setDegreeOption($degreeOption);
    }

    /**
     * {@inheritDoc}
     */
    public function getDegreeOption()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDegreeOption', []);

        return parent::getDegreeOption();
    }

    /**
     * {@inheritDoc}
     */
    public function setDegreeReferenceId($degreeReferenceId = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDegreeReferenceId', [$degreeReferenceId]);

        return parent::setDegreeReferenceId($degreeReferenceId);
    }

    /**
     * {@inheritDoc}
     */
    public function getDegreeReferenceId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDegreeReferenceId', []);

        return parent::getDegreeReferenceId();
    }

    /**
     * {@inheritDoc}
     */
    public function setStudentcol($studentcol = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStudentcol', [$studentcol]);

        return parent::setStudentcol($studentcol);
    }

    /**
     * {@inheritDoc}
     */
    public function getStudentcol()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStudentcol', []);

        return parent::getStudentcol();
    }

    /**
     * {@inheritDoc}
     */
    public function setDegreeJuryNumber($degreeJuryNumber = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDegreeJuryNumber', [$degreeJuryNumber]);

        return parent::setDegreeJuryNumber($degreeJuryNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getDegreeJuryNumber()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDegreeJuryNumber', []);

        return parent::getDegreeJuryNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setDegreeSession($degreeSession = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDegreeSession', [$degreeSession]);

        return parent::setDegreeSession($degreeSession);
    }

    /**
     * {@inheritDoc}
     */
    public function getDegreeSession()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDegreeSession', []);

        return parent::getDegreeSession();
    }

    /**
     * {@inheritDoc}
     */
    public function setSportiveInformation($sportiveInformation = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSportiveInformation', [$sportiveInformation]);

        return parent::setSportiveInformation($sportiveInformation);
    }

    /**
     * {@inheritDoc}
     */
    public function getSportiveInformation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSportiveInformation', []);

        return parent::getSportiveInformation();
    }

    /**
     * {@inheritDoc}
     */
    public function setCulturalInformation($culturalInformation = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCulturalInformation', [$culturalInformation]);

        return parent::setCulturalInformation($culturalInformation);
    }

    /**
     * {@inheritDoc}
     */
    public function getCulturalInformation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCulturalInformation', []);

        return parent::getCulturalInformation();
    }

    /**
     * {@inheritDoc}
     */
    public function setAssociativeInformation($associativeInformation = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAssociativeInformation', [$associativeInformation]);

        return parent::setAssociativeInformation($associativeInformation);
    }

    /**
     * {@inheritDoc}
     */
    public function getAssociativeInformation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAssociativeInformation', []);

        return parent::getAssociativeInformation();
    }

    /**
     * {@inheritDoc}
     */
    public function setItKnowledge($itKnowledge = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setItKnowledge', [$itKnowledge]);

        return parent::setItKnowledge($itKnowledge);
    }

    /**
     * {@inheritDoc}
     */
    public function getItKnowledge()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getItKnowledge', []);

        return parent::getItKnowledge();
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', [$status]);

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', []);

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setSponsorName($sponsorName = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSponsorName', [$sponsorName]);

        return parent::setSponsorName($sponsorName);
    }

    /**
     * {@inheritDoc}
     */
    public function getSponsorName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSponsorName', []);

        return parent::getSponsorName();
    }

    /**
     * {@inheritDoc}
     */
    public function setSponsorProfession($sponsorProfession = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSponsorProfession', [$sponsorProfession]);

        return parent::setSponsorProfession($sponsorProfession);
    }

    /**
     * {@inheritDoc}
     */
    public function getSponsorProfession()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSponsorProfession', []);

        return parent::getSponsorProfession();
    }

    /**
     * {@inheritDoc}
     */
    public function setSponsorPhoneNumber($sponsorPhoneNumber = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSponsorPhoneNumber', [$sponsorPhoneNumber]);

        return parent::setSponsorPhoneNumber($sponsorPhoneNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getSponsorPhoneNumber()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSponsorPhoneNumber', []);

        return parent::getSponsorPhoneNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setSponsorEmail($sponsorEmail = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSponsorEmail', [$sponsorEmail]);

        return parent::setSponsorEmail($sponsorEmail);
    }

    /**
     * {@inheritDoc}
     */
    public function getSponsorEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSponsorEmail', []);

        return parent::getSponsorEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setSponsorCountry($sponsorCountry = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSponsorCountry', [$sponsorCountry]);

        return parent::setSponsorCountry($sponsorCountry);
    }

    /**
     * {@inheritDoc}
     */
    public function getSponsorCountry()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSponsorCountry', []);

        return parent::getSponsorCountry();
    }

    /**
     * {@inheritDoc}
     */
    public function setSponsorCity($sponsorCity = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSponsorCity', [$sponsorCity]);

        return parent::setSponsorCity($sponsorCity);
    }

    /**
     * {@inheritDoc}
     */
    public function getSponsorCity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSponsorCity', []);

        return parent::getSponsorCity();
    }

    /**
     * {@inheritDoc}
     */
    public function setFatherCity($fatherCity = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFatherCity', [$fatherCity]);

        return parent::setFatherCity($fatherCity);
    }

    /**
     * {@inheritDoc}
     */
    public function getFatherCity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFatherCity', []);

        return parent::getFatherCity();
    }

    /**
     * {@inheritDoc}
     */
    public function setMotherCity($motherCity = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMotherCity', [$motherCity]);

        return parent::setMotherCity($motherCity);
    }

    /**
     * {@inheritDoc}
     */
    public function getMotherCity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMotherCity', []);

        return parent::getMotherCity();
    }

}
