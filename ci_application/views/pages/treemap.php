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
			if (isset($topCompaniesWithClaimsJSON)) {
			?>
				var treemapHeight = $(window).height()+100 - $("#treemapCanvas").offset().top - $("footer").height() - 40;
				var treemapWidth = $(window).width() - 60;
			<?php
			} else if (isset($topClaimsForCompanyJSON) || isset($topClaimsWithTagJSON)) {
			?>
				var treemapHeight = $(window).height() - $("#treemapCanvas").offset().top - $("footer").height() - 40;
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
			   .range([colors[0], colors[1], colors[2], colors[3], colors[4], colors[5], colors[6]]),
			/*borderColor = d3.scale.linear()
				.domain([-3, 3])
			   .range(['#000', '#FFF']),*/
			borderColor = d3.scale.category10(),
			borderWidth = 5,
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
		  .append("svg:g");
		var jsonDataObj = {<?php
			if (isset($topCompaniesWithClaimsJSON)) {
				echo($topCompaniesWithClaimsJSON);
			} else if (isset($topClaimsForCompanyJSON)) {
				echo($topClaimsForCompanyJSON);
			} else if (isset($topClaimsWithTagJSON)) {
				echo($topClaimsWithTagJSON);
			}
			
		//TODO: Fix this mess
		?>};
		console.log("jsonDataObj is: " + jsonDataObj);

		var data = jsonDataObj;
		node = root = data;

		var nodes = treemap.nodes(root)
		  .filter(function(d) { return !d.children; });

		var cell = svg.selectAll("g")
		  .data(nodes)
		  .enter().append("svg:g")
		  .attr("class", "cell")
		  .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
		  .attr("stroke", function (d) {return borderColor(d.parent.name);})
		  .attr("stroke-width", function (d) {return borderWidth;})
		  .on("click", function(d) { return zoom(node == d.parent ? root : d.parent); })
		  .on("mouseover", function (d) {d3.select(this).attr("fill", "red");});
		
		cell.append("svg:rect")
		  .attr("width", function(d) { return d.dx - borderWidth; })
		  .attr("height", function(d) { return d.dy - borderWidth; })
		  .style("fill", function(d) { return bgColor(d.score);});

		//Text labels for treemap
		cell.append("foreignObject")
		  .attr("x", function(d) { return padding - 5;})
		  .attr("y", function(d) { return padding})
		  .on("click", function(d) {window.location.href = "http://" + domain + "/claim/" + d.claimID;})
		  .attr("width", function(d) {return d.dx - padding})
		  .attr("height", function(d) {return d.dy - padding})
		  .append("xhtml:div")
			  .attr("dy", ".35em")
			  .html(function(d) {return d.name})
			  .style("font-size", function(d) {return (d.dx*d.dy)/10000 + "px";})
			  //.style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; })
			  .style("width", function(d) {return d.dx - padding})
			  .style("height", function(d) {return d.dy - padding});
		 
		//add as many text elements as required to display the full, wrapped text. 

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
			  .attr("width", function(d) { return kx * d.dx - 1; })
			  .attr("height", function(d) { return ky * d.dy - 1; })

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