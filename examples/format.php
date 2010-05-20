<?php

require_once '../source/fontproxy.php';
require_once '../source/fonttypes.php';

$proxy = new FontProxy();

$proxy->addFontTypes('Myriad Pro - Normal', array(
	FontTypes::OTF => '../fonts/MyriadPro-Semibold.otf',
	FontTypes::EOT => '../fonts/MyriadPro-Semibold.eot',
	FontTypes::TTF => '../fonts/MyriadPro-Semibold.ttf'
))->addFontTypes('Myriad Pro - Bold', array(
	FontTypes::OTF => '../fonts/MyriadPro-Bold.otf',
	FontTypes::EOT => '../fonts/MyriadPro-Bold.eot',
	FontTypes::TTF => '../fonts/MyriadPro-Bold.ttf'
))->addFontTypes('Myriad Pro - Italic', array(
	FontTypes::OTF => '../fonts/MyriadPro-SemiboldIt.otf',
	FontTypes::EOT => '../fonts/MyriadPro-SemiboldIt.eot',
	FontTypes::TTF => '../fonts/MyriadPro-SemiboldIt.ttf'
))->addFontTypes('Myriad Pro - Bold Italic', array(
	FontTypes::OTF => '../fonts/MyriadPro-BoldIt.otf',
	FontTypes::EOT => '../fonts/MyriadPro-BoldIt.eot',
	FontTypes::TTF => '../fonts/MyriadPro-BoldIt.ttf'
));

$declarations = '';
$sniff = $proxy->sniff($_SERVER['HTTP_USER_AGENT']);
$fonts = isset($_GET['font']) ? explode('|', urldecode($_GET['font'])) : array();

if (sizeof($fonts) > 0)
{
	foreach ($fonts as $font)
	{
		$extra = ' - Normal';
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
					$extra = ' - Bold';
					break;
				case 'i':
				case 'italic':
					$style = 'oblique';
					$extra = ' - Italic';
					break;
				case 'bi':
				case 'bold italic':
					$weight = 'bold';
					$style = 'oblique';
					$extra = ' - Bold Italic';
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

if ($declarations)
{
	echo $declarations;
}
else
{
	echo '/* no fonts to show */';
}