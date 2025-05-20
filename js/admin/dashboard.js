$(document).ready(function () {
    const fetchSectionWithMostViolations = (period) => {
        $.ajax({
            url: '../php-api/ReadSectionWithMostViolations.php',
            type: 'GET',
            dataType: 'json',
            data: {period: period},
            success: function(response) {
                $('#mostSectionId').text(response.data.ProgramCode + " - " + response.data.ViolationCount);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    };

    const chartRun = () => {
        $.ajax({
            url: '../php-api/ReadProgramsAndTotalViolations.php',
            method: 'GET',
            dataType: 'json',
            data: { type: 'WithoutUniform,WithoutID', period: $('#periodSelect').val() },
            success: (response) => {
                const responseData_WithoutUniform = response.data?.WithoutUniform || {};
                const responseData_WithoutID = response.data?.WithoutID || {};

                google.charts.setOnLoadCallback(() => {
                    // Chart 1: Students without ID
                    let chartDataID = [['Program Code', 'Total Violations']];
                    let hasIDData = false;

                    for (const programCode in responseData_WithoutID) {
                        if (Object.hasOwnProperty.call(responseData_WithoutID, programCode)) {
                            const program = responseData_WithoutID[programCode];
                            const count = parseInt(program.TotalViolations);
                            if (count > 0) {
                                chartDataID.push([`${programCode} - ${count}`, count]);
                                hasIDData = true;
                            }
                        }
                    }

                    const dataID = google.visualization.arrayToDataTable(chartDataID);
                    const optionsID = {
                        pieHole: 0.5,
                        chartArea: { left: 0, width: '100%', height: '75%' },
                        backgroundColor: 'transparent',
                        pieSliceText: 'value',
                        tooltip: { trigger: 'focus' },
                        legend: { position: 'right', textStyle: { fontSize: 12 } }
                    };

                    const chart1 = new google.visualization.PieChart(document.getElementById('idChart'));
                    if (hasIDData) {
                        chart1.draw(dataID, optionsID);
                    } else {
                        document.getElementById('idChart').innerHTML = '<p class="text-center text-sm text-gray-500">No data available for selected period.</p>';
                    }

                    // Chart 2: Students without Uniform
                    let chartDataUniform = [['Program Code', 'Total Violations']];
                    let hasUniformData = false;

                    for (const programCode in responseData_WithoutUniform) {
                        if (Object.hasOwnProperty.call(responseData_WithoutUniform, programCode)) {
                            const program = responseData_WithoutUniform[programCode];
                            const count = parseInt(program.TotalViolations);
                            if (count > 0) {
                                chartDataUniform.push([`${programCode} - ${count}`, count]);
                                hasUniformData = true;
                            }
                        }
                    }

                    const dataUniform = google.visualization.arrayToDataTable(chartDataUniform);
                    const optionsUniform = {
                        pieHole: 0.5,
                        chartArea: { left: 0, width: '100%', height: '75%' },
                        backgroundColor: 'transparent',
                        pieSliceText: 'value',
                        tooltip: { trigger: 'focus' },
                        legend: { position: 'right', textStyle: { fontSize: 12 } }
                    };

                    const chart2 = new google.visualization.PieChart(document.getElementById('uniformChart'));
                    if (hasUniformData) {
                        chart2.draw(dataUniform, optionsUniform);
                    } else {
                        document.getElementById('uniformChart').innerHTML = '<p class="text-center text-sm text-gray-500">No data available for selected period.</p>';
                    }
                });
            },
            error: (xhr, status, error) => {
                console.error('AJAX Error:', error);
                document.getElementById('idChart').innerHTML = '<p class="text-center text-red-500">Error loading data.</p>';
                document.getElementById('uniformChart').innerHTML = '<p class="text-center text-red-500">Error loading data.</p>';
            }
        });
    };



    chartRun();
    

    const fetchViolationTotals = (period) => {
        $.ajax({
            url: '../php-api/ReadViolationTotals.php',
            type: 'GET',
            dataType: 'json',
            data: {period: period},
            success: function(response) {
                $('#totalWithoutUniform').text(response.data.total_without_uniform);
                $('#totalWithoutId').text(response.data.total_without_id);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    };
    

    $('#periodSelect').on('change', function () {
        const period = this.value;
        $('#textSummary').text(period.charAt(0).toUpperCase() + period.slice(1) + ' Summary');
        fetchSectionWithMostViolations(period);
        fetchViolationTotals(period);
        chartRun();
    });
    
    fetchSectionWithMostViolations($('#periodSelect').val());
    fetchViolationTotals($('#periodSelect').val());

});