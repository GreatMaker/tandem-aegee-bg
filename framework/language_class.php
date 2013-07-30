<?php
/**
 * Language class
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

class language_class
{
    private $curr_lang;

    private $available_langs = array("en_US" => "en", "it_IT" => "it");
    private $lang_names      = array("en_US" => "English", "it_IT" => "Italiano");

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
