<canvas id="myBarChart" width="100%" height="40"></canvas>

<script type="text/javascript">
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Bar Chart Example
    var ctx = document.getElementById("myBarChart");
    var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($coordinators) !!},
        datasets: [{
            label: "Rekap Koordinator",
            backgroundColor: "rgba(2,117,216,1)",
            borderColor: "rgba(2,117,216,1)",
            data: {!! json_encode($dataByCoor) !!},
        }],
    },
    options: {
        scales: {
        xAxes: [{
            time: {
            unit: 'month'
            },
            gridLines: {
            display: false
            },
            ticks: {
            maxTicksLimit: 10
            }
        }],
        yAxes: [{
            ticks: {
            min: 0,
            maxTicksLimit: 5
            },
            gridLines: {
            display: true
            }
        }],
        },
        legend: {
        display: false
        }
    }
    });
</script>