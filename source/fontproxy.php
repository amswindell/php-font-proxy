<?php

class FontProxy
{
	private $fonts = array();

	public function addFont($key, $type, $file)
	{
		if (!isset($this->fonts[$type]))
		{
			$this->fonts[$type] = array();
		}
		$this->fonts[$type][$key] = $file;
		return $this;
	}

	public function addFontTypes($key, $types)
	{
		foreach ($types as $type => $file)
		{
			$this->addFont($key, $type, $file);
		}
		return $this;
	}

	public function addTypeFonts($type, $fonts)
	{
		foreach ($fonts as $key => $file)
		{
			$this->addFont($key, $type, $file);
		}
		return $this;
	}

	public function removeFont($key, $type)
	{
		if (isset($this->fonts[$type][$key]))
		{
			unset($this->fonts[$type][$key]);
		}
		return $this;
	}

	public function getFont($key, $type)
	{
		if (isset($this->fonts[$type][$key]))
		{
			return $this->fonts[$type][$key];
		}
		return null;
	}

	public function sniff($agent)
	{
		$browser = '/(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/i';
		$platform = '/(ipod|iphone|ipad|webos|android|win|mac|linux)/i';

		if (preg_match($browser, $agent, $browsers))
		{
			if (preg_match($platform, $agent, $platforms))
			{
				$platform = $platforms[1];
			}
			else
			{
				$platform = 'other';
			}

			return array(
				'browser' => (strtolower($browsers[1]) == 'version') ? strtolower($browsers[3]) : strtolower($browsers[1]),
				'version' => (float) (strtolower($browsers[1]) == 'opera') ? strtolower($browsers[4]) : strtolower($browsers[2]),
				'platform' => strtolower($platform)
			);
		}
		return false;
	}

	public function detectSupport($agent)
	{
		$sniff = $this->sniff($agent);

		if ($sniff)
		{
			switch ($sniff['platform'])
			{
				case 'win':
				case 'mac':
				case 'linux':
					switch ($sniff['browser'])
					{
						case 'opera':
							return ($sniff['version'] > 10) ? array(FontTypes::TTF, FontTypes::OTF, FontTypes::SVG) : false;
							break;
						case 'safari':
							return ($sniff['version'] > 3.1) ? array(FontTypes::TTF, FontTypes::OTF) : false;
							break;
						case 'chrome':
							return ($sniff['version'] > 4) ? array(FontTypes::TTF, FontTypes::OTF) : false;
							break;
						case 'firefox':
							return ($sniff['version'] > 3.5) ? array(FontTypes::TTF, FontTypes::OTF) : false;
							break;
						case 'ie':
							return ($sniff['version'] > 4) ? array(FontTypes::EOT) : false;
							break;
					}
					break;
			}
		}
		return false;
	}

	public function serve($key, $agent)
	{
		$support = $this->detectSupport($agent);
		if ($support)
		{
			$fonts = array();
			foreach ($support as $type)
			{
				$font = $this->getFont($key, $type);
				if ($font)
				{
					$fonts[$type] = $this->getFont($key, $type);
				}
			}
			return $fonts;
		}
		return array();
	}
}