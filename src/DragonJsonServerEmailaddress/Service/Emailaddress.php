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
		return $emailaddress;
	}
	
    /**
	 * Entfernt die E-Mail Adressverknüpfung für den Account
	 * @param \DragonJsonServerAccount\Entity\Account $account
     * @throws \DragonJsonServer\Exception
	 */
	public function unlinkAccount(\DragonJsonServerAccount\Entity\Account $account)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $entityManager->getRepository('\DragonJsonServerEmailaddress\Entity\Emailaddress')
								      ->findOneBy(['account_id' => $account->getAccountId()]);
		if (null === $emailaddress) {
			throw new \DragonJsonServer\Exception('no emailaddress found');
		}
		$entityManager->remove($emailaddress);
		$entityManager->flush();
	}
	
	/**
	 * Gibt die E-Mail Adresse der übergebenen E-Mail Adressverknüpfung zurück
	 * @param string $emailaddress
	 * @param string $password
	 * @return \DragonJsonServerEmailaddress\Entity\Emailaddress
     * @throws \DragonJsonServer\Exception
	 */
	public function getEmailaddress($emailaddress, $password)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Emailaddress')
		    ->findOneBy(['emailaddress' => $emailaddress]);
		if (null === $emailaddress) {
			throw new \DragonJsonServer\Exception('incorrect emailaddress or password');
		}
		$emailaddress->verifyPassword($password);
		return $emailaddress;
	}
}
