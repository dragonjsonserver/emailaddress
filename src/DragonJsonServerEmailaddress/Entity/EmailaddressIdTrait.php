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
 * Trait für die EmailaddressId mit der Beziehung zu einer E-Mail Adresse
 */
trait EmailaddressIdTrait
{
	/**
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 **/
	protected $emailaddress_id;
	
	/**
	 * Setzt die EmailaddressID der Entity
	 * @param integer $emailaddress_id
	 * @return EmailaddressIdTrait
	 */
	public function setEmailaddressId($emailaddress_id)
	{
		$this->emailaddress_id = $emailaddress_id;
		return $this;
	}
	
	/**
	 * Gibt die EmailaddressID der Entity zurück
	 * @return integer
	 */
	public function getEmailaddressId()
	{
		return $this->emailaddress_id;
	}
}
