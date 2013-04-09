<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

/**
 * @return array
 */
return [
	'dragonjsonserveremailaddress' => [
		'from' => 'noreply@dragonjsonserver.de',
		'validationrequest' => [
			'subject' => 'validationrequest',
			'body' => '%validationrequesthash%',
		],
		'passwordrequest' => [
			'subject' => 'passwordrequest',
			'body' => '%passwordrequesthash%',
		],
	],
	'dragonjsonserver' => [
	    'apiclasses' => [
	        '\DragonJsonServerEmailaddress\Api\Emailaddress' => 'Emailaddress',
	        '\DragonJsonServerEmailaddress\Api\Validationrequest' => 'Validationrequest',
	        '\DragonJsonServerEmailaddress\Api\Passwordrequest' => 'Passwordrequest',
	    ],
    ],
	'service_manager' => [
		'invokables' => [
            'Emailaddress' => '\DragonJsonServerEmailaddress\Service\Emailaddress',
            'Passwordrequest' => '\DragonJsonServerEmailaddress\Service\Passwordrequest',
            'Validationrequest' => '\DragonJsonServerEmailaddress\Service\Validationrequest',
		],
	],
	'doctrine' => [
		'driver' => [
			'DragonJsonServerEmailaddress_driver' => [
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [
					__DIR__ . '/../src/DragonJsonServerEmailaddress/Entity'
				],
			],
			'orm_default' => [
				'drivers' => [
					'DragonJsonServerEmailaddress\Entity' => 'DragonJsonServerEmailaddress_driver'
				],
			],
		],
	],
];
