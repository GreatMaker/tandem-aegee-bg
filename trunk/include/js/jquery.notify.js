/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function processReply(data)
{ 
    if (data.error)
		alert(data.error);
	if (data.reload == 'true')
		window.location.reload();
	if (data.redirect)
		window.location = data.redirect;
}