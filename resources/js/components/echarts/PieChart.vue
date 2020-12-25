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
                    text: chartConfig.title.text || '',
                    subtext: chartConfig.subtitle.text || '',
                    left: chartConfig.title.left || 'center',
                    top: chartConfig.title.top || 'center',
                    textStyle: {
                        fontWeight: '500',
                        fontSize: chartConfig.title.fontSize,
                        color: chartConfig.title.color
                    },
                    subtextStyle: {
                        fontWeight: 'normal',
                        fontSize: chartConfig.subtitle.fontSize,
                        color: chartConfig.subtitle.color
                    }
                },
                tooltip: {
                    show: chartConfig.showTooltip || true,
                    trigger: 'item',
                    formatter: '{b} : {d}%'
                },
                legend: {
                    orient: chartConfig.legend.orient,
                    right: chartConfig.legend.right || '10%',
                    y: chartConfig.legend.y || 'middle',
                    data: chartConfig.legend.data,
                    textStyle: {
                        color: chartConfig.legend.color,
                        fontSize: chartConfig.legend.fontSize
                    },
                    itemWidth: 16,
                    itemHeight: 16
                },
                "series": [
                    {
                        type: 'pie',
                        radius: chartConfig.series.radius || '60%',
                        color: chartConfig.series.color,
                        center: chartConfig.series.center || ['35%', '50%'],
                        label: {
                            show: false
                        },
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data: chartConfig.series.data,
                        hoverAnimation: false,
                        itemStyle: {
                            normal: {
                                borderWidth: 2,
                                borderColor: '#fff'
                            }
                        }
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
