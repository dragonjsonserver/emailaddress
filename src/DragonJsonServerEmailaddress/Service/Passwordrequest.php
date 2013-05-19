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
	 * @return Passwordrequest
	 */
	public function requestPassword($emailaddress)
	{
		$entityManager = $this->getEntityManager();

		$emailaddress = $this->getServiceManager()->get('\DragonJsonServerEmailaddress\Service\Emailaddress')
			->getEmailaddressByEmailaddress($emailaddress);
		$passwordrequesthash = md5($emailaddress->getEmailaddressId() . microtime(true));
		$entityManager->persist((new \DragonJsonServerEmailaddress\Entity\Passwordrequest())
			->setEmailaddressId($emailaddress->getEmailaddressId())
			->setPasswordrequesthash($passwordrequesthash));
		$entityManager->flush();
		$config = $this->getServiceManager()->get('Config')['dragonjsonserveremailaddress'];
		$message = (new \Zend\Mail\Message())
			->addTo($emailaddress->getEmailaddress())
	        ->addFrom($config['from'])
	        ->setSubject($config['passwordrequest']['subject'])
	        ->setBody(str_replace(
	        	'%passwordrequesthash%', 
	        	$passwordrequesthash, 
	        	$config['passwordrequest']['body']
	        ));
		(new \Zend\Mail\Transport\Sendmail())->send($message);
		return $this;
	}
	
	/**
	 * Setzt das Passwort des übergebenen Hashes
	 * @param string $passwordrequesthash
	 * @param string $newpassword
	 * @return Passwordrequest
	 * @throws \DragonJsonServer\Exception
	 */
	public function resetPassword($passwordrequesthash, $newpassword)
	{
		$entityManager = $this->getEntityManager();

		$conditions = ['passwordrequesthash' => $passwordrequesthash];
		$passwordrequest = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Passwordrequest')
			->findOneBy($conditions);
		if (null === $passwordrequest) {
			throw new \DragonJsonServer\Exception('invalid passwordrequesthash', $conditions);
		}
		$this->getServiceManager()->get('\DragonJsonServerDoctrine\Service\Doctrine')->transactional(function ($entityManager) use ($newpassword, $passwordrequest) {
			$serviceEmailaddress = $this->getServiceManager()->get('\DragonJsonServerEmailaddress\Service\Emailaddress');
			$emailaddress = $serviceEmailaddress->getEmailaddressByEmailaddressId($passwordrequest->getEmailaddressId());
			$serviceEmailaddress->changePassword($emailaddress, $newpassword);
			$entityManager->remove($passwordrequest);
			$entityManager->flush();
		});
		return $this;
	}
}
