<?php	
	/* TODO 6/3/13
	* Finish filters
	*
	*/
	$claimsByCompanyButtonClass = ($pageType == "home") ? "button active" : "button";
	$showTopClaimsButtonClass = ($pageType == "claimBrowse") ? "button active" : "button";
	$showTopCompaniesButtonClass = ($pageType == "companyBrowse") ? "button active" : "button";
	$showTopTagsButtonClass = ($pageType == "tagBrowse") ? "button active" : "button";
?>
	<div id = "treemapFilters">
		<div id = "explore">
			Explore:
		</div>
		<div id = "claimsByCompanyButton" class = "<?=$claimsByCompanyButtonClass?>">
			Claims by Company
		</div>
		
		<div id = "showTopClaimsButton" class = "<?=$showTopClaimsButtonClass?>">
			Top Claims
		</div>
		
		<div id = "showTopCompaniesButton" class = "<?=$showTopCompaniesButtonClass?>">
			Top Companies
		</div>
		
		<div id = "showTopTagsButton"  class = "<?=$showTopTagsButtonClass?>">
			Top Tags
		</div>
	</div>
	
	<div id = "treemapIncrementDecrement">
		<div>
			<!--p>
				>
			</p-->
		</div>
		<div>
			<!--p>
				<
			</p-->
		</div>
	</div>
<?php
	
?>

<!-- Start treemap -->
<div id = "treemapCanvas" class = "full">
</div>

