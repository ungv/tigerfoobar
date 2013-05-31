	<div id="user" class="container">
		<h1><?=$userInfo['Name']?></h1>

		<?php
		//User tools
		//Only visible when viewing own profile page and logged in
		if (isset($userdata['userid']) && $userdata['userid'] == $curProfile) {
		?>
		<p><a id="changeUser" href="">Change username</a> | <a id="changePass" href="">Change password</a> | <a id="changeEmail" href="">Change email</a> | <a id="deleteAccount" href="">delete account</a></p><br/>
		<p id="message"></p>
		<div id="changeUserBox" class="popup" style="display: none;">
			<input type="text" name="newUser" maxlength="20" placeholder="new username" />
			<button class="submitButton" type="submit">Update</button>
			<button class="cancelButton" type="button">cancel</button>
		</div>
		<div id="changePassBox" class="popup" style="display: none;">
			<div id="passMatchMsg" style="color: red; display: none;">Passwords do not match</div>
			<input type="password" name="newPass" placeholder="new password" />
			<input type="password" name="newPass2" placeholder="password again" />
			<button class="submitButton" type="submit">Update</button>
			<button class="cancelButton" type="button">cancel</button>
		</div>
		<div id="changeEmailBox" class="popup" style="display: none;">
			<input type="email" name="newEmail" placeholder="new email" />
			<button class="submitButton" type="submit">Update</button>
			<button class="cancelButton" type="button">cancel</button>
		</div>
		<div id="deleteAccountBox" class="popup" style="display: none;">
			<div><strong>ARE YOU ABSOLUTELY SURE?!</strong></div>
			<input type="password" name="verifyDeletion" placeholder="Enter password to delete" />
			<button class="submitButton" type="submit">Yes</button>
			<button class="cancelButton" type="button">no</button>
			<div><em>*This will not delete content you have already submitted to this website. You will not be able to access your account after this.</em></div>
		</div>
		<?php
		}
		?>
	</div>

	<h3 style="margin-bottom: 10px;">Submissions:</h3>