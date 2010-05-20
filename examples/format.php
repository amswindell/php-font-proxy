<?php

require_once '../source/fontproxy.php';
require_once '../source/fonttypes.php';

$proxy = new FontProxy();

$proxy->addFontTypes('LuxiSans-Regular', array(
	FontTypes::OTF => '../fonts/LuxiSans-Regular.otf',
	FontTypes::EOT => '../fonts/LuxiSans-Regular.eot',
	FontTypes::TTF => '../fonts/LuxiSans-Regular.ttf'
))->addFontTypes('LuxiSans-Bold', array(
	FontTypes::OTF => '../fonts/LuxiSans-Bold.otf',
	FontTypes::EOT => '../fonts/LuxiSans-Bold.eot',
	FontTypes::TTF => '../fonts/LuxiSans-Bold.ttf'
))->addFontTypes('LuxiSans-Oblique', array(
	FontTypes::OTF => '../fonts/LuxiSans-Oblique.otf',
	FontTypes::EOT => '../fonts/LuxiSans-Oblique.eot',
	FontTypes::TTF => '../fonts/LuxiSans-Oblique.ttf'
))->addFontTypes('LuxiSans-BoldOblique', array(
	FontTypes::OTF => '../fonts/LuxiSans-BoldOblique.otf',
	FontTypes::EOT => '../fonts/LuxiSans-BoldOblique.eot',
	FontTypes::TTF => '../fonts/LuxiSans-BoldOblique.ttf'
));

$declarations = '';
$sniff = $proxy->sniff($_SERVER['HTTP_USER_AGENT']);
$fonts = isset($_GET['font']) ? explode('|', urldecode($_GET['font'])) : array();

if (sizeof($fonts) > 0)
{
	foreach ($fonts as $font)
	{
		$extra = '-Regular';
		$weight = '';
		$style = '';
		$font = explode(':', $font);

		if (sizeof($font) > 1)
		{
			switch (strtolower($font[1]))
			{
				case 'b':
				case 'bold':
					$weight = 'bold';
					$extra = '-Bold';
					break;
				case 'i':
				case 'italic':
				case 'o':
				case 'oblique':
					$style = 'oblique';
					$extra = '-Oblique';
					break;
				case 'bi':
				case 'bold italic':
					$weight = 'bold';
					$style = 'oblique';
					$extra = '-BoldOblique';
					break;
			}
		}

		$font = $font[0];
		$served = $proxy->serve($font . $extra, $_SERVER['HTTP_USER_AGENT']);
		if (sizeof($served) > 0)
		{
			$keys = array_keys($served);
			$declarations .= '@font-face {';
			$declarations .= 'font-family: "' . $font . '";';

			if ($weight)
			{
				$declarations .= 'font-weight: ' . $weight . ';';
			}
			if ($style)
			{
				$declarations .= 'font-style: ' . $style . ';';
			}

			if ($sniff && strtolower($sniff['browser']) == 'ie')
			{
				$declarations .= 'src: url("' . $served[$keys[0]] . '");';
			}
			else
			{
				$declarations .= 'src: url("' . $served[$keys[0]] . '") format("' . $keys[0] . '");';
			}

			$declarations .= '}';
		}
	}
}

// Thanks mennovanslooten!
header('Content-type: text/css');

if ($declarations)
{
	echo $declarations;
}
else
{
	echo '/* no fonts to show */';
}