<template>
    <div class="wrap">
        <div :id="attrs.canvasId" :style="chartStyle"></div>
    </div>
</template>
<script>
    import echarts from 'echarts'

    export default {
        props: {
            attrs: Object
        },
        data() {
            return {
                chartStyle: {
                    width: this.attrs.data.width || '100%',
                    height: this.attrs.data.height || '100%'
                },
                myChart:null
            };
        },
        mounted() {
            this.myChart = echarts.init(document.getElementById(this.attrs.canvasId))
            this.chart_data(this.attrs.data);
        },
        methods: {
            chart_data(attr_data) {
                let chartConfig = attr_data
                let chartData = {
                    grid: {
                        top: '30',
                        left: '20',
                        right: '30',
                        bottom: 20,
                        containLabel: true
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    color: chartConfig.color,
                    legend: chartConfig.legend,
                    xAxis: {
                        data: chartConfig.xAxisData || []
                    },
                    yAxis: {
                        type: 'value',
                        axisLine: {
                            lineStyle: {
                                color: '#EEEEEE'
                            }
                        },
                        axisLabel: {
                            formatter: '{value}',
                            color: '#3E3E3E'
                        },
                        min: function (value) {
                            return (value.min > 10) ? (value.min - 10) : 0;
                        },
                        splitLine: {
                            show: false
                        }
                    },
                    visualMap: chartConfig.visualMap,
                    series: chartConfig.series,
                    dataZoom: chartConfig.dataZoom,
                }
                this.myChart.setOption(chartData)
            }
        },
        methods: {
            chart_data(attr_data) {

            }
        }
    };
</script>
<style lang="scss">
    .wrap {
        height: 100%;
        width: 100%;
    }
</style>
