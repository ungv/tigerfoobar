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
        // Claims will always have at least one rating, but new companies might not have any claims
        $('#relatedTagsContainer').text('This company is lonely without more claims. Submit a new claim with the "+" at the top!');
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
                        [0, '#31b373'],
                        [1, '#FFFFFF']
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