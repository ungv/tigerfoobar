<div id="main">
	<div id="urlContainer">
		<div id="urlButton">
			<img src="/img/newClaim.png" /><span>Add a new claim</span>
		</div>
	</div>
	
	<form id="newClaimForm">
		<div id="urlInput" class="quarter" style="display: none;">
			<input type="url" id="pasteURL" name="pasteURL" placeholder="Paste URL to a new article"/>
		</div>
		<div id="urlSubmit" class="popup" style="display:none;">
			<h3>We need more info about this URL</h3>
			<h4><em>Only * are required</em></h4>
			<input class="full" name="title" type="text" placeholder="Title*"/>
			<textarea rows="4" placeholder="Your comments on this article"></textarea>
			<h4>The most associated company to this article:</h4>
			<p><em>Please enter only one</em></p>
			<ul id="assocCo" name="assocCo" class="outfocus"></ul>		
			<h4>What does this article say about the associated company?</h4>

