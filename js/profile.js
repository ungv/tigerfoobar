$(document).ready(function() {
	// Call jQuery validate plugin that injects messages for required fields on form submit
	$('#updateForm').validate({
		rules: {
			newUser: {
				required: true
			},
			newPass: {
				required: true
			},
			newEmail: {
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

	$('#user a').click(function (e) {
		e.preventDefault();
		$id = $(this).attr('id');
		hidePopups();
		$('#' + $id + 'Box').show(200);
		$('.focusthis').focus();
	});

	$('.cancelButton').click(function() {
		hidePopups();
	});

	$.each($('#submissions li'), function() {
		applyColors(parseFloat($(this).attr('id')), $(this), 'background-color');
	});
	
	$.each($('.theirRating'), function() {
		applyColors(parseFloat($(this).text()), $(this), 'color');
	});
	
	$.each($('.liked'), function() {
		$(this).css('color', colors[6]);
	});

	$.each($('.disliked'), function() {
		$(this).css('color', colors[0]);
	});

	$('.scrollBox').scrollTop(0);
	$('.scrollBox').bind('scroll', function() {
		$(this).addClass('topBorder');
		if ($(this).scrollTop() == 0) {
			$(this).removeClass('topBorder');
		}
	});

	$('input[name=oldPass]').blur(function() {
		$elem = $('input[name=oldPass]');
		$.ajax({
			type: 'POST',
			url: '/action/passCheck',
			data: {
				password: $elem.val()
			},
			dataType: 'json',
			success: function(json) {
				//user entered correct current password
				$elem.parent().find('.submitButton').attr('disabled', 'false');
				$elem.parent().find('.submitButton').removeClass('disabled');
				$('#passMatchMsg').hide(200);
			},
			error: function() {
				$('#passMatchMsg').text('Current password incorrect.').show(200);
				$elem.parent().find('.submitButton').attr('disabled', 'disabled');
				$elem.parent().find('.submitButton').addClass('disabled');
			}
		});
	});
});

function updatecheck(elem) {
	if (elem.find($('input')).val() == '') {
		elem.find('.emptyMsg').show(200);
	} else {
		console.log(elem);
		if (elem.attr('id') == 'changeUserBox') {
			updateProfile('Name', (elem.find($('input')).val()));
		}
		if (elem.attr('id') == 'changePassBox') {
			if ($('input[name=newPass]').val() != '' && $('input[name=newPass]').val() == $('input[name=newPass2]').val()) {
				updateProfile('Password', (elem.find($('input')).val()));
			} else {
				$('#passMatchMsg').hide();
				$('#passMatchMsg').show(200);
			}
		}
	}
	if (elem.attr('id') == 'changeEmailBox') {
		updateProfile('Email', (elem.find($('input')).val()));
	}
	if (elem.attr('id') == 'deleteAccountBox') {
		alert('user cannot delete account yet');
		// TODO: check entered password against password in database as confirmation for deletion
		//dropAccount((elem.find($('input')).val()));
	}
}

//Updates the user profile with new information
function updateProfile(col, newInfo) {
	$.ajax({
		type: 'POST',
		url: '/action/updateProfile',
		data: {
			col: col,
			newInfo: newInfo
		},
		dataType: 'json',
		success: function(json) {
			window.location.reload();
			hidePopups();
			$('#message').text('successfully updated!');
			$('#message').css('color', 'green');
			$('#message').fadeIn();
			$('#message').fadeOut(2000);
		},
		error: function() {
			hidePopups();
			$('#message').text('oops, are you logged in?');
			$('#message').css('color', 'red');
			$('#message').fadeIn();
			$('#message').fadeOut(3000);
		}
	});
}

//Drops the user from database
function dropAccount(password) {
	$.ajax({
		type: 'POST',
		url: '/action/dropAccount',
		data: {
			password: password
		},
		dataType: 'json',
		success: function(json) {
			hidePopups();
			$('#message').text('successfully updated!');
			$('#message').css('color', 'green');
			$('#message').fadeIn();
			$('#message').fadeOut(2000);
			window.location.reload();
		},
		error: function() {
			hidePopups();
			$('#message').text('oops, are you logged in?');
			$('#message').css('color', 'red');
			$('#message').fadeIn();
			$('#message').fadeOut(3000);
		}
	});
}
