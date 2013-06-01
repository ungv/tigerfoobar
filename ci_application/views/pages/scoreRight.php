			</section>
			<!-- End leftCol -->

			<section id="rightCol">
				<div id="relatedTagsHeader">
					<span>Average daily scores for this <?=$pageType == 'company' ? 'company' : 'claim'?>:</span>
				<?php
				// if ($pageType == 'company') {
				?>
				<?php
				// } else {
				?>
					<!-- <span>Average daily scores for this claim:</span> -->
				<?php
				// }
				?>
				</div>
				<div id="relatedTagsContainer">
				<?php
				// if ($pageType == 'company') {
					?>
					<!-- <ul id="claimPopTags"> -->
						<?php
						// need to get most popular claims for this company, then get their most popular tags
						// foreach ($companyTags AS $tag) {
							?>
							<!-- <li>[need help with this query]<?//=$tag['Name']?></li> -->
							<?php
						// }
						?>
					<!-- </ul> -->
					<?php
				// }
				?>
				</div>