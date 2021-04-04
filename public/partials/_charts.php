<?php
/*
 * Created on Sun Apr 04 2021
 *
 * The MIT License (MIT)
 * Copyright (c) 2021 MartDevelopers Inc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<script>
    /* User Login Activity Chart */
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Hourly Average CPU Utilization"
            },
            axisX: {
                title: "Time"
            },
            axisY: {
                title: "Percentage",
                suffix: "%"
            },
            data: [{
                type: "line",
                name: "CPU Utilization",
                connectNullData: true,
                //nullDataLineDashType: "solid",
                xValueType: "dateTime",
                xValueFormatString: "DD MMM hh:mm TT",
                yValueFormatString: "#,##0.##'%'",
                dataPoints: [{
                        x: 1501048673000,
                        y: 35.939
                    },
                    {
                        x: 1501052273000,
                        y: 40.896
                    },
                    {
                        x: 1501055873000,
                        y: 56.625
                    },
                    {
                        x: 1501059473000,
                        y: 26.003
                    },
                    {
                        x: 1501063073000,
                        y: 20.376
                    },
                    {
                        x: 1501066673000,
                        y: 19.774
                    },
                    {
                        x: 1501070273000,
                        y: 23.508
                    },
                    {
                        x: 1501073873000,
                        y: 18.577
                    },
                    {
                        x: 1501077473000,
                        y: 15.918
                    },
                    {
                        x: 1501081073000,
                        y: null
                    }, // Null Data
                    {
                        x: 1501084673000,
                        y: 10.314
                    },
                    {
                        x: 1501088273000,
                        y: 10.574
                    },
                    {
                        x: 1501091873000,
                        y: 14.422
                    },
                    {
                        x: 1501095473000,
                        y: 18.576
                    },
                    {
                        x: 1501099073000,
                        y: 22.342
                    },
                    {
                        x: 1501102673000,
                        y: 22.836
                    },
                    {
                        x: 1501106273000,
                        y: 23.220
                    },
                    {
                        x: 1501109873000,
                        y: 23.594
                    },
                    {
                        x: 1501113473000,
                        y: 24.596
                    },
                    {
                        x: 1501117073000,
                        y: 31.947
                    },
                    {
                        x: 1501120673000,
                        y: 31.142
                    }
                ]
            }]
        });
        chart.render();

    }
</script>