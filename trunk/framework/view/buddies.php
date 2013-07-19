<?php
/**
 * Buddies List View File
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 */

if ($page->is_user_logged() == false)
{
	require 'login_needed.php';
}
else
{
	// set template file
	$page->set_template('framework/templates/main.tpl');

	// set page title
	$page->set_title('Buddies');
	
	// Add jquery UI
	$page->AddJS("jquery-ui-1.10.1.custom.min.js");
	$page->AddCSS("ui-lightness/jquery-ui-1.10.1.custom.css");
	
	// get db connection
	$db_conn = null;
	$page->get_db($db_conn);
	
	// Page title
	$page->AddToBody("<h2>"._("Buddies List")."</h2>");

	$data = $db_conn->matching_find_buddies(14); // TODO real id

	foreach ($data as $lang => $buddies)
	{
		$bd_data = "<div style=\"border-bottom: 1px solid black; margin-bottom: 10px; \">".$db_conn->languages_get_lang_name($lang)."</div>";
		
		foreach ($buddies as $buddy_id => $buddy_data)
		{
			// get DB user data
			$user_data = $db_conn->user_get_data_by_id($buddy_id);

			// user image
			if (isset($user_data['facebook']) && $user_data['facebook'] != "")
				$usr_img = "<img src='http://graph.facebook.com/".$user_data['facebook']."/picture' />";
			else
			{
				if ($user_data['gender'] == "M")
					$usr_img = "<img src='img/user_def_male.png' />";
				else
					$usr_img = "<img src='img/user_def_female.png' />";
			}

			// user real name
			$usr_name = $user_data['name']." ".$user_data['surname'];

			$bd_data .= "<div style=\"clear: both; overflow:auto;\">";

			// Buddy name
			$bd_data .= "<div style=\"clear: both; font-size: 15px; margin-left: 10px;\"><strong>".$usr_name."</strong>";
			
			// Buddy affinity
			$affinity = ($buddy_data['score'] / $buddy_data['max_score']) * 100.0;

			$bd_data .= "<div style=\"float: right; font-size: 11px; margin-right: 10px;\">Affinity: ".round($affinity)."%</div></div>";
			
			// Buddy image
			$bd_data .= "<div style=\"border: 1px solid black; float: left; width: 50px; height: 50px; margin: 10px;\">".$usr_img."</div>";

			$bd_data .= "<div style=\"overflow:auto; margin-top: 10px;\">";

			// Buddy age
			if ($user_data['age'] != "" && $user_data['age'] != 0)
			{
				$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>Age:</strong></div>";
				$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px;\">".$user_data['age']."</div>";
			}

			// Buddy about..
			if (isset($user_data['about']) && $user_data['about'] != "")
			{
				$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>About me:</strong></div>";
				$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px;\">".$user_data['about']."</div>";
			}

			// Buddy languages
			$user_langs	= $db_conn->user_languages_get_by_id($buddy_id);

			$langs_str_s = _("Speaks")."&nbsp;";
			$lang_s_cnt  = 0;
			$langs_str_l = _("Wants to learn")."&nbsp;";
			$lang_l_cnt  = 0;

			foreach ($user_langs as $l_id => $l_data)
			{
				if ($l_data['mother_tongue'] == 1)
				{
					if ($lang_s_cnt != 0)
						$langs_str_s .= ",&nbsp;";

					$langs_str_s .= $l_data['lang_name'];
					$lang_s_cnt++;
				}
				else if ($l_data['mother_tongue'] == 0)
				{
					if ($lang_l_cnt != 0)
						$langs_str_l .= ",&nbsp;";

					$langs_str_l .= $l_data['lang_name']." ("._("now")." ".$db_conn->languages_get_level($l_data['level']).")";
					$lang_l_cnt++;
				}
			}

			$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>Languages:</strong></div>";
			$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px;\">".$langs_str_s."<br />".$langs_str_l."</div>";

			// Buddy common interests
			$user_c_interests = $db_conn->matching_get_common_interests(14, $buddy_id);

			if (count($user_c_interests) > 0)
			{
				$user_int_str  = "";
				$user_int_cnt  = 0;

				foreach ($user_c_interests as $i_id => $i_data)
				{
					if ($user_int_cnt != 0)
						$user_int_str .= ",&nbsp;";

					$user_int_str .= $i_data['interest'];
					$user_int_cnt++;
				}

				$bd_data .= "<div style=\"float: left; text-align: right; width: 20%; clear: left;\"><strong>Common interests:</strong></div>";
				$bd_data .= "<div style=\"float: left; margin-left: 5px; margin-bottom: 5px;\">".$user_int_str."</div>";
			}

			$bd_data .= "</div>";
			$bd_data .= "</div>";

			// Buttons
			$bd_data .= "<div style=\"clear: both; margin-right: 10px; overflow:auto;\">";
			$bd_data .= "<a href=\"#\" class=\"open_message\"><div style=\"float: right; border: 1px solid black; padding: 5px;\">"._("Send message")."</div></a>";
			$bd_data .= "</div>";

			// Separator
			$bd_data .= "<div style=\"line-height: .2em; background-color: #676767; height: 1px; margin: 10px;\"></div>";
		}
	}

	// send message dialog
	$page->AddJQuery("$(\"#dialog-message\").dialog({resizable: false, width: 500, height: 280, modal: true, autoOpen: false, close: function() {}});
					 $('.open_message').click(function() {\$(\"#dialog-message\").dialog('open'); return false;});");

	// set page
	$page->AddToBody($bd_data);

	// Message div
	$div_send_message = "<div id=\"dialog-message\" title=\"Send Message\">
						<p><form>
							<textarea name=\"message\" id=\"message\" rows=\"8\" cols=\"62\" class=\"ui-widget-content ui-corner-all\ style=\"resize: none;\"></textarea>
						</form></p>
						</div>";

	$page->AddToBody($div_send_message);
}
?>
