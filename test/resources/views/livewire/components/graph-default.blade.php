{{-- <div class="max-w-screen-md mx-auto sm:rounded-lg bg-white border shadow-lg rounded-lg">
    <div id="line-graph" class="w-full graph-container">
        <canvas id="defaultChart" height="100" style="max-width: 100%;"></canvas>
    </div>
</div> --}}
<div></div>

<script>
    // assign the graph by getting the id of the html element tag
    var ctx = document.getElementById("defaultChart{{ $location }}").getContext("2d");
    var batanganGraph{{ $location }};

    // method for giving the value to the chart.
    function updateChart{{ $location }}() {
        var place = "<?php echo $location; ?>";
        var batanganData = @json($waterLevels);
        var batanganWaterLevels{{ $location }} = batanganData.map(entry => entry.water_level);
        var batanganLabels = batanganData.map(entry => entry.time);

        console.log("batanganWaterLevels",batanganWaterLevels{{ $location }});

        if (batanganGraph{{ $location }}) {
            batanganGraph{{ $location }}.destroy();
        }

        // chart.js properties
        batanganGraph{{ $location }} = new Chart(ctx, {
            type: "line",
            data: {
                labels: batanganLabels,
                datasets: [{
                    label: place + " - Water Level",
                    data: batanganWaterLevels{{ $location }},
                    borderColor: "blue",
                    borderWidth: 2,
                    pointBackgroundColor: "blue",
                    pointRadius: 5,
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: 4,
                        suggestedMax: 7,
                        stepSize: 1,
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
                maintainAspectRatio: false,
            },
        });
    }

    updateChart{{$location}}();
</script>
