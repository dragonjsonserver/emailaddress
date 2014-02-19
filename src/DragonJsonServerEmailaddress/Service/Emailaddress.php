<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2014 DragonProjects (http://dragonprojects.de/)
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
	 * Validiert die übergebene E-Mail Adresse
	 * @param string $emailaddress
	 * @return Emailaddress
     * @throws \DragonJsonServer\Exception
	 */
	public function validateEmailaddress($emailaddress)
	{
		$validator = new \Zend\Validator\EmailAddress();
		if (!$validator->isValid($emailaddress)) {
			throw new \DragonJsonServer\Exception('invalid emailaddress', ['emailaddress' => $emailaddress]);
		}
		return $this;
	}
	
    /**
	 * Erstellt eine neue E-Mail Adressverknüpfung für den Account
     * @param integer $account_id
	 * @param string $emailaddress
	 * @param string $password
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress
	 */
	public function createEmailaddress($account_id, $emailaddress, $password)
	{
		$entityManager = $this->getEntityManager();
		
		$emailaddress = (new \DragonJsonServerEmailaddress\Entity\Emailaddress())
			->setAccountId($account_id)
			->setEmailaddress($emailaddress)
			->setPassword($password);
		$this->getServiceManager()->get('\DragonJsonServerDoctrine\Service\Doctrine')->transactional(function ($entityManager) use ($emailaddress) {
			$entityManager->persist($emailaddress);
			$entityManager->flush();
			$this->getEventManager()->trigger(
				(new \DragonJsonServerEmailaddress\Event\CreateEmailaddress())
					->setTarget($this)
					->setEmailaddress($emailaddress)
			);
			$this->getServiceManager()->get('\DragonJsonServerEmailaddress\Service\Validationrequest')
				->createValidationrequest($emailaddress);
		});
		return $emailaddress;
	}
	
    /**
	 * Entfernt die E-Mail Adressverknüpfung für den Account
     * @param integer $account_id
     * @throws \DragonJsonServer\Exception
     * @return Emailaddress
	 */
	public function removeEmailaddress($account_id)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $entityManager->getRepository('\DragonJsonServerEmailaddress\Entity\Emailaddress')
								      ->findOneBy(['account_id' => $account_id]);
		if (null === $emailaddress) {
			throw new \DragonJsonServer\Exception('missing emailaddress', ['account_id' => $account_id]);
		}
		$this->getServiceManager()->get('\DragonJsonServerDoctrine\Service\Doctrine')->transactional(function ($entityManager) use ($emailaddress) {
			$this->getEventManager()->trigger(
				(new \DragonJsonServerEmailaddress\Event\RemoveEmailaddress())
					->setTarget($this)
					->setEmailaddress($emailaddress)
			);
			$emailaddress_id = $emailaddress->getEmailaddressId();
			$entityManager
				->createQuery('
					DELETE FROM \DragonJsonServerEmailaddress\Entity\Passwordrequest passwordrequest
					WHERE passwordrequest.emailaddress_id = :emailaddress_id
				')
				->execute(['emailaddress_id' => $emailaddress_id]);
			$entityManager
				->createQuery('
					DELETE FROM \DragonJsonServerEmailaddress\Entity\Validationrequest validationrequest
					WHERE validationrequest.emailaddress_id = :emailaddress_id
				')
				->execute(['emailaddress_id' => $emailaddress_id]);
			$entityManager->remove($emailaddress);
			$entityManager->flush();
		});
		return $this;
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
			throw new \DragonJsonServer\Exception('invalid emailaddress_id', ['emailaddress_id' => $emailaddress_id]);
		}
		return $emailaddress;
	}
	
	/**
	 * Gibt die E-Mail Adresse zur übergebenen AccountID zurück
	 * @param integer $account_id
	 * @param boolean $throwException
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress|null
     * @throws \DragonJsonServer\Exception
	 */
	public function getEmailaddressByAccountId($account_id, $throwException = true)
	{
		$entityManager = $this->getEntityManager();

		$conditions = ['account_id' => $account_id];
		$emailaddress = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Emailaddress')
		    ->findOneBy($conditions);
		if (null === $emailaddress && $throwException) {
			throw new \DragonJsonServer\Exception('invalid account_id', $conditions);
		}
		return $emailaddress;
	}
	
	/**
	 * Gibt die E-Mail Adresse der übergebenen E-Mail Adresse zurück
	 * @param string $emailaddress
	 * @param boolean $throwException
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress|null
     * @throws \DragonJsonServer\Exception
	 */
	public function getEmailaddressByEmailaddress($emailaddress, $throwException = true)
	{
		$entityManager = $this->getEntityManager();

		$conditions = ['emailaddress' => $emailaddress];
		$emailaddress = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Emailaddress')
		    ->findOneBy($conditions);
		if (null === $emailaddress && $throwException) {
			throw new \DragonJsonServer\Exception('invalid emailaddress', $conditions);
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
		$emailaddress = $this->getEmailaddressByAccountId($account_id);
		$emailaddress->setEmailaddress($newemailaddress);
		$this->getServiceManager()->get('\DragonJsonServerDoctrine\Service\Doctrine')->transactional(function ($entityManager) use ($emailaddress) {
			$entityManager->persist($emailaddress);
			$entityManager->flush();
			$this->getServiceManager()->get('\DragonJsonServerEmailaddress\Service\Validationrequest')
				->createValidationrequest($emailaddress);
		});
		return $this;
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
