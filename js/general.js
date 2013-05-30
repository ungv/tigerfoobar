/*
	Patchwork - General Front End Functionality
*/
var colors = ['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#5cffae', '#31b373', '#106138'];

$(document).ready(function() {
	/*--------FORM VALIDATION (DOCUMENTATION http://docs.jquery.com/Plugins/Validation)--------*/

	// Call jQuery validate plugin that injects messages for required fields on form submit
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
				required: "^Hmm, no password seems kinda fishy"
			}
		}
	});

	/*-----------TagIt Plugin (DOCUMENTATION: http://webspirited.com/tagit/docs.html)-------------------*/

	var companyList = []; // populated with all companies pulled from database as a source for autocompletion
	var tagsList = []; // populated with all tags pulled from database as a source for autocompletion
	
	// Input field for entering an associated company from "Add new claim" form
	$('#assocCo').tagit({
		tagSource: companyList,
		maxTags: 1,
		select: true,
		triggerKeys: ['enter', 'comma'],
		highlightOnExistColor: '#000'
	});

	// Input field for entering tags from "Add new claim" form
	$('#tagsSearch').tagit({
		tagSource: tagsList,
		select: true,
		triggerKeys: ['enter', 'comma'],
		highlightOnExistColor: '#000'
	});
	
 	/*------------Logging In-----------*/
	
	//Show login popup onclick
	$('#login').click(function() {
		hidePopups();
		$('#loginPopup').show(200);
	});

	//Ask server to login on click
	$('#login_submit').click(function() {
		$('#login_fail').hide(200);
		sendLogin( $('#login_username').val() , $('#login_password').val() );
	});

	//Ask server to login on click
	$('#login_cancel').click(function() {
		hidePopups();
	});

	//Sends the passed login parameters to server onclick
	function sendLogin(username, password) {
		$.ajax({
			type: 'POST',
			url: '/action/login',
			data: {
				username: username,
				password: password
			},
			dataType: 'json',
			success: function(json) {
				hidePopups();
				window.location.reload();
			},
			error: function() {
				$('#login_fail').show(200);
				$('#login_password').val('');
			}
		});
	}
	

	/*------Sigining Up------*/

	//Show signin box onclick
	$('#signup').click(function() {
		hidePopups();
		$('#signupPopup').show(200);
	});

	//Hide Signinpopup
	$('.cancelButton').click(function() {
		hidePopups();
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

	$('#signup_submit').click(function() {
		addUser($('#signupPopup input[name="username"]').val(), $('#signupPopup input[name="password"]').val(), $('#signupPopup input[name="email"]').val());
		console.log($('#signupPopup input[name="username"]').val(), $('#signupPopup input[name="password"]').val(), $('#signupPopup input[name="email"]').val());
	});

	//Adds a new user to the database
	function addUser(username, password, email) {
		$.ajax({
			type: 'POST',
			url: '/action/addUser',
			data: {
				username: username,
				password: password,
				email: email
			},
			dataType: 'json',
			success: function(json) {
				sendLogin(username, password);
			},
			error: function() {
				$('#username_exists').show(200);
			}
		});
	}


	/*-----Lights out----*/

	//Adjust height of overlay to fill screen when page loads
	$('.lightsout').css('height', $(document).height());

	//Adjust height of overlay to fill screen when browser gets resized  
	$(window).bind("resize", function(){  
		$(".lightsout").css("height", $(window).height());  
	}); 

	$('.lightsout').click(function () {
		$('.lightsout').fadeOut();
		hidePopups();
	});

	/*--------Auto Complete Info-------*/

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

	//Grabs a list of companies, cliams, and tags relavent to
	//the text the user has typed in the search box
	$("#searchInput").autocomplete({
		minLength: 0,
		source: function (request, response) {
	        $.ajax({
	            url: "/data/searchAutocomplete/" + $('#searchInput').val(),
	            dataType: 'json',
	            success: function (data) {
	            	$('#claimResults').html('<h3>Claims</h3>');
	            	$('#companyResults').html('<h3>Companies</h3>');
	            	if (data.length == 0) {
	            		data.push(
			          		{
		                        'name':  'No claims found found.',
		                        'id': -1,
		                        'type': "Empty",
		                        'score': -1
		                    }
		                );
		            }
	                response(data.map(function (value) {
	                    return {
	                        'name':  value.name ,
	                        'id': value.id,
	                        'type': value.type,
	                        'score': value.score
	                    };  
	                }));
	            }   
	        }); 
	    },
	    select: function( event, ui ) {
			//search for that company/claim/tag combination

		},
		open: function() { 
			$('.ui-menu').width(915); 
			//$(".ui-menu-item").hide();
			//$(".ui-autocomplete").hide();
			//$("#autoCompleteResults").show();
		}
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		
		//check if header
		var autocompleteLi;
		if(item.type == "header") {
			autocompleteLi = $( "<li>" , {
				"class": "autoCompleteHeader",
				"text" : item.name
			});
		}else if(item.type == "empty") {
			autocompleteLi = $( "<li>" , {
				"text" : item.name
			});
		}else {
			autocompleteLi = $( "<li>" , {
				"class": "autoCompleteResult"
			})
			.html( '<a><span class="resultName">' + item.name + '</span>' +
					'<span class="resultScore">' + item.score + '</span></a>');
			//!! can changes later to label industry
			autocompleteLi.addClass(item.type+"Result")
			$(autocompleteLi.children("a")[0]).attr("href","http://127.0.0.1/" + item.type + "/" + item.id);
		}
		autocompleteLi.appendTo(ul);
		return autocompleteLi;
	};;
});

/*--------Public functions that need to be accessed by other JS files----------*/

// Hide all popups currently showing
function hidePopups() {
	$('.lightsout').fadeOut();
	$('.popup').hide(200);
	$.each($('.popup'), function() {
		$('input').val('');
	});
}

//Apply css coloring defined in global COLORS variable to element based on the element's value
function applyColors(thisVal, $element, styling, stylewith) {
	if (thisVal < -2) {
		$element.css(styling, stylewith + colors[0]);
	} else if (thisVal < -1) {
		$element.css(styling, stylewith + colors[1]);
	} else if (thisVal < 0) {
		$element.css(styling, stylewith + colors[2]);
	} else if (thisVal == 0) {
		$element.css(styling, stylewith + colors[3]);
	} else if (thisVal < 1) {
		$element.css(styling, stylewith + colors[4]);
	} else if (thisVal <= 2) {
		$element.css(styling, stylewith + colors[5]);
	} else if (thisVal > 2) {
		$element.css(styling, stylewith + colors[6]);
	}
}

//Sends the passed login parameters to server onclick
//Reloads the current page
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
			hidePopups();
			window.location.reload();
		},
		error: function() {
			$('#login_fail').show(200);
			$('#login_password').val('');
		}
	});
}

//Resets and recolors the kudos scale to get rid of border color
function resetScale() {
	$.each($('.scoreBox'), function(i) {
		$(this).css('background-color', colors[i]);
		$(this).css('border', '2px solid ' + colors[i]);
		$(this).removeClass('selectedRating');
	});
}

// Check if the user is logged in before committing any action
// Pass in the element that contains the 'claimid' attribute to check if it is set
function isLoggedIn($action) {
	if ($('#main').attr('signedin') != 1) {
		alert('Please log in to ' + $action);
		$('#loginPopup').show(200);
		window.scrollTo(0, 0);
		return false;
	}
	return true;
}