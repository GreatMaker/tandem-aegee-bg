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

	$.fn.tandem_message = function(msg_from, msg_to, message_str)
    {
        $.post("framework/scripts/tandem.php", { func: "tandem_message", from: msg_from, to: msg_to, message: message_str}, reply_message, "json");
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
            alert(data.error);
	}
})(jQuery);