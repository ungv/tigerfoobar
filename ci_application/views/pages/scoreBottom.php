	<!--Start ScoreBottom content-->
			</section>

			<section id="rightCol">
				<div id="relatedTagsHeader">
				<?php
				if ($pageType == 'company') {
				?>
					<span>Tags applied to related claims</span>
				<?php
				} else {
				?>
					<span>Other claims related to <a href="/company/<?=$claimInfo[0]['CompanyID']?>"><?=$claimInfo[0]['CoName']?></a></span>
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
						foreach ($companyTags AS $tag) {
							?>
							<li><?=$tag['Name']?></li>
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