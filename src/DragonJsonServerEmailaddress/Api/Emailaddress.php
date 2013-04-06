<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

namespace DragonJsonServerEmailaddress\Api;

/**
 * API Klasse zur Verwaltung von E-Mail Adressverknüpfungen
 */
class Emailaddress
{
	use \DragonJsonServer\ServiceManagerTrait;
	
	/**
	 * Erstellt eine neue E-Mail Adressverknüpfung für den Account
	 * @param string $emailaddress
	 * @param string $password
	 * @authenticate
	 */
	public function linkAccount($emailaddress, $password)
	{
		$serviceManager = $this->getServiceManager();

		$sessionService = $serviceManager->get('Session');
		$session = $sessionService->getSession();
		$account = $serviceManager->get('Account')->getAccount($session->getAccountId());
		$emailaddress = $serviceManager->get('Emailaddress')->linkAccount(
			$account, 
			$emailaddress, 
			$password,
			$this->getServiceManager()->get('Config')['emailaddress']
		);
		$data = $session->getData();
		$data['emailaddress'] = $emailaddress->toArray();
		$session->setData($data);
		$sessionService->updateSession($session);
	}
	
    /**
	 * Entfernt die E-Mail Adressverknüpfung für den Account
	 * @authenticate
	 */
	public function unlinkAccount()
	{
		$serviceManager = $this->getServiceManager();

		$sessionService = $serviceManager->get('Session');
		$session = $sessionService->getSession();
		$account = $serviceManager->get('Account')->getAccount($session->getAccountId());
		$serviceManager->get('Emailaddress')->unlinkAccount($account);
		$data = $session->getData();
		unset($data['emailaddress']);
		$session->setData($data);
		$sessionService->updateSession($session);
	}
	
    /**
	 * Meldet den Account mit der übergebenen E-Mail Adressverknüpfung an
	 * @param string $emailaddress
	 * @param string $password
	 * @return array
	 */
	public function loginAccount($emailaddress, $password)
	{
		$serviceManager = $this->getServiceManager();

		$emailaddress = $serviceManager->get('Emailaddress')
			->getEmailaddressByEmailaddressAndPassword($emailaddress, $password);
		$account = $serviceManager->get('Account')->getAccount($emailaddress->getAccountId());
		$serviceSession = $serviceManager->get('Session');
		$session = $serviceSession->createSession($account, ['emailaddress' => $emailaddress->toArray()]);
		$serviceSession->setSession($session);
		return $session->toArray();
	}
	
	/**
	 * Ändert die E-Mail Adresse der E-Mail Adressverknüpfung
	 * @param string $newemailaddress
	 * @authenticate
	 */
	public function changeEmailaddress($newemailaddress)
	{
		$serviceManager = $this->getServiceManager();
		
		$sessionService = $serviceManager->get('Session');
		$session = $sessionService->getSession();
		$serviceManager->get('Emailaddress')->changeEmailaddress(
			$session->getAccountId(), 
			$newemailaddress,
			$this->getServiceManager()->get('Config')['emailaddress']
		);
		$data = $session->getData();
		if (isset($data['emailaddress'])) {
			$data['emailaddress']['emailaddress'] = $newemailaddress;
			$session->setData($data);
			$sessionService->updateSession($session);
		}
	}
	
	/**
	 * Ändert das Passwort der E-Mail Adressverknüpfung
	 * @param string $newpassword
	 * @authenticate
	 */
	public function changePassword($newpassword)
	{
		$serviceManager = $this->getServiceManager();
		
		$sessionService = $serviceManager->get('Session');
		$session = $sessionService->getSession();
		$serviceEmailaddress = $serviceManager->get('Emailaddress'); 
		$emailaddress = $serviceEmailaddress->getEmailaddressByAccountId($session->getAccountId());
		$serviceEmailaddress->changePassword($emailaddress, $newpassword);
	}
}
