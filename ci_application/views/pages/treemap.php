<?php
	/*TODO (5/17/13):
	* -Make the SVG text bring users to the relevant claim page on click
	* -Create on-hover tooltips that show more detail and slightly change the color of hovered cells to indicate
	* that the cell is clickable
	* -Limit the number of claims per top company returned in the data model
	*/
?>
<!-- Start treemap -->
<div id = "treemapCanvas" class = "main">
</div>

<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script type="text/javascript">
	var treemapHeight = $(window).height() - $("#treemapCanvas").offset().top - 40;
	var w = $(".main").width(),
		h = treemapHeight,
		x = d3.scale.linear().range([0, w]),
		y = d3.scale.linear().range([0, h]),
		color = d3.scale.ordinal()
		   .range(['#00AF64', '#FF7640', '#36D792', '#61D7A4', '#FF4900', '#FEF5CA','#FF9B73']),
		//color = d3.scale.category20c(),
		root,
		node;
	
	var treemap = d3.layout.treemap()
		.round(false)
		.size([w, h])
		.sticky(true)
		.value(function(d) { return d.size; });

	var svg = d3.select("#treemapCanvas")
		.attr("class", "chart main")
		.style("width", w + "px")
		.style("height", h + "px")
	  .append("svg:svg")
		.attr("width", w)
		.attr("height", h)
	  .append("svg:g")
		.attr("transform", "translate(.5,.5)");
	var jsonDataObj = {"name": "Top companies with claims", "children": [
	
	<?php 
		//Builds JSON out of the data in the $data array
	
		$currCompany = "";
		$companiesWithClaims = '';
		for ($i = 0; $i < count($topCompanies); $i++) {
			//foreach($topClaims as $topClaim) {

			if ($topCompanies[$i]["Name"] != $currCompany) {
				$currCompany = $topCompanies[$i]["Name"];
				$companiesWithClaims .= '{"name": "' . $topCompanies[$i]["Name"] . '", "children": [';
			}
			
			$claims = '';
			
			while (($i < count($topCompanies)) && $topCompanies[$i]["Name"] == $currCompany) {
				$name = str_replace("'","", $topCompanies[$i]["Title"]);
				$size = str_replace("'","", $topCompanies[$i]["numScores"]);
				$claims .= '{"name" : "' . $name . '", "size" : ' . $size . '},';
				$i++;
			} 
			
			$claims = rtrim($claims, ",");
			$companiesWithClaims .= $claims;
			$companiesWithClaims .= "]},";
			
			$i--;
		}
		
		$companiesWithClaims = rtrim($companiesWithClaims, ",");
		echo($companiesWithClaims);
	?>]};
	console.log(jsonDataObj);
	var data = jsonDataObj;
	node = root = data;

	var nodes = treemap.nodes(root)
	  .filter(function(d) { return !d.children; });

	var cell = svg.selectAll("g")
	  .data(nodes)
	.enter().append("svg:g")
	  .attr("class", "cell")
	  .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
	  .on("click", function(d) { return zoom(node == d.parent ? root : d.parent); });

	cell.append("svg:rect")
	  .attr("width", function(d) { return d.dx - 1; })
	  .attr("height", function(d) { return d.dy - 1; })
	  .style("fill", function(d) { return color(d.parent.name); });

	cell.append("svg:text")
	  .attr("x", function(d) { return d.dx / 2; })
	  .attr("y", function(d) { return d.dy / 2; })
	  .attr("dy", ".35em")
	  .attr("text-anchor", "middle")
	  .text(function(d) { return d.name; })
	  .style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; });

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
		  .attr("x", function(d) { return kx * d.dx / 2; })
		  .attr("y", function(d) { return ky * d.dy / 2; })
		  .style("opacity", function(d) { return kx * d.dx > d.w ? 1 : 0; });

	  node = d;
	  d3.event.stopPropagation();
	}
</script>
<!-- End treemap -->