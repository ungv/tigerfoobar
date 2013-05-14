	<!--Start Score content-->
	<div class="scoreContainer">
		<h2>Score</h2>
		<div id="scoreContent">
			<section id="leftCol">
				<!-- have to make form auto submit with js -->
				<div id="scoreHeader">
					<span id="averageScore"><?=$pageType == 'company' ? 
						$companyInfo['Score'] : $claimInfo[0]['Score'] ?></span>
					<span id="scoreInfo">(30 claims, 976 comments)</span>
				</div>
				<div id="controlContainer">
					<div id='scoreDistribution'>
					<?php
					for ($i=0; $i <=6 ; $i++) { 
						?>
						<div style='top: <?=7	*$i?>px'>
						<?php
						for ($j=0; $j < 4; $j++) { 
							?>
							<p class='circle'></p>
							<?php
						}
						?>	
						</div>
						<?php
					}
					?>	
					</div>

					<div id="scoreControl">
					<?php
					for ($i=0; $i <=6 ; $i++) { 
						?>
						<input type='radio' id="radio<?=$i?>" name='score' value='<?=$i?>'>
						<label class="scoreBox" for="radio<?=$i?>" value="<?=$i-3?>"></label>
						<?php
					}
					?>					
					</div>
				</div>
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
					<span>Other claims related to <a href="/company/<?=$claimInfo[0]['CompanyID']?>"><?=$claimInfo[0]['Name']?></a></span>
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
	<!--End Score content-->