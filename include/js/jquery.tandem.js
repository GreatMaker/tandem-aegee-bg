/* 
 * General Tandem JS functions
 */

;(function($)
{
	// logout
    $.fn.tandem_logout = function()
    {
        $.post("framework/scripts/tandem.php", { func: "tandem_logout"}, reply, "json");
    };

	$.fn.tandem_toggle_visible = function()
    {
        $.post("framework/scripts/tandem.php", { func: "tandem_invisible"}, reply, "json");
    };

	$.fn.tandem_message = function(msg_from, msg_to, message_str)
    {
        $.post("framework/scripts/tandem.php", { func: "tandem_message", from: msg_from, to: msg_to, message: message_str}, reply_message, "json");
    };

	$.fn.tandem_add_friend = function(friend_id)
    {
        $.post("framework/scripts/tandem.php", { func: "tandem_add_friend", friend: friend_id}, reply, "json");
    };

	$.fn.tandem_remove_friend = function(friend_id)
    {
        $.post("framework/scripts/tandem.php", { func: "tandem_remove_friend", friend: friend_id}, reply, "json");
    };

	$.fn.tandem_block_user = function(block_id)
	{
		$.post("framework/scripts/tandem.php", { func: "tandem_block_user", block: block_id}, reply, "json");
	};

	$.fn.tandem_unblock_user = function(block_id)
	{
		$.post("framework/scripts/tandem.php", { func: "tandem_unblock_user", block: block_id}, reply, "json");
	};

	function getURLParameter(name) {return decodeURI((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);}

	// Reply function
    function reply(data)
    {
        if (data.error)
            alert(data.error);
        if (data.reload === 'true')
            window.location.reload();
        if (data.redirect)
            window.location = data.redirect;
    }

	function reply_message(data)
	{
		if (data.error)
		{
			$('#btn_send').hide();
			$('#dialog-message').html(data.error);
		}
        else
		{
			$('#btn_send').hide();
			$('#dialog-message').html(data.success);
		}
	}
})(jQuery);