<?php

require_once '../source/fontproxy.php';
require_once '../source/fonttypes.php';

$proxy = new FontProxy();

$proxy->addFont('LuxiSans-Regular', FontTypes::OTF, '../fonts/LuxiSans-Regular.otf');
$proxy->addFont('LuxiSans-Bold', FontTypes::OTF, '../fonts/LuxiSans-Bold.otf');
$proxy->addFont('LuxiSans-BoldOblique', FontTypes::OTF, '../fonts/LuxiSans-BoldOblique.otf');

$proxy->addFont('LuxiSans-Regular', FontTypes::EOT, '../fonts/LuxiSans-Regular.eot');
$proxy->addFont('LuxiSans-Bold', FontTypes::EOT, '../fonts/LuxiSans-Bold.eot');
$proxy->addFont('LuxiSans-BoldOblique', FontTypes::EOT, '../fonts/LuxiSans-BoldOblique.eot');

$proxy->addFontTypes('LuxiSans - Bold Italic', array(
	FontTypes::OTF => '../fonts/LuxiSans-BoldIt.otf',
	FontTypes::EOT => '../fonts/LuxiSans-BoldIt.eot'
));

$proxy->addTypeFonts(FontTypes::TTF, array(
	'LuxiSans-Regular' => '../fonts/LuxiSans-Regular.ttf',
	'LuxiSans-Bold' => '../fonts/LuxiSans-Bold.ttf',
	'LuxiSans-Oblique' => '../fonts/LuxiSans-Oblique.ttf',
	'LuxiSans-BoldOblique' => '../fonts/LuxiSans-BoldOblique.ttf'
));

print_r($proxy);

$font = $proxy->getFont('BoldOblique-Regular', FontTypes::OTF);
print_r($font);

$support = $proxy->detectSupport($_SERVER['HTTP_USER_AGENT']);
print_r($support);

$serve = $proxy->serve('BoldOblique-BoldOblique', $_SERVER['HTTP_USER_AGENT']);
print_r($serve);