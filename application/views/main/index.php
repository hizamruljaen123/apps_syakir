<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Highcharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

    <title>Oil Price Prediction</title>

    <!-- Custom CSS -->
    <style>
        /* Navbar */
        .navbar {
            background-color: #1abc9c; /* Aksen warna pertama */
            color: white;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #16a085; /* Aksen warna kedua */
            padding-top: 56px;
            z-index: 1;
        }

        .sidebar .nav-link {
            color: white;
        }

        .sidebar .nav-link:hover {
            color: #f1c40f; /* Warna ketika dihover */
        }

        /* Main content */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .content h1 {
            color: #1abc9c; /* Warna judul */
        }

        /* Chart container */
        #chartContainer {
            background-color: #f2f2f2; /* Warna latar belakang chart */
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>

<?php include 'sideNav.php' ?>
<!-- Main content -->
<div class="content" style="margin-top: 3%;">
    <div class="container-fluid">

        <h1 class="mt-4">Oil Price Prediction</h1>
        <div id="chartContainer" style="height: 700px;"></div>
        <h1 class="mt-4">Tabel Hasil Prediksi</h1>
        <div id="tableContainer" ></div>
    </div>
</div>

<!-- jQuery first, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/main.js"></script>
<script type="text/javascript">
    // Data historis harga minyak (tanggal dan harga penutupan)
    var historicalData = <?php echo json_encode($historical_data); ?>;

    // Data prediksi harga minyak (tanggal dan harga prediksi)
    var predictions = <?php echo json_encode($predictions); ?>;


    console.log(historicalData)
    function displayData(historicalData, predictions) {
    // Table header
    var tableHtml = "<table class='table'>";
    tableHtml += "<thead><tr><th>Date</th><th>Original High</th><th>Original Low</th><th>Original Close</th><th>Original Open</th><th>Predicted</th><th>Difference (Close)</th><th>Percentage Difference (Close)</th></tr></thead>";
    tableHtml += "<tbody>";

    console.log(historicalData, "OKEY")
    // Display historical data
    for (var i = 0; i < historicalData.length; i++) {
        var date = historicalData[i].Date;
        var originalHigh = historicalData[i].Tertinggi;
        var originalLow = historicalData[i].Terendah;
        var originalClose = historicalData[i].Terakhir;
        var originalOpen = historicalData[i].Pembukaan;

        var predicted = predictions[i].Prediction;

        // Calculate difference and percentage difference for Close
        var closeDifference = predicted - parseFloat(originalClose.replace(',', '.'));
        var closePercentageDifference = ((predicted - parseFloat(originalClose.replace(',', '.'))) / parseFloat(originalClose.replace(',', '.'))) * 100;

        // Determine color based on closeDifference
        var color = 'gray';
        if (closeDifference > 0) {
            color = 'green'; // Jika naik
        } else if (closeDifference < 0) {
            color = 'red'; // Jika turun
        }

        // Add row to the table with color formatting
        tableHtml += "<tr>";
        tableHtml += "<td>" + date + "</td>";
        tableHtml += "<td>" + originalHigh + "</td>";
        tableHtml += "<td>" + originalLow + "</td>";
        tableHtml += "<td>" + originalClose + "</td>";
        tableHtml += "<td>" + originalOpen + "</td>";
        tableHtml += "<td style='color: " + color + "'>" + predicted.toFixed(4) + "</td>";
        tableHtml += "<td style='color: " + color + "'>" + closeDifference.toFixed(2) + "</td>";
        tableHtml += "<td style='color: " + color + "'>" + closePercentageDifference.toFixed(2) + "%</td>";
        tableHtml += "</tr>";
    }

    tableHtml += "</tbody></table>";

    // Display table in the specified container
    document.getElementById("tableContainer").innerHTML = tableHtml;
}

// Function to draw ECharts chart
            function drawChart(historicalData, predictions) {
            var chartDom = document.getElementById('chartContainer');
            var myChart = echarts.init(chartDom);
            var option;

            // Convert data to ECharts format
            var candleData = historicalData.map((item, index) => {
                var date = new Date(item.Date).getTime();
                var open = parseFloat(item.Pembukaan);
                var high = parseFloat(item.Tertinggi);
                var low = parseFloat(item.Terendah);
                var close = parseFloat(item.Terakhir);

                // Determine the color based on the close price compared to the previous day's close price
                var color = (index === 0 || close >= parseFloat(historicalData[index - 1].Terakhir)) ? '#00da3c' : '#ec0000';

                return {
                    value: [date, open, high, low, close],
                    itemStyle: {
                        color: color,
                        color0: color,
                        borderColor: color,
                        borderColor0: color
                    }
                };
            });

            var lineData = predictions.map(item => [
                new Date(item.Date).getTime(),
                item.Prediction
            ]);

            // Define the option for the chart
            option = {
                title: {
                    text: 'Grafik Harga Historis dan Prediksi',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross'
                    }
                },
                xAxis: {
                    type: 'time',
                    axisLabel: {
                        formatter: function (value) {
                            return echarts.format.formatTime('dd MMM yyyy', value);
                        }
                    }
                },
                yAxis: [{
                    scale: true,
                    splitArea: {
                        show: true
                    }
                }],
                dataZoom: [
                    {
                        type: 'inside',
                        start: 80,
                        end: 100
                    },
                    {
                        show: true,
                        type: 'slider',
                        top: '90%',
                        start: 70,
                        end: 100
                    }
                ],
                brush: {
                    xAxisIndex: 'all',
                    brushLink: 'all',
                    outOfBrush: {
                        colorAlpha: 0.1
                    }
                },
                series: [
                    {
                        name: 'Harga Historis',
                        type: 'candlestick',
                        data: candleData,
                        itemStyle: {
                            color: '#ec0000',
                            color0: '#00da3c',
                            borderColor: '#8A0000',
                            borderColor0: '#008F28'
                        }
                    },
                    {
                        name: 'Prediksi',
                        type: 'line',
                        data: lineData,
                        smooth: true,
                        symbol: 'none', // Hide the symbols on the plot
                        lineStyle: {
                            color: '#0000FF'
                        }
                    }
                ]
            };

            // Use the option to show the chart
            option && myChart.setOption(option);
        }
    // Memanggil fungsi drawChart dengan data historis dan prediksi sebagai parameter
    drawChart(historicalData, predictions);
    displayData(historicalData, predictions);

</script>
</body>
</html>
