<div id="main">
	<div id="user" class="container">
		<h1><?=$userInfo['Name']?></h1>
		<?php
		if (isset($userdata['userid'])) {
		?>
		<p><a id="changeUser" href="">Change username</a> | <a id="changePass" href="">Change password</a> | <a id="changeEmail" href="">Change email</a> | <a id="deleteAccount" href="">delete account</a></p><br/>
		<p id="message"></p>
		<!-- <form id="updateForm"> -->
			<div id="changeUserBox" class="popup" style="display: none;">
				<input type="text" name="newUser" maxlength="20" placeholder="new username" />
				<button class="submitButton" type="submit">Update</button>
				<button class="cancelButton" type="button">cancel</button>
			</div>
			<div id="changePassBox" class="popup" style="display: none;">
				<input type="password" name="newPass" placeholder="new password" />
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
			</div>
		<!-- </form> -->
		<?php
		}
		?>
	</div>
	
	<div id="submissions">
		<h3>Submissions:</h3>
		<ul>
		<?php
		foreach ($userClaims as $claim) {
		?>
			<li id="<?=$claim['Value']?>"><a href="/claim/<?=$claim['ClaimID']?>"><?=$claim['Title']?></a></li>
		<?php
		}
		?>
		</ul>
	</div>
	
	<div id="comments" class="container">
		<h3>Comments:</h3>
		<?php
		foreach ($userComments as $comment) {
		?>
		<div class="threadContainer">
			<div class="thread">
				<h3><a href="">"<?=$comment['Comment']?>"</a></h3>
				<p><em><strong><?=$userInfo['Name']?></strong> rated <a href="/claim/<?=$comment['ClaimID']?>"><?=$comment['Title']?></a> a <strong class="theirRating"><?=$comment['Value']?></strong></em></p>
			</div>
		</div>
		<?php
		}
		?>
	</div>
	
	<div id="votes" class="container">
		<h3>Votes:</h3>
		<ul>
		<?php
		foreach ($userVotes as $vote) {
		?>
			<li>
				<p>
					<strong><?=$vote['Name']?></strong> 
					<?= $vote['Value'] == 0 ? '<span class="disliked">disliked</span>' : '<span class="liked">liked</span>' ?>
				</p>
				<p class="votedComment">
					"<a href="/claim/<?=$vote['ClaimID']?>/#<?=$vote['CommentID']?>"><?=$vote['Comment']?></a>"
				</p>
				<p class="coComment">about <a href="/company/<?=$vote['CompanyID']?>"><?=$vote['CoName']?></a></p>
			</li>
		<?php
		}
		?>
	</div>
</div>