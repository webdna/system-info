# system-info

app.php

use webdna\systeminfo\SystemInfo;

return [
	'modules' => [
		'system-info' => SystemInfo::class,
	],
	'bootstrap' => ['system-info'],
];


url => actions/system-info/info?key={{ getenv(KEY) }}