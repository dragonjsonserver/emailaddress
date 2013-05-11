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
	 * @DragonJsonServerAccount\Annotation\Session
	 */
	public function createEmailaddress($emailaddress, $password)
	{
		$this->validateEmailaddress($emailaddress);
		$serviceManager = $this->getServiceManager();

		$sessionService = $serviceManager->get('Session');
		$session = $sessionService->getSession();
		$emailaddress = $serviceManager->get('Emailaddress')->createEmailaddress($session->getAccountId(), $emailaddress, $password);
		$data = $session->getData();
		$data['emailaddress'] = $emailaddress->toArray();
		$sessionService->changeData($session, $data);
		return $emailaddress->toArray();
	}
	
    /**
	 * Entfernt die E-Mail Adressverknüpfung für den aktuellen Account
	 * @DragonJsonServerAccount\Annotation\Session
	 */
	public function removeEmailaddress()
	{
		$serviceManager = $this->getServiceManager();

		$sessionService = $serviceManager->get('Session');
		$session = $sessionService->getSession();
		$serviceManager->get('Emailaddress')->removeEmailaddress($session->getAccountId());
		$data = $session->getData();
		unset($data['emailaddress']);
		$sessionService->changeData($session, $data);
	}
	
    /**
	 * Meldet den Account mit der übergebenen E-Mail Adressverknüpfung an
	 * @param string $emailaddress
	 * @param string $password
	 * @return array
	 */
	public function loginEmailaddress($emailaddress, $password)
	{
		$serviceManager = $this->getServiceManager();

		$emailaddress = $serviceManager->get('Emailaddress')
			->getEmailaddressByEmailaddressAndPassword($emailaddress, $password);
		$serviceSession = $serviceManager->get('Session');
		$session = $serviceSession->createSession($emailaddress->getAccountId(), ['emailaddress' => $emailaddress->toArray()]);
		$serviceSession->setSession($session);
		return $session->toArray();
	}
	
	/**
	 * Gibt die E-Mail Adressverknüpfung des aktuellen Accounts zurück
	 * @return array|null
	 * @DragonJsonServerAccount\Annotation\Session
	 */
	public function getEmailaddress()
	{
		$serviceManager = $this->getServiceManager();
		
		$session = $serviceManager->get('Session')->getSession();
		$emailaddress = $serviceManager->get('Emailaddress')->getEmailaddressByAccountId($session->getAccountId(), false);
		if (null !== $emailaddress) {
			return $emailaddress->toArray();
		}
		return;
	}
	
	/**
	 * Ändert die E-Mail Adresse der E-Mail Adressverknüpfung
	 * @param string $newemailaddress
	 * @DragonJsonServerAccount\Annotation\Session
	 */
	public function changeEmailaddress($newemailaddress)
	{
		$this->validateEmailaddress($newemailaddress);
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
			$sessionService->changeData($session, $data);
		}
	}
	
	/**
	 * Ändert das Passwort der E-Mail Adressverknüpfung
	 * @param string $newpassword
	 * @DragonJsonServerAccount\Annotation\Session
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
