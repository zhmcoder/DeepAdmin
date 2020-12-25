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
                }
            };
        },
        mounted() {
            let myChart = echarts.init(document.getElementById(this.attrs.canvasId))
            let chartConfig = this.attrs.data
            let chartData = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross',
                        crossStyle: {
                            color: '#999'
                        }
                    },
                    formatter: '{a0}: {c0}<br />{a1}: {c1}%<br />{a2}: {c2}%'
                },
                grid: {
                    // top: '20', // 距上边距
                    left: '20', // 距离左边距
                    right: '20', // 距离右边距
                    bottom: '10%', // 距离下边距
                    containLabel: true
                },
                legend: {
                    x: 'center',
                    y: 'bottom',
                    padding: [0, 50, 0, 0],
                    data: chartConfig.legend.data || [],
                    itemWidth: 16,
                    itemHeight: 16,
                    textStyle: {
                        color: '#333333',
                        fontSize: 12
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
                yAxis: [
                    {
                        type: 'value',
                        name: chartConfig.yAxisName || '',
                        min: 0,
                        axisLine: {
                            lineStyle: {
                                color: '#626770'
                            }
                        },
                        axisLabel: {
                            formatter: '{value}',
                            color: '#626770'
                        },
                        splitLine: {
                            show: false
                        }
                    },
                    {
                        type: 'value',
                        min: 0,
                        axisLine: {
                            lineStyle: {
                                color: '#626770'
                            }
                        },
                        axisLabel: {
                            formatter: '{value}%',
                            color: '#626770'
                        },
                        splitLine: {
                            show: false
                        }
                    }
                ],
                series: []
            }
            this.attrs.data.series.forEach((item, index) => {
                let itemStyle
                if (item.type === 'bar') {
                    itemStyle = {
                        barBorderRadius: [20, 20, 0, 0],
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: item.color
                        }, {
                            offset: 1,
                            color: item.colorEnd
                        }])
                    }
                } else if (item.type === 'line') {
                    itemStyle = {
                        color: '#67E5F0'
                    }
                }
                chartData.series.push({
                    name: item.name,
                    type: item.type,
                    barWidth: 20,
                    itemStyle: {
                        normal: itemStyle
                    },
                    yAxisIndex: item.type === 'line' ? 1 : 0,
                    data: item.data
                })
            })
            myChart.setOption(chartData)
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
