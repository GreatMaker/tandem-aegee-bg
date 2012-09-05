<?php
/*
 * Sidebar Box class
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

class box_class
{
    private $box_data;

    public function __construct($title)
    {
        $this->box_data .= "\t\t\t<li>\n";
        $this->box_data .= "\t\t\t\t<h4><span>".$title."</span></h4>\n";
    }

    public function add_content($content)
    {
        $this->box_data .= "\t\t\t\t".$content."\n";
    }

    public function get_box_data()
    {
        // close li
        $this->box_data .= "\t\t\t</li>";

        return $this->box_data;
    }
}
?>
