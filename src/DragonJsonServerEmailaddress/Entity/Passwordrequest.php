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
 * Entityklasse einer Passwort vergessen Anfrage
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="passwordrequests")
 */
class Passwordrequest
{
	use \DragonJsonServerDoctrine\Entity\CreatedTrait;
	use \DragonJsonServerEmailaddress\Entity\EmailaddressIdTrait;
	
	/** 
	 * @Doctrine\ORM\Mapping\Id 
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 * @Doctrine\ORM\Mapping\GeneratedValue
	 **/
	protected $passwordrequest_id;
	
	/** 
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $passwordrequesthash;
	
	/**
	 * Gibt die ID der Passwort vergessen Anfrage zurück
	 * @return integer
	 */
	public function getPasswordrequestId()
	{
		return $this->passwordrequest_id;
	}
	
	/**
	 * Setzt den Hash der Passwort vergessen Anfrage
	 * @param string $passwordrequesthash
	 * @return Passwordrequest
	 */
	public function setPasswordrequesthash($passwordrequesthash)
	{
		$this->passwordrequesthash = $passwordrequesthash;
		return $this;
	}
	
	/**
	 * Gibt den Hash der Passwort vergessen Anfrage zurück
	 * @return string
	 */
	public function getPasswordrequesthash()
	{
		return $this->passwordrequesthash;
	}
	
	/**
	 * Gibt die Attribute der Passwort vergessen Anfrage als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'passwordrequest_id' => $this->getPasswordrequestId(),
			'created' => $this->getCreatedTimestamp(),
			'emailaddress_id' => $this->getEmailaddressId(),
		];
	}
}
