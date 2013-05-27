	<div id="urlContainer">
		<div id="urlButton">
			<img src="/img/newClaim.png" /><span>Add a new claim</span>
		</div>
	</div>
	
	<form id="newClaimForm" action="javascript:addClaim()">
		<div id="urlInput" class="quarter" style="display: none;">
			<input type="url" id="pasteURL" name="pasteURL" placeholder="Paste URL to a new article"/>
		</div>
		<div id="urlSubmit" class="popup" style="display:none;">
			<h3>We need more info about this URL</h3>
			<h4><em>Only * are required</em></h4>
			<input class="full" name="title" type="text" placeholder="Title*"/>
			<textarea rows="4" placeholder="A little summary of this article to put it in context"></textarea>
			<h4>The most associated company to this article:</h4>
			<p id="coNote"><em>*Please enter at least one and only one</em></p>
			<ul id="assocCo" name="assocCo" class="outfocus"></ul>		
			<h4>What does this article say about the associated company?</h4>

			<?php
			//Import the kudos scale into form
			require 'score.php';
			?>

			<h4>Tags related to this article:</h4>
			<ul id="tagsSearch" name="tagsSearch" class="outfocus"></ul>
			<p><em>FYI: you can type out all of your tags in one line separated by ";" instead of hitting enter everytime</em></p>
			<button type="submit" id="addClaim" class="submitButton">Submit</button>
			<button type="button" class="cancelButton">cancel</button>
		</div>
	</form>