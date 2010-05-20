<?php

require_once '../source/fontproxy.php';
require_once '../source/fonttypes.php';

$proxy = new FontProxy();

$proxy->addFont('Myriad Pro - Normal', FontTypes::OTF, '../fonts/MyriadPro-Semibold.otf');
$proxy->addFont('Myriad Pro - Bold', FontTypes::OTF, '../fonts/MyriadPro-bold.otf');
$proxy->addFont('Myriad Pro - Italic', FontTypes::OTF, '../fonts/MyriadPro-SemiboldIt.otf');

$proxy->addFont('Myriad Pro - Normal', FontTypes::EOT, '../fonts/MyriadPro-Semibold.eot');
$proxy->addFont('Myriad Pro - Bold', FontTypes::EOT, '../fonts/MyriadPro-bold.eot');
$proxy->addFont('Myriad Pro - Italic', FontTypes::EOT, '../fonts/MyriadPro-SemiboldIt.eot');

$proxy->addFontTypes('Myriad Pro - Bold Italic', array(
	FontTypes::OTF => '../fonts/MyriadPro-BoldIt.otf',
	FontTypes::EOT => '../fonts/MyriadPro-BoldIt.eot'
));

$proxy->addTypeFonts(FontTypes::TTF, array(
	'Myriad Pro Semibold - Normal' => '../fonts/MyriadPro-Semibold.ttf',
	'Myriad Pro Semibold - Bold' => '../fonts/MyriadPro-bold.ttf',
	'Myriad Pro Semibold - Italic' => '../fonts/MyriadPro-SemiboldIt.ttf',
	'Myriad Pro Semibold - Bold Italic' => '../fonts/MyriadPro-BoldIt.ttf'
));

print_r($proxy);

$font = $proxy->getFont('Myriad Pro - Normal', FontTypes::OTF);
print_r($font);

$support = $proxy->detectSupport($_SERVER['HTTP_USER_AGENT']);
print_r($support);

$serve = $proxy->serve('Myriad Pro - Bold Italic', $_SERVER['HTTP_USER_AGENT']);
print_r($serve);