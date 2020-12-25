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
                title: {
                    text: chartConfig.title.text,
                    subtext: chartConfig.subtitle.text,
                    x: 'center',
                    y: 'center',
                    textStyle: {
                        fontWeight: '500',
                        fontSize: chartConfig.title.fontSize || 20,
                        color: chartConfig.title.color || '#3E3E3E'
                    }, // 标题
                    subtextStyle: {
                        fontWeight: 'normal',
                        fontSize: chartConfig.subtitle.color || 30,
                        color: chartConfig.subtitle.color || '#3E3E3E'
                    } // 副标题
                },
                angleAxis: {
                    max: 100,
                    clockwise: true, // 逆时针
                    // 隐藏刻度线
                    axisLine: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        show: false
                    },
                    splitLine: {
                        show: false
                    }
                },
                radiusAxis: {
                    type: 'category',
                    // 隐藏刻度线
                    axisLine: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        show: false
                    },
                    splitLine: {
                        show: false
                    }
                },
                polar: {
                    center: ['50%', '50%'],
                    radius: '170%' // 图形大小
                },
                series: [{
                    type: 'bar',
                    data: [{
                        name: '',
                        value: chartConfig.series.data,
                        itemStyle: {
                            normal: {
                                color: {
                                    type: 'linear',
                                    x: 0,
                                    y: 1,
                                    x2: 0,
                                    y2: 0,
                                    colorStops: [
                                        {
                                            offset: 0.2,
                                            color: chartConfig.series.color[0]
                                        },
                                        {
                                            offset: 1,
                                            color: chartConfig.series.color[1]
                                        }
                                    ],
                                    globalCoord: true
                                }
                            }
                        }
                    }],
                    coordinateSystem: 'polar',
                    roundCap: true,
                    barWidth: 25,
                    barGap: '-100%', // 两环重叠
                    radius: ['49%', '53%'],
                    z: 2
                }, { // 灰色环
                    type: 'bar',
                    data: [{
                        value: 100,
                        itemStyle: {
                            normal: {
                                color: {
                                    type: 'linear',
                                    colorStops: [
                                        {
                                            offset: 0,
                                            color: '#F5F7FA'
                                        },
                                        {
                                            offset: 1,
                                            color: '#F5F7FA'
                                        }
                                    ],
                                    globalCoord: true
                                }
                            }
                        }
                    }],
                    coordinateSystem: 'polar',
                    roundCap: true,
                    barWidth: 25,
                    barGap: '-110%', // 两环重叠
                    radius: ['48%', '52%'],
                    z: 1
                }]
            }
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
