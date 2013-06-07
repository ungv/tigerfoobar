	<form id="newClaimForm" action="javascript:addClaim()">
		<!-- <div id="urlInput" class="quarter" style="display: none;"> </div> -->
		<div id="urlSubmit"> <!-- class="popup" style="display:none;" -->
			<!-- <h3>We need more info about this URL</h3> -->
			<h4 id="first"><em>Only * are required</em></h4>
			<p id="urlCheck" class="message"></p>
			<input id="pasteURL" type="pasteURL" class="full" name="pasteURL" placeholder="Paste source to a new article*"/>
			<input type="text" class="full" name="title" placeholder="Title*"/>
			<textarea rows="4" placeholder="A little summary of this article to put it in context"></textarea>
			<h4>The most associated company to this article:</h4>
			<p><em>*Please enter at least one and only one</em></p>
			<p id="coNote" class="message"><em>*All claims needs to refer to a company</em></p>
			<ul id="assocCo" name="assocCo" class="outfocus"></ul>		
			<h4>*What does this article say about the associated company?</h4>
			<p id="ratingNote" class="message"><em>*Please submit an initial rating for this claim</em></p>

			<?php
			//Import the kudos scale into form
			require 'score.php';
			?>

			<h4>Tags related to this article:</h4>
			<ul id="tagsSearch" name="tagsSearch" class="outfocus"></ul>
			<p><em>FYI: hit 'enter' to turn words into tags or separate them by a semicolon (;)</em></p>
			<!-- <img id="loadingGif" src="<?=base_url()?>img/pacmanload.gif" /> -->
			<button type="submit" id="addClaim" class="submitButton">Submit</button>
			<!-- <button type="button" class="cancelButton">cancel</button> -->
		</div>
	</form>

	<div id="successAlert">
		<p>Your claim has been submitted!</p>
	</div>