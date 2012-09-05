<?php
/**
 * Language class
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */
class language_class
{
    private $curr_lang;

    private $available_langs = array("it_IT" => "it", "en_GB" => "en");
    private $lang_names      = array("it" => "Italiano", "en" => "English");

    public function __construct($force_lang = null)
    {
        bindtextdomain('messages', './locale');
        textdomain('messages');

        if (isset($force_lang) && $force_lang != "")
            $this->curr_lang = $force_lang;
        else
            $this->curr_lang = http_negotiate_language($this->available_langs, $res);

        // Impostazione lang da dati browser
        setlocale(LC_ALL, array_search($this->curr_lang, $this->available_langs));
    }

    public function GetActiveLanguage()
    {
        return $this->curr_lang;
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
