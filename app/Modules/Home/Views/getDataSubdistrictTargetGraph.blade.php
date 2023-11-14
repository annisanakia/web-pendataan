<canvas id="chartBySubdistrictTarget" width="100%" height="25"></canvas>

<script src="{{ asset('assets/js/chart.min.js') }}" crossorigin="anonymous"></script>
<script type="text/javascript">
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Bar Chart Example
    var ctx = document.getElementById("chartBySubdistrictTarget");
    var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($subdistrict_names) !!},
        datasets: [{
            label: "Data Pendataan",
            backgroundColor: "rgba(221,121,60,1)",
            borderColor: "rgba(221,121,60,1)",
            data: {!! json_encode($dataBySubdistrict) !!},
        }],
    },
    options: {
        scales: {
        xAxes: [{
            time: {
            unit: 'Kelurahan'
            },
            gridLines: {
            display: false
            },
            ticks: {
            maxTicksLimit: 40
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