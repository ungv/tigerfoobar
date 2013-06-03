<?php
	$tagtype;
	$plural;
	if ($pageType == 'company') {	//loading industries
		$objectid = $companyInfo['CompanyID'];
		$tagtype = 'Industry';
		$plural = 'Industries';
		$list = $companyTags;
	?>
		<h1><?=$companyInfo['Name']?></h1>
		<div id="evidenceContainer" class="container">
			<div id='evidenceContent' class="content">
				<dl id="tagSection">
					<dt id="tagSectionIndicator"><?=$plural ?></dt>
					<dd>
						<ul id="taglist" tagtype="<?=$tagtype?>" objectid="<?=$objectid?>">
							<?php
							foreach ($list AS $tag) {
							?>
									<li<?php if($tag['uservoted']) { ?> class="userVoted" <?php } ?>>
										<span class="tagName"><a href="/tag/<?=$tag['TagsID']?>"><?=$tag['Name']?></a></span>
										<span>(</span>
											<span class="tagTotal"><?=$tag['votes']?></span>
										<span>)</span>
										<span class="tagUpvote" tagtype="<?=$tagtype?>" tagid="<?=$tag['TagsID']?>" objectid="<?=$objectid?>" voted="<?=$tag['uservoted']?>" title='Approve tag'>
											<?php if($tag['uservoted']) { ?>
													-
											<?php }else { ?>
													+
											<?php } ?>
										</span>
									</li>
							<?php } ?>
						</ul>
					</dd>
					<a id="addTag" href="#" <?php if(!$isLogged) { ?>style="display:none;"<?php } ?> title='Add new tag'>+</a>
					<div id="newTagPopup" style="display:none;">
						<input id="newtag_name" tagtype="<?=$tagtype?>" type="text" placeholder="Type a tag name"/>
					</div>
				</dl>
			</div>
		</div>
	<?php
	}else {
	?>
		<h1><a href="<?=$claimInfo['Link']?>"><?=$claimInfo['ClaimTitle']?></a></h1>
	<?php
	}
	?>
<!--End of Top-->