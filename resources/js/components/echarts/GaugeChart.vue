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
                series: [
                    {
                        type: 'gauge',
                        radius: '98%',
                        splitNumber: 3,
                        axisLine: {
                            lineStyle: {
                                color: [[0.333, chartConfig.series.areaColor[0]], [0.666, chartConfig.series.areaColor[1]], [1, chartConfig.series.areaColor[2]]],
                                width: chartConfig.series.itemWidth || 20
                            }
                        },
                        axisTick: { // 刻度(线)样式。
                            show: true, // 是否显示刻度(线),默认 true。
                            splitNumber: 2, // 分隔线之间分割的刻度数,默认 5。
                            length: 8, // 刻度线长。支持相对半径的百分比,默认 8。
                            lineStyle: { // 刻度线样式。
                                color: '#D8D8D8' // 线的颜色,默认 #eee
                            }
                        },
                        splitLine: {
                            show: true, // 是否显示分隔线,默认 true。
                            length: 30, // 分隔线线长。支持相对半径的百分比,默认 30。
                            lineStyle: { // 分隔线样式。
                                width: 1,
                                color: '#D8D8D8'
                            }
                        },
                        axisLabel: {
                            fontSize: 10,
                            formatter: function (value) {
                                return Math.floor(value * 100) / 100 + '%'
                            },
                            color: '#A0A7B4'
                        },
                        pointer: {
                            show: true,
                            length: '70%',
                            width: 5
                        },
                        itemStyle: { // 仪表盘指针样式。
                            color: '#3E3E3E' // 指针颜色，默认(auto)取数值所在的区间的颜色
                        },
                        title: {
                            fontSize: 15,
                            fontWeight: 'bolder',
                            offsetCenter: [0, '60%']
                        },
                        detail: {
                            offsetCenter: [0, '70%'],
                            formatter: '{value}%',
                            color: '#3E3E3E', // 文字的颜色,默认 auto。
                            fontSize: 25 // 文字的字体大小,默认 15。
                        },
                        data: chartConfig.series.data
                    }
                ]
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