<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script type="text/javascript">
	$(function () {
		<?php
		if (isset($pageType) && ($pageType == "home" || $pageType == "claimBrowse" || $pageType == "companyBrowse" || $pageType == "tagBrowse")) {
		?>
			var availVisibleHeight = $(window).height()+100 - $("#treemapCanvas").offset().top - $("footer").height() - 40;
			var treemapHeight = (availVisibleHeight >= 500) ? availVisibleHeight : 500;
			
			var availVisibleWidth = $(window).width() - 60;
			var treemapWidth = (availVisibleWidth >= 500) ? availVisibleWidth : 500;
		<?php
		} else if (isset($pageType) && ($pageType == "company" || $pageType == "tag" || $pageType == "profile")) {
		?>
			var treemapHeight = 500;
			var treemapWidth = $("#main").width();
			
			//Very kludgy. TODO: save data in variables, not DOM elements
			$("#treemapFilters").hide();
		
		<?php	
		}
		?>
		
		initializeEvents();
		
		var w = treemapWidth,
			h = treemapHeight,
			x = d3.scale.linear().range([0, w]),
			y = d3.scale.linear().range([0, h]),
			mouseX,
			mouseY,
			bgColor = d3.scale.quantile()
			   .domain([-3, -2, -1, 0, 1, 2, 3])
			   .range(['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64']),
			borderColor = d3.scale.category10(),
			borderWidth = 1,
			domain = document.domain,
			padding = 20,
			root,
			node;
			
		//By default, display whatever data is passed in by the server			
		var jsonDataObj = {<?php
			echo($treemapJSON);
		?>};
		generateTreemap(jsonDataObj);
	
		function initializeEvents() {
			//Initialize function to track mouse location
			$(document).mousemove(function(e) {
				mouseX = e.clientX;
				mouseY = e.clientY;
			}).mouseover();
			
			//Initialize treemap filters
			$("#showTopClaimsButton").click(showTopClaims);
			$("#claimsByCompanyButton").click(showTopCompaniesWithClaims);
			$("#showTopCompaniesButton").click(showTopCompanies);
			$("#showTopTagsButton").click(showTopTags);
			
			$("*:not(#treemapCanvas)").click(clearTooltips);
		}
		
		function generateTreemap(data) {
			var treemap = d3.layout.treemap()
				.round(true)
				.size([w, h])
				.sticky(true)
				.value(function(d) { return d.size>0? d.size : 0; });

			var svg = d3.select("#treemapCanvas")
				.attr("class", "chart")
				.style("width", w + "px")
				.style("height", h + "px")
			  .append("svg:svg")
				.attr("width", w)
				.attr("height", h)
			  .append("svg:g")
				.attr("class", "outerG");

			node = root = data;

			var claimNodes = treemap.nodes(root)
			.filter(function (d) {return !d.children;})

			
			var claimCell = svg.selectAll(".outerG")
				.data(claimNodes)
				.enter()
				.append("svg:g")
				.attr("class", "cell")
				.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
				/*.attr("stroke", function (d) {return borderColor(d.parent.name);})
				.attr("stroke-width", function (d) {return borderWidth;})*/
			
			claimCell.append("svg:rect")
			  .attr("width", function(d) { return (d.dx - borderWidth) >= 0? (d.dx - borderWidth) : 0;})
			  .attr("height", function(d) { return (d.dy - borderWidth) >= 0? (d.dy - borderWidth) : 0;})
			  .attr("title", function(d) {return computeTitle(d);})
			  .attr("rx", "10")
			  .attr("ry", "10")
			  .style("fill", function(d) { return bgColor(d.score);})
			  .on("mouseover", function (d) {d3.select(this).style("fill-opacity", "0.75");})
			  .on("mouseout", function (d) {d3.select(this).style("fill-opacity", "1.0");})
			  .on("click", function(d) { return zoom(node == d.parent ? root : d.parent, svg); });
			 
			//Text labels for treemap
			claimCell.append("foreignObject")
			  .attr("x", function(d) { return padding - 5;})
			  .attr("y", function(d) { return padding})
			  .attr("width", function(d) {return d.dx - padding})
			  .attr("height", function(d) {return d.dy - padding})
			  .attr("class", "textOuterWrapper")
			  .style("pointer-events", "none")
			  .append("xhtml:div")
				  .attr("dy", ".35em")
				  .attr("title", function(d) {return computeTitle(d);})
				  .attr("class", "textInnerWrapper")
				  .html(function(d) {return ((d.name.length < 100) ? d.name : (d.name.substring(0,100)+'...'));})
				  .style("font-size", function(d) {return calculateFontSize(d.dx - 2*padding, d.dy - 2*padding, $(this).html());})
				  .style("width", function(d) {return d.dx - padding})
				  .style("height", function(d) {return d.dy - padding})
				  .style("cursor", "pointer")
				  .style("pointer-events", "auto")
				  .on("mouseover", function (d) {$(this).parent().siblings("rect").css("fill-opacity", "0.75");})
				  .on("mouseout", function (d) {$(this).parent().siblings("rect").css("fill-opacity", "1.0");})
				  .on("click", function(d) {
						if (typeof d.claimID !== 'undefined') { //The clicked item is a claim title
							window.location.href = "/claim/" + d.claimID;
						} else if (typeof d.companyID !== 'undefined') { //The clicked item is a company name
							window.location.href = "/company/" + d.companyID;
						} else if (typeof d.tagID !== 'undefined') { //The clicked item is a tag name
							window.location.href = "/tag/" + d.tagID;
						}
					});

			makeTooltips("svg rect, svg div");
			
			<?php if (isset($pageType) && ($pageType == "home" || $pageType == "claimBrowse" || $pageType == "companyBrowse" || $pageType == "tagBrowse")) {
			?>
			//Add company borders
			var companyNodes = treemap.nodes(root)
			.filter(function (d) {return d.children});

			if (companyNodes.length > 1) {
				var companyCellContainer = svg.append("svg:g")
				.attr("class", "companyCellContainer");
				
				var companyCell = companyCellContainer.selectAll(".companyCellContainer")
				.data(companyNodes)
				.enter()
				.append("svg:g")
					.attr("class", "cell")
					.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
				.append("svg:rect")
					.attr("width", function(d) { return d.dx - 1;})
					.attr("height", function(d) { return d.dy - 1;})
					.attr("rx", "10")
					.attr("ry", "10")
					.style("fill-opacity", function(d) { return "0.0";}) //Fix
					.style("stroke", function(d) {return "white";})
					.style("stroke-width", function(d) {return "5";})
					.style("pointer-events", "none");
				$(".companyCellContainer>g:nth-child(1)").detach();
			}
			<?php
			}
			?>
		}
		
		function makeTooltips(selector) {
			//Tooltips
			$(selector).tooltipster({
				trigger: 'hover',
				interactive: true,
				interactiveTolerance: 10000,
				onlyOne: true,
				maxWidth: 400,
				delay: 700,
				functionReady: function(origin, tooltip) {
					var scrollTop = $(window).scrollTop();
					var scrollLeft = $(window).scrollLeft();
					var top = scrollTop + mouseY - $(tooltip).height() - 10;
					var left = scrollLeft + mouseX - $(tooltip).width()/2;

					if (top - $(tooltip).height() < 0) {
						//top = top + $(tooltip).height();
					}
					
					if (left < 0) {
						left = 0;
					}
					
					if (left + $(tooltip).width() > $(window).width()) {
						left = $(window).width() - tooltip.outerWidth();
						//left = left - (left + tooltip.width() - $(window).width());
					}
					
					$(tooltip).css({
						"top": top + "px",
						"left": left + "px"
					});
					
					$(".tooltipster-arrow").hide();
				},
				position: 'top'
			});
		}
		
		function clearTooltips() {
			$(".tooltipster-base").detach();
		}
				
		function computeTitle(d) {
			var html = ""
			
			if (typeof d.claimID !== 'undefined' && typeof d.name !== 'undefined') {
				html += "<a href = '/claim/" + d.claimID + "'><h3>" + d.name + "</h3></a> <br/>";
			}
			
			if (typeof d.description !== 'undefined') {
				html += "<p><h5>Description:</h5> " + (d.description.substring(0,100)+'...') + "</p>"
			}
			
			//TODO: Fix this mess; save the state in variables rather than button
			if ($("#claimsByCompanyButton").hasClass("active") || $("#showTopClaimsButton").hasClass("active")) {
				html+="<h5>Total number of votes:</h5> " + d.size + "<br />";
				html+="<h5>Average score:</h5> " + d.score + "<br />";
			}

			if (typeof d.companyID !== 'undefined' && typeof d.company !== 'undefined') {
				html+="<h5>Company: <a href = '/company/" + d.companyID + "'>" + d.company + "</a></h5><br />";
			}
			
			if ($("#showTopCompaniesButton").hasClass("active")) {
				html+="<h5>Total number of claims:</h5> " + d.size + "<br />";
				html+="<h5>Average score:</h5> " + d.score + "<br />";
			}
			if ($("#showTopTagsButton").hasClass("active")) {
				html+="<a href = '/tag/" + d.tagID + "'><h5>" + d.name + "</h5></a><br />";
				html+="<h5>Total times used:</h5> " + d.size + "<br />";
				html+="<h5>Average score:</h5> " + d.score + "<br />";
			}
			
			if (typeof d.userID !== 'undefined' && typeof d.userName !== 'undefined') {
				html += "<h5>Submitted by: <a href = '/profile/" + d.userID + "'>" + d.userName + "</a></h5>";
			}
			return html;
		}
		
		function calculateFontSize(width, height, text) {
				var maxSize = 40;
				var currSize = maxSize;
				var sizingDiv = $("<div></div>");
				$("body").append(sizingDiv);
				sizingDiv.html(text);
		
				sizingDiv.css({"max-width": width + 50, "font-size": maxSize, "display":"inline-block", "visibility":"hidden", "word-spacing":"" });
				
				var count = 0;
				while(sizingDiv.height() > height && count<(maxSize-10)) {
					if (count>maxSize-10) 
						break;
					var currSize = currSize - 1;
					sizingDiv.css("font-size", currSize);
					count ++;
				}
				
				sizingDiv.remove();
				
				var trueFontSize = currSize;//newTrueFontSizeWidth;//newTrueFontSizeWidth < trueFontSizeHeight ? trueFontSizeHeight : newTrueFontSizeWidth;
				return trueFontSize + "px";//(width*height)/4000 + "px";
			}
		
		function size(d) {
		  return d.size;
		}

		function count(d) {
		  return 1;
		}

		function zoom(d, svg) {
		  var kx = w / d.dx, ky = h / d.dy;
		  x.domain([d.x, d.x + d.dx]);
		  y.domain([d.y, d.y + d.dy]);

		  var t = svg.selectAll("g.cell").transition()
			  .duration(d3.event.altKey ? 7500 : 750)
			  .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });

		  t.select("rect")
			  .attr("width", function(d) { return kx * d.dx - borderWidth; })
			  .attr("height", function(d) { return ky * d.dy - borderWidth; })

		  t.select("text")
			  .attr("x", function(d) { return kx * padding;})
			  .attr("y", function(d) { return ky * padding;})
			  .style("opacity", function(d) { return kx * d.dx > d.w ? 1 : 0; });

		  //Resize text box
		  var textWrapper = t.select(".textOuterWrapper")
			  .attr("width", function(d) { return kx * d.dx - padding; })
			  .attr("height", function(d) { return ky * d.dy - padding; })

		  //Resize text
		  textWrapper.select(".textInnerWrapper")
			  .style("font-size", function(d) {return calculateFontSize(kx * d.dx - 2*padding, ky * d.dy - 2*padding, $(this).html());});
			
		  node = d;
		  d3.event.stopPropagation();
		}
		
		function showTopCompaniesWithClaims(e) {
			$(".active").removeClass("active");
			$(e.target).addClass("active");
			
			//Clear old treemap and place loading gif in the middle of it
			$("#treemapCanvas").prepend('<div class="loadingOverlay" style = "width : '+parseInt($("#treemapCanvas").width() + 10) + 'px; height : '+parseInt($("#treemapCanvas").height() + 10) +'px;"><img src = "/img/loading.gif" alt = "loading" /></div>');
			
			$.getJSON("/data/topCompaniesWithClaims", function(result){
				//Clear old treemap
				$("#treemapCanvas").empty();
				$(".loadingOverlay").hide();
				
				generateTreemap(result);
			});
		}
		
		function showTopClaims(e) {
			$(".active").removeClass("active");
			$(e.target).addClass("active");
			
			//Clear old treemap and place loading gif in the middle of it
			$("#treemapCanvas").prepend('<div class="loadingOverlay" style = "width : '+parseInt($("#treemapCanvas").width() + 10) + 'px; height : '+parseInt($("#treemapCanvas").height() + 10) +'px;"><img src = "/img/loading.gif" alt = "loading" /></div>');
			
			$.getJSON("/data/claimsInRange", function(result){
				//Clear old treemap
				$("#treemapCanvas").empty();
				$(".loadingOverlay").detach();

				generateTreemap(result);
			});
		}
		
		function showTopCompanies(e) {
			$(".active").removeClass("active");
			$(e.target).addClass("active");
			
			//Clear old treemap and place loading gif in the middle of it
			$("#treemapCanvas").prepend('<div class="loadingOverlay" style = "width : '+parseInt($("#treemapCanvas").width() + 10) + 'px; height : '+parseInt($("#treemapCanvas").height() + 10) +'px;"><img src = "/img/loading.gif" alt = "loading" /></div>');
			
			$.getJSON("/data/companiesInRange", function(result){
				//Clear old treemap
				$("#treemapCanvas").empty();
				$(".loadingOverlay").hide();

				generateTreemap(result);
			});
		}
		
		function showTopTags(e) {
			$(".active").removeClass("active");
			$(e.target).addClass("active");
			
			//Clear old treemap and place loading gif in the middle of it
			$("#treemapCanvas").prepend('<div class="loadingOverlay" style = "width : '+parseInt($("#treemapCanvas").width() + 10) + 'px; height : '+parseInt($("#treemapCanvas").height() + 10) +'px;"><img src = "/img/loading.gif" alt = "loading" /></div>');
			
			$.getJSON("/data/tagsInRange", function(result){
				//Clear old treemap
				$("#treemapCanvas").empty();
				$(".loadingOverlay").hide();

				generateTreemap(result);
			});
		}
	});
</script>
<!-- End treemap -->