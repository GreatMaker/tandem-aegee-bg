<?php
/*
 * Interface for Controller classes
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

interface ctrl_interface
{
	// constructor
    public function		__construct($data);

	// process data
    public function		process();

	// reply to user
	public function		get_reply();
}
?>