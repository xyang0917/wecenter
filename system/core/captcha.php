<?php/*+--------------------------------------------------------------------------|   Anwsion [#RELEASE_VERSION#]|   ========================================|   by Anwsion dev team|   (c) 2011 - 2012 Anwsion Software|   http://www.anwsion.com|   ========================================|   Support: zhengqiang@gmail.com|   +---------------------------------------------------------------------------*/class core_captcha{	private $captcha;		public function __construct()	{		if (!is_dir(ROOT_PATH . 'cache/captcha/'))		{			mkdir(ROOT_PATH . 'cache/captcha/');		}				$this->captcha = new Zend_Captcha_Image(array(			'font' => $this->get_font(),			'imgdir' => ROOT_PATH . 'cache/captcha/',			'fontsize' => 24,			'width' => 120,			'height' => 40,			'wordlen' => 4,			'session' => new Zend_Session_Namespace(G_COOKIE_PREFIX . '_Captcha'),			'timeout' => 300		));				$this->captcha->setDotNoiseLevel(20);		$this->captcha->setLineNoiseLevel(2);	}		public function get_font()	{		if (!$captcha_fonts = AWS_APP::cache()->get('captcha_fonts'))		{			$dir_handle = opendir(AWS_PATH . 'core/fonts/');					while (($file = readdir($dir_handle)) !== false)			{			    if ($file != '.' AND $file != '..')			    {			    	if (strstr(strtolower($file), '.ttf'))			    	{			    		$captcha_fonts[] = AWS_PATH . 'core/fonts/' . $file;			    	}			   	}			 }			 			 closedir($dir_handle);			 			 AWS_APP::cache()->set('captcha_fonts', $captcha_fonts, get_setting('cache_level_normal'));		}						return array_random($captcha_fonts);	}		public function generate()	{		$this->captcha->generate();				HTTP::no_cache_header();				readfile($this->captcha->getImgDir() . $this->captcha->getId() . $this->captcha->getSuffix());				die;	}		public function is_validate($validate_code)	{		if (strtolower($this->captcha->getWord()) == strtolower($validate_code))		{			$this->captcha->generate();						return true;		}				return false;	}}