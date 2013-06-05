<?php	
	/* TODO 6/3/13
	* Finish filters
	* Position tooltips at the cursor position
	* Enable /claim/ 
		     /company/
			 /tag/
			 /profile/ (?)
	   treemaps
	*
	*/
	if (isset($pageType) && $pageType == "home") {
?>
	<div id = "treemapFilters">
		<div id = "claimsByCompanyButton" class = "button active">
			Claims by Company
		</div>
		
		<div id = "showTopClaimsButton" class = "button">
			Top Claims
		</div>
		
		<div id = "showTopCompaniesButton" class = "button">
			Top Companies
		</div>
		
		<div class = "button">
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
	}
?>

<!-- Start treemap -->
<div id = "treemapCanvas" class = "full">
</div>

<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script type="text/javascript">
	$(function () {
		<?php
		if (isset($pageType) && $pageType == "home") {
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
		<?php
		} else if (isset($pageType) && $pageType == "claim") {
		?>
			var treemapHeight = 100;
			var treemapWidth = 410;
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
			$(document).mousemove(function(e) {
				mouseX = e.clientX;
				mouseY = e.clientY;
			}).mouseover();
			
			$("#showTopClaimsButton").click(showTopClaims);
			$("#claimsByCompanyButton").click(showTopCompaniesWithClaims);
			$("#showTopCompaniesButton").click(showTopCompanies);
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
			  //.attr("title", function(d) {return computeTitle(d);})
			  .style("pointer-events", "none")
			  .append("xhtml:div")
				  .attr("dy", ".35em")
				  .attr("title", function(d) {return computeTitle(d);})
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
							window.location.href = "<?=base_url()?>claim/" + d.claimID;
						} else if (typeof d.companyID !== 'undefined') { //The clicked item is a company name
							window.location.href = "<?=base_url()?>company/" + d.companyID;
						}
					});

			makeTooltips("svg rect, svg div");
			
			<?php if (isset($pageType) && $pageType == "home") {
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
			//End company borders
			<?php
			}
			?>
			
			d3.select(window).on("click", function() { zoom(root, svg); });
			d3.select("select").on("change", function() {
				treemap.value(this.value == "size" ? size : count).nodes(root);
				zoom(node, svg);
			});
		}
		
		function makeTooltips(selector) {
			//Tooltips
			$(selector).tooltipster({
				trigger: 'hover',
				interactive: true,
				interactiveTolerance: 10000,
				onlyOne: true,
				maxWidth: 400,
				timer: 20000,
				functionReady: function(origin, tooltip) {
					var scrollTop = $(window).scrollTop();
					var scrollLeft = $(window).scrollLeft();
					var top = scrollTop + mouseY;
					var left = scrollLeft + mouseX - $(tooltip).width()/2;

					var rotation = 0;
					
					if (top + $(tooltip).height() > ($(window).height() - 50)) {
						top = top - $(tooltip).height();
					}
					
					if (left < 0) {
						left = 0;
					}
					
					$(tooltip).css({
						"top": top + "px",
						"left": left + "px",
					});
				},
				position: 'bottom'
			});
		}
				
		function computeTitle(d) {
			var html = ""
			
			if (typeof d.claimID !== 'undefined' && typeof d.name !== 'undefined') {
				html += "<a href = '/claim/" + d.claimID + "'><h3>" + d.name + "</h3></a> <br/>";
			}
			
			if (typeof d.description !== 'undefined') {
				html += "<p><h5>Description:</h5> " + (d.description.substring(0,100)+'...') + "</p>"
			}	
			<?php
				if (isset($pageType) && $pageType == "home") {
			  ?>
				if (typeof d.companyID !== 'undefined' && typeof d.company !== 'undefined') {
					html+="<h5>Company: <a href = '/company/" + d.companyID + "'>" + d.company + "</a></h5><br />";
				}
			  <?php
				}
			?>
			
			if (typeof d.userID !== 'undefined' && typeof d.userName !== 'undefined') {
				html += "<h5>Submitted by: <a href = '/profile/" + d.userID + "'>" + d.userName + "</a></h5>";
			}
			return html;
		}
		
		function calculateFontSize(width, height, text) {
				var maxSize = 50;
				var currSize = maxSize;
				var sizingDiv = $("<div></div>");
				$("body").append(sizingDiv);
				sizingDiv.html(text);
		
				sizingDiv.css({"max-width": width + 50, "font-size": maxSize, "display":"inline-block", "visibility":"hidden", "word-spacing":"" });
				
				var trueWidth = sizingDiv.width();
				if (trueWidth>width) {
					currSize = (width/trueWidth)*currSize;
					sizingDiv.css("font-size", currSize);
				}
				
				var trueHeight = sizingDiv.height();
				if (trueHeight>height) {
					currSize = (height/trueHeight)*currSize;
					sizingDiv.css("font-size", currSize);
				}
				/*
				var trueFontSizeHeight = (height/trueHeight)*maxSize;
				sizingDiv.css("font-size", trueFontSizeHeight);
				
				var newTrueWidth = sizingDiv.width();
				var newTrueFontSizeWidth = (width/newTrueWidth)*trueFontSizeHeight;*/
				
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
		
		// function computeTitle(d) {
		// 	var html = "<a href = '<?=base_url()?>claim/" + d.claimID + "'><h3>" + d.name + "</h3></a> <br/>";
		// 	html += "<p><h5>Description:</h5> " + (d.description.substring(0,100)+'...') + "</p>"
		// 	<?php
		// 		if (isset($pageType) && $pageType == "home") {
		// 	  ?>
		// 		html+="<h5>Company: <a href = '<?=base_url()?>company/" + d.companyID + "'>" + d.company + "</a></h5><br />";
		// 	  <?php
		// 		}
		// 	?>
		// 	html += "<h5>Submitted by: <a href = '<?=base_url()?>profile/" + d.userID + "'>" + d.userName + "</a></h5>";
		// 	return html;
		// }

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

		  node = d;
		  d3.event.stopPropagation();
		}
		
		function showTopCompaniesWithClaims(e) {
			$(".active").removeClass("active");
			$(e.target).addClass("active");
			
			$.getJSON("<?=base_url()?>data/topCompaniesWithClaims", function(result){
				//Clear old treemap
				$("#treemapCanvas").empty();

				generateTreemap(result);
			});
		}
		
		function showTopClaims(e) {
			$(".active").removeClass("active");
			$(e.target).addClass("active");
			
			$.getJSON("<?=base_url()?>data/claimsInRange", function(result){
				//Clear old treemap
				$("#treemapCanvas").empty();

				generateTreemap(result);
			});
		}
		
		function showTopCompanies(e) {
			$(".active").removeClass("active");
			$(e.target).addClass("active");
			
			$.getJSON("/data/companiesInRange", function(result){
				//Clear old treemap
				$("#treemapCanvas").empty();

				generateTreemap(result);
			});
		}
	});
</script>
<!-- End treemap -->