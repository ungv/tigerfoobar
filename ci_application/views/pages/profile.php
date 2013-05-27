	<div id="user" class="container">
		<h1><?=$userInfo['Name']?></h1>
		<?php
		if (isset($userdata['userid']) && $userdata['userid'] == $curProfile) {
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
				<div><em>*This will not delete content you have already submitted to this website. You will not be able to access your account after this.</em></div>
			</div>
		<!-- </form> -->
		<?php
		}
		?>
	</div>
	
	<div id="submissions">
		<h3>Submissions:</h3>
		<?php
		// If the user has not submitted anything before, display message
		if (empty($userClaims[0]['ClaimID'])) {
		?>
			<p>You haven't submitted any content yet! Get started with the button at the top!</p>
		<?php
		} else {
		?>
		<ul>
			<?php
			foreach ($userClaims as $claim) {
			?>
			<li id="<?=$claim['Value']?>"><a href="/claim/<?=$claim['ClaimID']?>"><?=$claim['Title']?></a></li>
			<?php
			}
		}
		?>
		</ul>
	</div>
	
	<div id="comments" class="container">
		<h3>Comments:</h3>
		<?php
		// If the user has not commented on anything before, display message
		if (empty($userComments[0]['Comment'])) {
		?>
			<p>You haven't commented on anyone's claims yet! Get started on the <a href="/claim">claims page</a></p>
		<?php
		} else {
			foreach ($userComments as $comment) {
			?>
		<div class="threadContainer">
			<div class="thread">
<<<<<<< HEAD
				<h3><a href="">"<?=$comment['Comment']?>"</a></h3>
=======
				<h3><a href="/claim/<?=$comment['ClaimID']?>/#<?=$comment['CommentID']?>comment">"<?=$comment['Comment']?>"</a></h3>
>>>>>>> origin/victor
				<p><em>About <a href="/company/<?=$comment['CompanyID']?>"><?=$comment['CoName']?></a>, from '<a href="/claim/<?=$comment['ClaimID']?>"><?=$comment['Title']?></a>,' of which they <?=$comment['Value'] == NULL ? 'have not rated yet' : 'gave a score of <strong class="theirRating">' . $comment["Value"] .'</strong>'?></em>
				</p>
			</div>
		</div>
			<?php
			}
		}
		?>
	</div>
	
	<div id="votes" class="container">
		<h3>Votes:</h3>
		<?php
		// If the user has not commented on anything before, display message
		if (empty($userVotes[0]['VoteID'])) {
		?>
			<p>You haven't voted on anyone's comments yet! Get started on the <a href="/claim">claims page</a></p>
		<?php
		} else {
		?>
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
<<<<<<< HEAD
					"<a href="/claim/<?=$vote['ClaimID']?>/#<?=$vote['CommentID']?>"><?=$vote['Comment']?></a>"
=======
					"<a href="/claim/<?=$vote['ClaimID']?>/#<?=$vote['CommentID']?>comment"><?=$vote['Comment']?></a>"
>>>>>>> origin/victor
				</p>
				<p class="coComment">about <a href="/company/<?=$vote['CompanyID']?>"><?=$vote['CoName']?></a></p>
			</li>
		<?php
			}
		}
		?>
	</div>
