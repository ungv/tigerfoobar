<script type="text/javascript">
var companyName = '<?=$claimInfo['CoName']?>';
var dateTime = '<?=$scoreHistory[0]['Time']?>'.split(' ');
var datePieces = dateTime[0].split('-');
var timePieces = dateTime[1].split(':');
console.log(datePieces + ", " + timePieces);
$(function () {
    $('#container').highcharts({
        chart: {
            zoomType: 'x',
            spacingRight: 20
        },
        title: {
            text: 'Score history of ' + companyName
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' :
                'Drag your finger over the plot to zoom in'
        },
        xAxis: {
            categories: 
        },
        yAxis: {
            title: {
                text: 'Score'
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
            name: 'Rating',
            pointInterval: 24 * 3600 * 1000,
            pointStart: Date.UTC(datePieces[0], datePieces[1]-1, datePieces[2]),
            data: [
                ['jan', 0.6804], ['feb', 0.6781], ['feb', 0.6756]           
            ]
        }]
    });
});
</script>

<div id="container" style="min-width: 400px; height: 200px; margin: 0 auto"></div>