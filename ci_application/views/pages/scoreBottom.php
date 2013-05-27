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