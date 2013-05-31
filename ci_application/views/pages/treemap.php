<?php
	/*TODO (5/17/13):
	* -Make the SVG text bring users to the relevant claim page on click
	* -Create on-hover tooltips that show more detail and slightly change the color of hovered cells to indicate
	* that the cell is clickable
	* -Limit the number of claims per top company returned in the data model
	*/
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
				var treemapHeight = $(window).height()+100 - $("#treemapCanvas").offset().top - $("footer").height() - 40;
				var treemapWidth = $(window).width() - 60;
			<?php
			} else if (isset($pageType) && ($pageType == "company" || $pageType == "tag" || $pageType == "profile")) {
			?>
				var treemapHeight = 500;
				var treemapWidth = $("#main").width();
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

		  
		//$(cell).tipsy();
		
		claimCell.append("svg:rect")
		  .attr("width", function(d) { return d.dx - borderWidth;})
		  .attr("height", function(d) { return d.dy - borderWidth;})
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
		  .style("pointer-events", "none")
		  .append("xhtml:div")
			  .attr("dy", ".35em")
			  .html(function(d) {return d.name})
			  .style("font-size", function(d) {return calculateFontSize(d.dx, d.dy);})
			  //.style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; })
			  .style("width", function(d) {return d.dx - padding})
			  .style("height", function(d) {return d.dy - padding})
			   .style("pointer-events", "auto")
			  .on("click", function(d) {window.location.href = "http://" + domain + "/claim/" + d.claimID;});
			  
		function calculateFontSize(width, height) {
			return (width*height)/6000 + "px";
		}
		 
		 //Tooltips
		$("svg rect, svg div, svg foreignObject").tipsy({ 
			gravity: "n",
			html: true, 
			title: function() {
			  var d = this.__data__; // c = colors(d.i);
			  var html = "<h3>" + d.name + "</h3> <br/>";
			  
			  <?php
				if (isset($pageType) && $pageType == "home") {
			  ?>
			  html+="<h4> " + d.company + "</h4>";
			  <?php
				}
			  ?>
			  //Todo: pass this html value to the "mouseenter" function below so that tooltips can be scaled properly before being displayed.
			  //One way to possibly do this is bind it to the SVG element above and read it from d
			  return html;
			},
			opacity: "1.0"})
			.mouseenter(function(e) {
				var d = this.__data__;
			
				var scrollTop = $(window).scrollTop();
				var scrollLeft = $(window).scrollLeft();
				var target = e.target;
				var targetCell = $(target).closest(".cell")[0];
				var targetCellRect = targetCell.getBoundingClientRect();

				//console.log(target + " top offset of targetCell is: " + $(targetCell).offset().top + " and left offset of targetCell is " + $(targetCell).offset().left);
				//console.log("Height of targetcell is: " + targetCellRect.height + " and width of targetCell is: " + targetCellRect.width);
				
				var top = targetCellRect.top + targetCellRect.height + scrollTop;
				var left = targetCellRect.left + targetCellRect.width/2 - $(".tipsy").width()/2 + scrollLeft;
				var rotation = 0;
				
				if (top + $(".tipsy").height() > ($(window).height() - 50)) {
					top = targetCellRect.top - $(".tipsy").height() - 10 + scrollTop;
					rotation = 180;
				}
				
				$(".tipsy").css({
					"top": top + "px",
					"left": left + "px",
					"transform" : "rotate(" + rotation +"deg)"
				});
				
				$(".tipsy-inner").css({
					"transform" : "rotate(" + rotation +"deg)"
				});
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