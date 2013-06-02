<?php
	/* Todo
	* -Limit the number of claims per top company returned in the data model
	*/
?>
<!-- Start treemap -->
<div id = "treemapCanvas" class = "full">
</div>

<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script type="text/javascript">
	$(function () {
		var mouseX, mouseY;
		$(document).mousemove(function(e) {
			mouseX = e.clientX;
			mouseY = e.clientY;
		}).mouseover();
		
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
		var w = treemapWidth,
			h = treemapHeight,
			x = d3.scale.linear().range([0, w]),
			y = d3.scale.linear().range([0, h]),
			bgColor = d3.scale.quantile()
			   .domain([-3, -2, -1, 0, 1, 2, 3])
			   .range(['#FF4900', '#FF7640', '#FF9B73', '#FEF5CA', '#61D7A4', '#36D792', '#00AF64']),
			borderColor = d3.scale.category10(),
			borderWidth = 1,
			domain = document.domain,
			padding = 20,
			root,
			node;
		
		var treemap = d3.layout.treemap()
			.round(true)
			.size([w, h])
			.sticky(true)
			.value(function(d) { return d.size; });

		var svg = d3.select("#treemapCanvas")
			.attr("class", "chart")
			.style("width", w + "px")
			.style("height", h + "px")
		  .append("svg:svg")
			.attr("width", w)
			.attr("height", h)
		  .append("svg:g")
			.attr("class", "outerG");
		  
		var jsonDataObj = {<?php
			echo($treemapJSON);
		?>};
		console.log(jsonDataObj);

		var data = jsonDataObj;
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
		  .attr("width", function(d) { return d.dx - borderWidth;})
		  .attr("height", function(d) { return d.dy - borderWidth;})
		  .attr("title", function(d) {return computeTitle(d);})
		  .style("fill", function(d) { return bgColor(d.score);})
		  .on("mouseover", function (d) {d3.select(this).style("fill-opacity", "0.75");})
		  .on("mouseout", function (d) {d3.select(this).style("fill-opacity", "1.0");})
		  .on("click", function(d) { return zoom(node == d.parent ? root : d.parent); });
		 
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
			  //.attr("title", function(d) {return computeTitle(d);})
			  .html(function(d) {return ((d.name.length < 100) ? d.name : (d.name.substring(0,100)+'...'));})
			  .style("font-size", function(d) {return calculateFontSize(d.dx - padding, d.dy - padding, $(this).html());})
			  .style("width", function(d) {return d.dx - padding})
			  .style("height", function(d) {return d.dy - padding})
			  .style("cursor", "pointer")
			  .style("pointer-events", "auto")
			  .on("mouseover", function (d) {$(this).parent().siblings("rect").css("fill-opacity", "0.75");})
			  .on("mouseout", function (d) {$(this).parent().siblings("rect").css("fill-opacity", "1.0");})
			  .on("click", function(d) {window.location.href = "/claim/" + d.claimID;});
			  
		function calculateFontSize(width, height, text) {
			var maxSize = 50;
			var sizingDiv = $("<div></div>");
			sizingDiv.css({"height": height, "font-size": "50px", "display":"inline-block", "visibility":"hidden"});
			sizingDiv.html(text);
			$("body").append(sizingDiv);
			
			var trueWidth = sizingDiv.width();
			var trueFontSize = width/trueWidth*maxSize;
			return trueFontSize + "px";//(width*height)/4000 + "px";
		}
		
		function computeTitle(d) {
			var html = "<a href = '/claim/" + d.claimID + "'><h3>" + d.name + "</h3></a> <br/>";
			html += "<p><h5>Description:</h5> " + (d.description.substring(0,100)+'...') + "</p>"
			<?php
				if (isset($pageType) && $pageType == "home") {
			  ?>
				html+="<h5>Company: <a href = '/company/" + d.companyID + "'>" + d.company + "</a></h5>";
			  <?php
				}
			?>
			html += "<h5>Submitted by: <a href = '/profile/" + d.userID + "'>" + d.userName + "</a></h5>";
			return html;
		}
		
		//Tooltips
		$("svg rect").tooltipster({
			trigger: 'hover',
			interactive: true,
			interactiveTolerance: 10000,
			onlyOne: true,
			maxWidth: 400,
			functionReady: function(origin, tooltip) {
				var scrollTop = $(window).scrollTop();
				var scrollLeft = $(window).scrollLeft();
				var target = origin;
				var targetCell = $(target).closest(".cell")[0];
				var targetCellRect = targetCell.getBoundingClientRect();
				
				var top = targetCellRect.top + targetCellRect.height + scrollTop;
				var left = targetCellRect.left + targetCellRect.width/2 - $(tooltip).width()/2 + scrollLeft;
				
				//var top = mouseX;
				//var left = mouseY;
				
				var rotation = 0;
				
				if (top + $(tooltip).height() > ($(window).height() - 50)) {
					top = targetCellRect.top - $(tooltip).height() - 10 + scrollTop;
					rotation = 180;
				}
				
				$(tooltip).css({
					"top": top + "px",
					"left": left + "px",
					"transform" : "rotate(" + rotation +"deg)"
				});
				
				$(".tooltipster-content").css({
					"transform" : "rotate(" + rotation +"deg)"
				});
				$($(".tooltipster-base")[0]).show();
			},
			position: 'bottom'
		});

			
		<?php if (isset($pageType) && $pageType == "home") {
		?>
		//Add company borders
		var companyNodes = treemap.nodes(root)
		.filter(function (d) {return d.children});

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
			.style("fill-opacity", function(d) { return "0.0";}) //Fix
			.style("stroke", function(d) {return "white";})
			.style("stroke-width", function(d) {return "5";})
			.style("pointer-events", "none");
		$(".companyCellContainer>g:nth-child(1)").detach();
		//End company borders
		<?php
		}
		?>
		
		d3.select(window).on("click", function() { zoom(root); });
		d3.select("select").on("change", function() {
			treemap.value(this.value == "size" ? size : count).nodes(root);
			zoom(node);
		});

		function size(d) {
		  return d.size;
		}

		function count(d) {
		  return 1;
		}

		function zoom(d) {
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
	});
</script>
<!-- End treemap -->