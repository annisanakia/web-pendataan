<canvas id="chartByToday" width="100%" height="25"></canvas>

<script src="{{ asset('assets/js/chart.min.js') }}" crossorigin="anonymous"></script>
<script type="text/javascript">
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Bar Chart Example
    var ctx = document.getElementById("chartByToday");
    var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($district_names) !!},
        datasets: [
        {
            label: "Data Hari Ini",
            backgroundColor: "rgba(152,85,194,1)",
            borderColor: "rgba(152,85,194,1)",
            data: {!! json_encode($dataByToday) !!},
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
            maxTicksLimit: 6
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