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
                        left: '25',
                        right: '25',
                        bottom: '30',
                        containLabel: true
                    },
                    tooltip: {
                        show:true,
                        trigger:'axis',
                        axisPointer:{
                            type:'shadow'

                        }
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            saveAsImage: {show: true}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        axisLine: {
                            lineStyle: {
                                color: '#626770'
                            }
                        },
                        axisLabel: {
                            interval: 0,
                            rotate: 20
                        },
                        data: chartConfig.xAxisData || []
                    },
                    yAxis: {
                        type: 'value',
                        name: chartConfig.yAxisName || '',
                        min: 0,
                        axisLine: {
                            lineStyle: {
                                color: '#626770'
                            }
                        },
                        axisLabel: {
                            formatter: '{value}' + chartConfig.unit || '',
                            color: '#626770'
                        },
                        splitLine: {
                            show: false
                        }
                    },
                    series: [],
                    dataZoom: chartConfig.dataZoom,
                }
                this.attrs.data.series.forEach((item, index) => {
                    let itemStyle
                    if (item.type === 'bar') {
                        itemStyle = {
                            barBorderRadius: [4, 4, 0, 0],
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                offset: 0,
                                color: item.color
                            }, {
                                offset: 1,
                                color: item.colorEnd
                            }])
                        }
                    }
                    chartData.series.push({
                        name: item.name,
                        type: item.type,
                        barWidth: 20,
                        itemStyle: {
                            normal: itemStyle
                        },
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: item.data
                    })
                    if(chartConfig.max){
                        chartData.yAxis.max = chartConfig.max;
                    }
                })
                this.myChart.setOption(chartData)
            }
        },
        updated() {
            // this.antv.changeData(this.attrs.data);
        },
        destroyed() {
            //this.antv.destory();
        }
    };
</script>
<style lang="scss">
    .wrap {
        height: 100%;
        width: 100%;
    }
</style>
