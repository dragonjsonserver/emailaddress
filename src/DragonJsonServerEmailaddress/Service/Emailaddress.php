<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

namespace DragonJsonServerEmailaddress\Service;

/**
 * Serviceklasse zur Verwaltung einer E-Mail Adressverknüpfung
 */
class Emailaddress
{
    use \DragonJsonServer\ServiceManagerTrait;
	use \DragonJsonServer\EventManagerTrait;
	use \DragonJsonServerDoctrine\EntityManagerTrait;
	
    /**
	 * Erstellt eine neue E-Mail Adressverknüpfung für den Account
	 * @param \DragonJsonServerAccount\Entity\Account $account
	 * @param string $emailaddress
	 * @param string $password
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress
	 */
	public function linkAccount(\DragonJsonServerAccount\Entity\Account $account, $emailaddress, $password)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = (new \DragonJsonServerEmailaddress\Entity\Emailaddress())
			->setAccountId($account->getAccountId())
			->setEmailaddress($emailaddress)
			->setPassword($password);
		$entityManager->persist($emailaddress);
		$entityManager->flush();
		$this->getEventManager()->trigger(
			(new \DragonJsonServerEmailaddress\Event\LinkAccount())
				->setTarget($this)
				->setAccount($account)
				->setEmailaddress($emailaddress)
		);
		return $emailaddress;
	}
	
    /**
	 * Entfernt die E-Mail Adressverknüpfung für den Account
	 * @param \DragonJsonServerAccount\Entity\Account $account
     * @throws \DragonJsonServer\Exception
     * @return Emailaddress
	 */
	public function unlinkAccount(\DragonJsonServerAccount\Entity\Account $account)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $entityManager->getRepository('\DragonJsonServerEmailaddress\Entity\Emailaddress')
								      ->findOneBy(['account_id' => $account->getAccountId()]);
		if (null === $emailaddress) {
			throw new \DragonJsonServer\Exception('no emailaddress found');
		}
		$this->getEventManager()->trigger(
			(new \DragonJsonServerEmailaddress\Event\UnlinkAccount())
				->setTarget($this)
				->setAccount($account)
				->setEmailaddress($emailaddress)
		);
		$entityManager->remove($emailaddress);
		$entityManager->flush();
		return $this;
	}
	
	/**
	 * Gibt die E-Mail Adresse zur übergebenen AccountID zurück
	 * @param integer $account_id
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress
     * @throws \DragonJsonServer\Exception
	 */
	public function getEmailaddressByAccountId($account_id)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Emailaddress')
		    ->findOneBy(['account_id' => $account_id]);
		if (null === $emailaddress) {
			throw new \DragonJsonServer\Exception('incorrect account_id');
		}
		return $emailaddress;
	}
	
	/**
	 * Gibt die E-Mail Adresse zur übergebenen EmailaddressID zurück
	 * @param integer $emailaddress_id
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress
     * @throws \DragonJsonServer\Exception
	 */
	public function getEmailaddressByEmailaddressId($emailaddress_id)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $entityManager->find('\DragonJsonServerEmailaddress\Entity\Emailaddress', $emailaddress_id);
		if (null === $emailaddress) {
			throw new \DragonJsonServer\Exception('incorrect emailaddress_id', ['emailaddress_id' => $emailaddress_id]);
		}
		return $emailaddress;
	}
	
	/**
	 * Gibt die E-Mail Adresse der übergebenen E-Mail Adresse zurück
	 * @param string $emailaddress
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress
     * @throws \DragonJsonServer\Exception
	 */
	public function getEmailaddressByEmailaddress($emailaddress)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Emailaddress')
		    ->findOneBy(['emailaddress' => $emailaddress]);
		if (null === $emailaddress) {
			throw new \DragonJsonServer\Exception('incorrect emailaddress');
		}
		return $emailaddress;
	}
	
	/**
	 * Gibt die E-Mail Adresse der übergebenen E-Mail Adressverknüpfung zurück
	 * @param string $emailaddress
	 * @param string $password
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress
     * @throws \DragonJsonServer\Exception
	 */
	public function getEmailaddressByEmailaddressAndPassword($emailaddress, $password)
	{
		$emailaddress = $this->getEmailaddressByEmailaddress($emailaddress);
		$emailaddress->verifyPassword($password);
		$this->getEventManager()->trigger(
			(new \DragonJsonServerEmailaddress\Event\Login())
				->setTarget($this)
				->setEmailaddress($emailaddress)
		);
		return $emailaddress;
	}
	
	/**
	 * Ändert die E-Mail Adresse der E-Mail Adressverknüpfung
	 * @param integer $account_id
	 * @param string $newemailaddress
	 * @return Emailaddress
	 */
	public function changeEmailaddress($account_id, $newemailaddress)
	{
		$entityManager = $this->getEntityManager();
		
		$emailaddress = $this->getEmailaddressByAccountId($account_id);
		$emailaddress->setEmailaddress($newemailaddress);
		$entityManager->persist($emailaddress);
		$entityManager->flush();
		return $this;
	}
	
	/**
	 * Sendet eine E-Mail mit dem Hash zum Zurücksetzen des Passwortes
	 * @param string $emailaddress
	 * @param array $configEmailaddress
	 */
	public function requestPassword($emailaddress, array $configEmailaddress)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $this->getEmailaddressByEmailaddress($emailaddress);
		$passwordrequesthash = md5($emailaddress->getEmailaddressId() . microtime(true));
		$entityManager->persist((new \DragonJsonServerEmailaddress\Entity\Passwordrequest())
			->setEmailaddressId($emailaddress->getEmailaddressId())
			->setPasswordrequesthash($passwordrequesthash));
		$entityManager->flush();
		$message = new \Zend\Mail\Message();
		$message->addTo($emailaddress->getEmailaddress())
		        ->addFrom($configEmailaddress['from'])
		        ->setSubject($configEmailaddress['passwordrequest']['subject'])
		        ->setBody(str_replace(
		        	'%passwordrequesthash%', 
		        	$passwordrequesthash, 
		        	$configEmailaddress['passwordrequest']['body']
		        ));
		$transport = new \Zend\Mail\Transport\Sendmail();
		$transport->send($message);
	}
	
	/**
	 * Setzt das Passwort des übergebenen Hashes
	 * @param string $passwordrequesthash
	 * @param string $newpassword
	 * @throws \DragonJsonServer\Exception
	 */
	public function resetPassword($passwordrequesthash, $newpassword)
	{
		$entityManager = $this->getEntityManager();

		$passwordrequest = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Passwordrequest')
			->findOneBy(['passwordrequesthash' => $passwordrequesthash]);
		if (null === $passwordrequest) {
			throw new \DragonJsonServer\Exception('incorrect passwordrequesthash');
		}
		$emailaddress = $this->getEmailaddressByEmailaddressId($passwordrequest->getEmailaddressId());
		$this->changePassword($emailaddress, $newpassword);
		$entityManager->remove($passwordrequest);
		$entityManager->flush();
	}
	
	/**
	 * Ändert das Passwort der E-Mail Adressverknüpfung
	 * @param \DragonJsonServerEmailaddress\Entity\Emailaddress $emailaddress
	 * @param string $newpassword
	 * @return Emailaddress
	 */
	public function changePassword(\DragonJsonServerEmailaddress\Entity\Emailaddress $emailaddress, $newpassword)
	{
		$entityManager = $this->getEntityManager();
		
		$emailaddress->setPassword($newpassword);
		$entityManager->persist($emailaddress);
		$entityManager->flush();
		return $this;
	}
}
