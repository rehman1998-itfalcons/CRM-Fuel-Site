function supplierInital() {
    var options = {
        series:
            [
                {
                    name: "Supplier Sales",
                    data: [
                        <?php
                        foreach ($companies as $company => $liter) {
                        ?>
                        {{ $liter }},
                        <?php
                        }
                        ?>
                    ]
                },
                {
                    name: "Supplier Purchases",
                    data: [
                        <?php
                        foreach ($purchases_companies as $company => $liter) {
                        ?>
                        {{ $liter }},
                        <?php
                        }
                        ?>
                    ]
                }
            ],
        chart: {
            type: 'bar',
            height: 550,
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    if(config.seriesIndex == 1)
                        var series = 'purchases';
                    else
                        var series = 'sales';
                    var company = config.w.config.xaxis.categories[config.dataPointIndex];
                    var url = '{{ url('/supplier-company-report') }}/'+series+'/'+company;
                    window.open(url);
                }
            },
        }, plotOptions: {
            bar: {
                horizontal: true,
            }
        }, dataLabels: {
            enabled: false
        },                 xaxis: {
            categories: [
                <?php
                    foreach ($companies as $company => $liter){
                    ?>
                    '{{ $company }}',
                <?php
                }
                ?>
            ],
        },
        colors: ['#008ffb', '#FEB019'],
    }

    var chart = new ApexCharts(
        document.querySelector("#s_companies"),
        options
    );
    chart.render();
}
