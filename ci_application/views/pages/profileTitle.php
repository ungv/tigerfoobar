	<div id="user" class="container">
		<h1><?=$userInfo['Name']?></h1>

		<?php
		//User tools
		//Only visible when viewing own profile page and logged in
		if (isset($userdata['userid']) && $userdata['userid'] == $curProfile) {
		?>
		<p><a id="changeUser" href="">Change username</a> | <a id="changePass" href="">Change password</a> | <a id="changeEmail" href="">Change email</a> | <a id="deleteAccount" href="">delete account</a></p><br/>
		<p id="message"></p>
		<form action="javascript:updatecheck($('#changeUserBox'))">
			<div id="changeUserBox" class="popup" style="display: none;">
				<div class="emptyMsg" style="color: red; display: none;">You don't want a blank username...</div>
				<input class="focusthis" type="text" name="newUser" maxlength="20" placeholder="new username" />
				<button class="submitButton" type="submit">Update</button>
				<button class="cancelButton" type="button">cancel</button>
			</div>
		</form>	
		<form action="javascript:updatecheck($('#changePassBox'))">
			<div id="changePassBox" class="popup" style="display: none;">
				<div class="emptyMsg" style="color: red; display: none;">You definitely don't want a blank password...</div>
				<div id="passMatchMsg" style="color: red; display: none;">Passwords do not match</div>
				<input class="focusthis" type="password" name="newPass" placeholder="new password" />
				<input type="password" name="newPass2" placeholder="password again" />
				<button class="submitButton" type="submit">Update</button>
				<button class="cancelButton" type="button">cancel</button>
			</div>
		</form>	
		<form action="javascript:updatecheck($('#changeEmailBox'))">
			<div id="changeEmailBox" class="popup" style="display: none;">
				<div><em>submit this blank form to delete your email from our database</em></div>
				<input class="focusthis" type="email" name="newEmail" placeholder="new email" />
				<button class="submitButton" type="submit">Update</button>
				<button class="cancelButton" type="button">cancel</button>
			</div>
		</form>	
		<form action="javascript:updatecheck($('#deleteAccountBox'))">
			<div id="deleteAccountBox" class="popup" style="display: none;">
				<div class="emptyMsg" style="color: red; display: none;">We need to verify this is your account</div>
				<div><strong>ARE YOU ABSOLUTELY SURE?!</strong></div>
				<input class="focusthis" type="password" name="verifyDeletion" placeholder="Enter password to delete" />
				<button class="submitButton" type="submit">Yes</button>
				<button class="cancelButton" type="button">no</button>
				<div><em>*This will not delete content you have already submitted to this website. You will not be able to access your account after this.</em></div>
			</div>
		</form>	
		<?php
		}
		?>
	</div>

	<h3 style="margin-bottom: 10px;">Submissions:</h3>