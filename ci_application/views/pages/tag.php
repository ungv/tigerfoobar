
		<div id="listview">
			<ul id="claimsList">
			<?php
			foreach ($tagInfo as $claim) {
				?>
				<li id="<?=$claim['Score']?>">
					<p><a href="/claim/<?=$claim['ClaimID']?>"><?=$claim['Title']?></a></p>
					<p style="float: right;">Linked to <a href="/company/<?=$claim['CompanyID']?>"><?=$claim['CoName']?></a></p>
				</li>
				<?php
			}
			?>
			</ul>
		</div>