<?php
/**
 * Language class
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

/*if (!isset())
{
	function prefered_language ($available_languages, $http_accept_language="auto")
	{
		// if $http_accept_language was left out, read it from the HTTP-Header
		if ($http_accept_language == "auto") $http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

		// standard  for HTTP_ACCEPT_LANGUAGE is defined under 
		// http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
		// pattern to find is therefore something like this:
		//    1#( language-range [ ";" "q" "=" qvalue ] )
		// where:
		//    language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
		//    qvalue         = ( "0" [ "." 0*3DIGIT ] )
		//            | ( "1" [ "." 0*3("0") ] )
		preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" . 
					   "(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i", 
					   $http_accept_language, $hits, PREG_SET_ORDER);

		// default language (in case of no hits) is the first in the array
		$bestlang = $available_languages[0];
		$bestqval = 0;

		foreach ($hits as $arr) {
			// read data from the array of this hit
			$langprefix = strtolower ($arr[1]);
			if (!empty($arr[3])) {
				$langrange = strtolower ($arr[3]);
				$language = $langprefix . "-" . $langrange;
			}
			else $language = $langprefix;
			$qvalue = 1.0;
			if (!empty($arr[5])) $qvalue = floatval($arr[5]);

			// find q-maximal language  
			if (in_array($language,$available_languages) && ($qvalue > $bestqval)) {
				$bestlang = $language;
				$bestqval = $qvalue;
			}
			// if no direct hit, try the prefix only but decrease q-value by 10% (as http_negotiate_language does)
			else if (in_array($languageprefix,$available_languages) && (($qvalue*0.9) > $bestqval)) {
				$bestlang = $languageprefix;
				$bestqval = $qvalue*0.9;
			}
		}
		return $bestlang;
	}
}*/

class language_class
{
    private $curr_lang;

    private $available_langs = array("it_IT" => "it_IT", "en_US" => "en_US");
    private $lang_names      = array("it" => "Italiano", "en" => "English");

    public function __construct($force_lang = false)
    {
        if (isset($force_lang) && $force_lang != false)
            $this->curr_lang = $force_lang;
        else
            $this->curr_lang = http_negotiate_language($this->available_langs, $res); //TODO

		// Impostazione lang da dati browser
		putenv("LC_ALL=".array_search($this->curr_lang, $this->available_langs));
        setlocale(LC_ALL, array_search($this->curr_lang, $this->available_langs).".UTF-8");

		bindtextdomain('messages', '/home/utente/tandem_new/locale');
        textdomain('messages');        
    }

    public function GetActiveLanguage()
    {
        return $this->curr_lang;
    }

	public function GetAvailableLanguages()
	{
		return $this->available_langs;
	}

    /*public function GetLanguageBar()
    {
        $data = array(LANG => array('title' => _('Lingua'), 'image' => 'icons/world.png', 'right' => MAIN_RIGHT, 'subs'  => array(), 'extra' => array()));

        foreach ($this->available_langs as $full_langname => $langname)
        {
            $link = "#".$langname;

            $data[LANG]['subs'][$link] = array('title' => $this->lang_names[$langname], 'image' => "flags/".$langname.".png", 'right' => MAIN_RIGHT);

            if ($langname != $this->curr_lang)
                $data[LANG]['extra'][$link] = "onclick=\"$().set_language('".$langname."');\"";
        }

        return $data;
    }*/
}
?>
