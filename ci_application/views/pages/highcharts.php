<?php
$string = '';
if ($pageType == 'claim') {
    $string .= 'this claim';
} else {
    $string .= $companyInfo['Name'];
}
?>

<script type="text/javascript">
var thisThing = "<?=$string?>";
var jsonDataObj = {<?php echo($scoreHistory)?>};
var dates = new Array();
var values = new Array();
for (var i in jsonDataObj['scores']) {
    dates.push(jsonDataObj['scores'][i]['date']);
    values.push(jsonDataObj['scores'][i]['value']);
}
$(function () {
    if (dates.length < 1) {
        $('#chartContainer').hide();
        $('#relatedTagsContainer').text('This claim is lonely without more ratings. Give it a score on the left!');
    }
    $('#chartContainer').highcharts({
        chart: {
            type: 'area',
            zoomType: 'x',
            spacingRight: 10
        },
        credits: {
            enabled: false
        },
        title: {
            text: ''
        },
        // subtitle: {
        //     text: document.ontouchstart === undefined ?
        //         'Click and drag in the plot area to zoom in' :
        //         'Drag your finger over the plot to zoom in'
        // },
        xAxis: {
            categories: dates
        },
        yAxis: {
            title: {
                text: 'Avg Rating'
            }
        },
        tooltip: {
            shared: true
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                lineWidth: 1,
                marker: {
                    enabled: false
                },
                shadow: false,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            name: 'Daily Avg',
            pointInterval: 1,
            data: values
        }]
    });
});
</script>

<div id="chartContainer" style="min-width: 400px; height: 100px; margin: 0 auto"></div>