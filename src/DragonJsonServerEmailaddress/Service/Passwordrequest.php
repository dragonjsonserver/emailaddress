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
 * Serviceklasse zur Verwaltung einer Passwort vergessen Anfragen
 */
class Passwordrequest
{
    use \DragonJsonServer\ServiceManagerTrait;
	use \DragonJsonServerDoctrine\EntityManagerTrait;
	
	/**
	 * Sendet eine E-Mail mit dem Hash zum Zurücksetzen des Passwortes
	 * @param string $emailaddress
	 */
	public function requestPassword($emailaddress)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $this->getServiceManager()->get('Emailaddress')
			->getEmailaddressByEmailaddress($emailaddress);
		$passwordrequesthash = md5($emailaddress->getEmailaddressId() . microtime(true));
		$entityManager->persist((new \DragonJsonServerEmailaddress\Entity\Passwordrequest())
			->setEmailaddressId($emailaddress->getEmailaddressId())
			->setPasswordrequesthash($passwordrequesthash));
		$entityManager->flush();
		$configEmailaddress = $this->getServiceManager()->get('Config')['emailaddress'];
		$message = (new \Zend\Mail\Message())
			->addTo($emailaddress->getEmailaddress())
	        ->addFrom($configEmailaddress['from'])
	        ->setSubject($configEmailaddress['passwordrequest']['subject'])
	        ->setBody(str_replace(
	        	'%passwordrequesthash%', 
	        	$passwordrequesthash, 
	        	$configEmailaddress['passwordrequest']['body']
	        ));
		(new \Zend\Mail\Transport\Sendmail())->send($message);
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
		$serviceEmailaddress = $this->getServiceManager()->get('Emailaddress');
		$emailaddress = $serviceEmailaddress->getEmailaddressByEmailaddressId($passwordrequest->getEmailaddressId());
		$serviceEmailaddress->changePassword($emailaddress, $newpassword);
		$entityManager->remove($passwordrequest);
		$entityManager->flush();
	}
}
