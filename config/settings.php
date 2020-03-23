<?php

return [
	'cyclic_tasks_days' => env('GENERAL.CYCLIC_TASKS_DAYS',3),
	'general'           => [
		'cyclic_tasks_days' => env('GENERAL.CYCLIC_TASKS_DAYS',3)
	],
	'sms'               => [
		'user' => env('SMS.USER',''),
		'pass' => env('SMS.PASS',''),
	]
];