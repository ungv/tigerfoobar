	<!--Start ScoreBottom content-->
			</section>

			<section id="rightCol">
				<div id="relatedTagsHeader">
				<?php
				if ($pageType == 'company') {
				?>
					<span>Popular Claim Tags for <?=$companyInfo['Name']?></span>
				<?php
				} else {
				?>
					<span><a href="/company/<?=$claimInfo['CompanyID']?>"><?=$claimInfo['CoName']?></a></span><br/>
					<span style="font-size: 10pt;">related claims:</span>
				<?php
				}
				?>
				</div>
				<div id="relatedTagsContainer">
				<?php
				if ($pageType == 'company') {
					?>
					<ul id="claimPopTags">
						<?php
						// need to get most popular claims for this company, then get their most popular tags
						foreach ($companyClaimTags AS $tag) {
							?>
							<li><?=$tag['Name']?> <?=$tag['total']?></li>
							<?php
						}
						?>
					</ul>
					<?php
				}
				?>
				</div>
			</section>
		</div>
	</div>
	<!--End ScoreBottom content-->