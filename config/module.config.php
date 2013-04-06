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
	'emailaddress' => [
		'from' => 'noreply@dragonjsonserver.de',
		'passwordrequest' => [
			'subject' => 'passwordrequest',
			'body' => '%passwordrequesthash%',
		],
	],
    'apiclasses' => [
        '\DragonJsonServerEmailaddress\Api\Emailaddress' => 'Emailaddress',
    ],
	'service_manager' => [
		'invokables' => [
            'Emailaddress' => '\DragonJsonServerEmailaddress\Service\Emailaddress',
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
