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
     * @throws \DragonJsonServer\Exception
	 */
	public function setEmailaddress($emailaddress)
	{
		if (!(new \Zend\Validator\EmailAddress())->isValid($emailaddress)) {
			throw new \DragonJsonServer\Exception('invalid emailaddress', ['emailaddress' => $emailaddress]);
		}
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
			throw new \DragonJsonServer\Exception('incorrect emailaddress or password');
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
	 * Gibt die Attribute der E-Mail Adressverknüpfung als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'emailaddress_id' => $this->getEmailaddressId(),
			'modified' => $this->getModified(),
			'created' => $this->getCreated(),
			'account_id' => $this->getAccountId(),
			'emailaddress' => $this->getEmailaddress(),
		];
	}
}
