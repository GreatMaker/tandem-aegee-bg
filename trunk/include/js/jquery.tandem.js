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

	// Reply function
    function reply(data)
    {
        if (data.error)
            alert(data.error);
        if (data.reload == 'true')
            window.location.reload();
        if (data.redirect)
            window.location = data.redirect;
    }
})(jQuery);