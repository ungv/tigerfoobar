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
		($('#' + $id + 'Box').find('input')).focus();
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

	$('.submitButton').click(function() {
		if ($(this).parent().attr('id') == 'changeUserBox') {
			updateProfile('Name', ($(this).parent().find($('input')).val()));
		}
		if ($(this).parent().attr('id') == 'changePassBox') {
			updateProfile('Password', ($(this).parent().find($('input')).val()));
		}
		if ($(this).parent().attr('id') == 'changeEmailBox') {
			updateProfile('Email', ($(this).parent().find($('input')).val()));
		}
		if ($(this).parent().attr('id') == 'deleteAccountBox') {
			//dropAccount(($(this).parent().find($('input')).val()));
		}
	});
});

//Updates the user profile with new information
function updateProfile(col, newInfo) {
	$.ajax({
		type: 'POST',
		url: 'http://127.0.0.1/action/updateProfile',
		data: {
			col: col,
			newInfo: newInfo
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

//Drops the user from database
function dropAccount(password) {
	$.ajax({
		type: 'POST',
		url: 'http://127.0.0.1/action/dropAccount',
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
