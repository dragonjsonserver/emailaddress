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
 * Entityklasse einer E-Mail Adressverknüpfung
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="emailaddresses")
 */
class Emailaddress
{
	use \DragonJsonServerDoctrine\Entity\ModifiedTrait;
	use \DragonJsonServerDoctrine\Entity\CreatedTrait;
	use \DragonJsonServerAccount\Entity\AccountIdTrait;
	
	/** 
	 * @Doctrine\ORM\Mapping\Id 
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 * @Doctrine\ORM\Mapping\GeneratedValue
	 **/
	protected $emailaddress_id;
	
	/** 
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $emailaddress;
	
	/** 
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $passwordcrypt;
	
	/**
	 * Setzt die ID der E-Mail Adressverknüpfung
	 * @param integer $emailaddress_id
	 * @return Emailaddress
	 */
	protected function setEmailaddressId($emailaddress_id)
	{
		$this->emailaddress_id = $emailaddress_id;
		return $this;
	}
	
	/**
	 * Gibt die ID der E-Mail Adressverknüpfung zurück
	 * @return integer
	 */
	public function getEmailaddressId()
	{
		return $this->emailaddress_id;
	}
	
	/**
	 * Setzt die E-Mail Adresse der E-Mail Adressverknüpfung
	 * @param string $emailaddress
	 * @return Emailaddress
	 */
	public function setEmailaddress($emailaddress)
	{
		$this->emailaddress = $emailaddress;
		return $this;
	}
	
	/**
	 * Gibt die E-Mail Adresse der E-Mail Adressverknüpfung zurück
	 * @return integer
	 */
	public function getEmailaddress()
	{
		return $this->emailaddress;
	}
	
	/**
	 * Hasht das Passwort und setzt den Passworthash
	 * @param string $password
	 * @return Emailaddress
	 */
	public function setPassword($password)
	{
		$this->setPasswordcrypt((new \Zend\Crypt\Password\Bcrypt())->create($password));
		return $this;
	}
	
	/**
	 * Setzt den Passworthash der E-Mail Adressverknüpfung
	 * @param string $passwordcrypt
	 * @return Emailaddress
	 */
	protected function setPasswordcrypt($passwordcrypt)
	{
		$this->passwordcrypt = $passwordcrypt;
		return $this;
	}
	
	/**
	 * Verifiziert das Passwort mit dem Passworthash
	 * @param string $password
	 * @return Emailaddress
     * @throws \DragonJsonServer\Exception
	 */
	public function verifyPassword($password)
	{
		if (!(new \Zend\Crypt\Password\Bcrypt())->verify($password, $this->getPasswordcrypt())) {
			throw new \DragonJsonServer\Exception('invalid password');
		}
		return $this;
	}
	
	/**
	 * Gibt den Passworthash Adresse der E-Mail Adressverknüpfung zurück
	 * @return integer
	 */
	protected function getPasswordcrypt()
	{
		return $this->passwordcrypt;
	}
	
	/**
	 * Setzt die Attribute der E-Mail Adressverknüpfung aus dem Array
	 * @param array $array
	 * @return Emailaddress
	 */
	public function fromArray(array $array)
	{
		return $this
			->setEmailaddressId($array['emailaddress_id'])
			->setModifiedTimestamp($array['modified'])
			->setCreatedTimestamp($array['created'])
			->setAccountId($array['account_id'])
			->setEmailaddress($array['emailaddress']);
	}
	
	/**
	 * Gibt die Attribute der E-Mail Adressverknüpfung als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'__className' => __CLASS__,
			'emailaddress_id' => $this->getEmailaddressId(),
			'modified' => $this->getModifiedTimestamp(),
			'created' => $this->getCreatedTimestamp(),
			'account_id' => $this->getAccountId(),
			'emailaddress' => $this->getEmailaddress(),
		];
	}
}
