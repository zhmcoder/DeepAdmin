<template>
    <div class="wrap">
      <div id="lineChart" :style="chartStyle"></div>
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
          let myChart = echarts.init(document.getElementById('lineChart'))
          let chartConfig = this.attrs.data
          let chartData = {
            grid: {
                top: '30', // 距上边距
                left: '20', // 距离左边距
                right: '10', // 距离右边距
                bottom: 20, // 距离下边距
                containLabel: true
            },
            tooltip: {
                trigger: 'item',
                formatter: '{b} : {c}'
            },
            xAxis: {
                type: 'category',
                axisLine: {
                lineStyle: {
                    color: '#EEEEEE'
                }
                },
                axisLabel: {
                interval: 0,
                rotate: 0,
                color: '#3E3E3E'
                },
                boundaryGap: [0, 0.01],
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
                splitLine: {
                show: false
                }
            },
            series: [{
                type: 'line',
                data: chartConfig.series.data || [],
                // smooth: true,
                symbol: 'none',
                lineStyle: {
                color: chartConfig.series.color,
                width: 2,
                type: 'solid'
                },
                // 折线堆积区域样式
                areaStyle: {
                    type: 'default',
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: chartConfig.series.areaColor[0]
                    }, {
                        offset: 1,
                        color: chartConfig.series.areaColor[1]
                    } ])
                },
                // 折线连接点样式
                itemStyle: {
                    show: false
                }
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
