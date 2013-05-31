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
    if (dates.length < 2) {
        $('#chartContainer').hide();
    }
    $('#chartContainer').highcharts({
        chart: {
            zoomType: 'x',
            spacingRight: 20
        },
        title: {
            text: 'Score history for ' + thisThing
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' :
                'Drag your finger over the plot to zoom in'
        },
        xAxis: {
            title: {
                text: 'Timestamps'
            },
            categories: dates
        },
        yAxis: {
            title: {
                text: 'Rating'
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
            type: 'area',
            name: 'Score',
            //pointInterval: 24 * 3600 * 1000, // used to rescale when there are more data points
            data: values
        }]
    });
});
</script>

<div id="chartContainer" style="min-width: 400px; height: 200px; margin: 0 auto"></div>