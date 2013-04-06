<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

namespace DragonJsonServerEmailaddress\Entity;

/**
 * Entityklasse einer E-Mail Adressvalidierung Anfrage
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="emailaddressvalidations")
 */
class Emailaddressvalidation
{
	use \DragonJsonServerDoctrine\Entity\ModifiedTrait;
	use \DragonJsonServerDoctrine\Entity\CreatedTrait;
	use \DragonJsonServerEmailaddress\Entity\EmailaddressIdTrait;
	
	/** 
	 * @Doctrine\ORM\Mapping\Id 
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 * @Doctrine\ORM\Mapping\GeneratedValue
	 **/
	protected $emailaddressvalidation_id;
	
	/** 
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $emailaddressvalidationhash;
	
	/**
	 * Gibt die ID der E-Mail Adressvalidierung zurück
	 * @return integer
	 */
	public function getEmailaddressvalidationId()
	{
		return $this->emailaddressvalidation_id;
	}
	
	/**
	 * Setzt den Hash der E-Mail Adressvalidierung
	 * @param string $emailaddressvalidationhash
	 * @return Emailaddressvalidation
	 */
	public function setEmailaddressvalidationhash($emailaddressvalidationhash)
	{
		$this->emailaddressvalidationhash = $emailaddressvalidationhash;
		return $this;
	}
	
	/**
	 * Gibt den Hash der E-Mail Adressvalidierung zurück
	 * @return string
	 */
	public function getEmailaddressvalidationhash()
	{
		return $this->emailaddressvalidationhash;
	}
	
	/**
	 * Gibt die Attribute der E-Mail Adressvalidierung als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'emailaddressvalidation_id' => $this->getEmailaddressvalidationId(),
			'modified' => $this->getModifiedTimestamp(),
			'created' => $this->getCreatedTimestamp(),
			'emailaddress_id' => $this->getEmailaddressId(),
		];
	}
}
