<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2014 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

namespace DragonJsonServerEmailaddress\Entity;

/**
 * Entityklasse einer E-Mail Adressvalidierung Anfrage
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="validationrequests")
 */
class Validationrequest
{
	use \DragonJsonServerDoctrine\Entity\ModifiedTrait;
	use \DragonJsonServerDoctrine\Entity\CreatedTrait;
	use \DragonJsonServerEmailaddress\Entity\EmailaddressIdTrait;
	
	/** 
	 * @Doctrine\ORM\Mapping\Id 
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 * @Doctrine\ORM\Mapping\GeneratedValue
	 **/
	protected $validationrequest_id;
	
	/** 
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $validationrequesthash;
	
	/**
	 * Setzt die ID der E-Mail Adressvalidierung
	 * @param integer $validationrequest_id
	 * @return Validationrequest
	 */
	protected function setValidationrequestId($validationrequest_id)
	{
		$this->validationrequest_id = $validationrequest_id;
		return $this;
	}
	
	/**
	 * Gibt die ID der E-Mail Adressvalidierung zurück
	 * @return integer
	 */
	public function getValidationrequestId()
	{
		return $this->validationrequest_id;
	}
	
	/**
	 * Setzt den Hash der E-Mail Adressvalidierung
	 * @param string $validationrequesthash
	 * @return Validationrequest
	 */
	public function setValidationrequesthash($validationrequesthash)
	{
		$this->validationrequesthash = $validationrequesthash;
		return $this;
	}
	
	/**
	 * Gibt den Hash der E-Mail Adressvalidierung zurück
	 * @return string
	 */
	public function getValidationrequesthash()
	{
		return $this->validationrequesthash;
	}
	
	/**
	 * Setzt die Attribute der E-Mail Adressvalidierung aus dem Array
	 * @param array $array
	 * @return Validationrequest
	 */
	public function fromArray(array $array)
	{
		return $this
			->setValidationrequestId($array['validationrequest_id'])
			->setModifiedTimestamp($array['modified'])
			->setCreatedTimestamp($array['created'])
			->setEmailaddressId($array['emailaddress_id']);
	}
	
	/**
	 * Gibt die Attribute der E-Mail Adressvalidierung als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'__className' => __CLASS__,
			'validationrequest_id' => $this->getValidationrequestId(),
			'modified' => $this->getModifiedTimestamp(),
			'created' => $this->getCreatedTimestamp(),
			'emailaddress_id' => $this->getEmailaddressId(),
		];
	}
}
