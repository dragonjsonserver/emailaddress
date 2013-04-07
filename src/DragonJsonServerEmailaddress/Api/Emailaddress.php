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
	 * Validiert die übergebene E-Mail Adresse
	 * @param string $emailaddress
     * @throws \DragonJsonServer\Exception
	 */
	public function validateEmailaddress($emailaddress)
	{
		$serviceManager = $this->getServiceManager();
		
		$serviceEmailaddress = $serviceManager->get('Emailaddress');
		$serviceEmailaddress->validateEmailaddress($emailaddress);
		if (null !== $serviceEmailaddress->getEmailaddressByEmailaddress($emailaddress, false)) {
			throw new \DragonJsonServer\Exception('emailaddress not unique', ['emailaddress' => $emailaddress]);
		}
	}
	
	/**
	 * Erstellt eine neue E-Mail Adressverknüpfung für den Account
	 * @param string $emailaddress
	 * @param string $password
	 * @session
	 */
	public function linkAccount($emailaddress, $password)
	{
		$this->validateEmailaddress($emailaddress);
		$serviceManager = $this->getServiceManager();

		$sessionService = $serviceManager->get('Session');
		$session = $sessionService->getSession();
		$account = $serviceManager->get('Account')->getAccount($session->getAccountId());
		$emailaddress = $serviceManager->get('Emailaddress')->linkAccount($account, $emailaddress, $password);
		$data = $session->getData();
		$data['emailaddress'] = $emailaddress->toArray();
		$session->setData($data);
		$sessionService->updateSession($session);
	}
	
    /**
	 * Entfernt die E-Mail Adressverknüpfung für den Account
	 * @session
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
	 * Gibt die E-Mail Adressverknüpfung des aktuellen Accounts zurück
	 * @return array|null
	 * @session
	 */
	public function getEmailaddress()
	{
		$serviceManager = $this->getServiceManager();
		
		$session = $serviceManager->get('Session')->getSession();
		$account = $serviceManager->get('Account')->getAccount($session->getAccountId());
		$emailaddress = $serviceManager->get('Emailaddress')->getEmailaddressByAccountId($session->getAccountId(), false);
		if (null !== $emailaddress) {
			return $emailaddress->toArray();
		}
		return;
	}
	
	/**
	 * Ändert die E-Mail Adresse der E-Mail Adressverknüpfung
	 * @param string $newemailaddress
	 * @session
	 */
	public function changeEmailaddress($newemailaddress)
	{
		$this->validateEmailaddress($emailaddress);
		$serviceManager = $this->getServiceManager();
		
		$sessionService = $serviceManager->get('Session');
		$session = $sessionService->getSession();
		$serviceManager->get('Emailaddress')->changeEmailaddress(
			$session->getAccountId(), 
			$newemailaddress
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
	 * @session
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
