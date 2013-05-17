/*
	Patchwork - General Front End Functionality
*/
var colors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64'];

$(document).ready(function() {
	var passwordMsgs = ['^Hmm, no password seems kinda fishy', '^Wait, ya kinda need a password', '^Uh, without a password, anyone can have your account', '^Ah, huge security risk!'];
	
	$('#signupForm').validate({
		rules: {
			username: {
				required: true
			},
			password: {
				required: true
			},
			email: {
				email: true
			}
		},
		messages: {
			username: {
				required: "^We can't just call you nothing..."
			},
			password: {
				required: passwordMsgs[Math.floor(Math.random() * passwordMsgs.length)]
			}
		}
	});
	
	//Sends the passed login parameters to server onclick
	function sendLogin(username, password) {
		$.ajax({
			type: 'POST',
			url: 'http://127.0.0.1/action/login',
			data: {
				username: username,
				password: password
			},
			dataType: 'json',
			success: function(json) {
				$('#loginPopup').hide(200);
				$('#login_username').val('');
				$('#login_password').val('');
				$('#login_buttons').hide(200);
				$('#login_status').html('Logged in as ' + json.username);
				$('#login_status').show(200);
				$('#logout').show(200);
			},
			error: function() {
				alert('Fail!');
				$('#login_fail').show(200);
				$('#login_password').val('');
			}
		});
	}
	
 	/*------Logging In------*/
	
	//Show login popup onclick
	$('#login').click(function() {
		$('#loginPopup').show(200);
		$('#signupPopup').hide(200);
	});

	//Ask server to login on click
	$('#login_submit').click(function() {
		$('#login_fail').hide(200);
		sendLogin( $('#login_username').val() , $('#login_password').val() );
	});

	//Ask server to login on click
	$('#login_cancel').click(function() {
		$('#loginPopup').hide(200);
		$('#login_fail').hide(200);
		$('#login_username').val('');
		$('#login_password').val('');
	});

	/*------Sigining Up------*/

	//Show signin box onclick
	$('#signup').click(function() {
		$('#signupPopup').show(200);
		$('#loginPopup').hide(200);
	});

	//Hide Signinpopup
	$('.cancelButton').click(function() {
		$('#signupPopup').hide(200);
	});

	//Set focus behavior for input boxes
	$('input, textarea').each(function() {
			$(this).addClass('outfocus');
		});
	$('input, textarea').focus(function() {
		$(this).removeClass('outfocus').addClass('infocus');
	}).blur(function() {
		$(this).removeClass('infocus').addClass('outfocus');
	}).mouseenter(function() {
		$(this).addClass('hover');
	}).mouseleave(function() {
		$(this).removeClass('hover');
	});

	/*-----Auto Complete Info----*/

	var projects = [
			{
				value: "jquery",
				label: "jQuery",
				desc: "the write less, do more, JavaScript library",
				icon: "jquery_32x32.png"
			},
			{
				value: "jquery-ui",
				label: "jQuery UI",
				desc: "the official user interface library for jQuery",
				icon: "jqueryui_32x32.png"
			},
			{
				value: "sizzlejs",
				label: "Sizzle JS",
				desc: "a pure-JavaScript CSS selector engine",
				icon: "sizzlejs_32x32.png"
			}
		];

	$( "#tags" ).autocomplete({
		minLength: 0,
		source: projects,
		focus: function( event, ui ) {
			$( "#tags" ).val( ui.item.label );
			return false;
		},
		select: function( event, ui ) {
			$( "#tags" ).val( ui.item.label );
			return false;
		}
	})
	.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li>" )
		.append( "<a>" + item.label + "<br>" + item.desc + "</a>" )
		.appendTo( ul );
	};	
});
