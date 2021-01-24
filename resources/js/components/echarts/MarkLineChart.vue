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
            console.log(this.attrs, 1111)
            let chartData = {
                grid: {
                    top: '30',
                    left: 40,
                    right: 80,
                    bottom: 20,
                    containLabel: true
                },
                tooltip: {
                  trigger: 'axis',
                  formatter: (param) => {
                    let current = param[0]
                    if (current.data && current.data.is_warning) {
                      let list = ''
                      current.data.waring_list.map(item => {
                        list += item + '<br/>'
                      })
                      return list
                    } else {
                      return current.axisValue + '<br/>' + current.value
                    }
                  }
                },
                xAxis: {
                  type: 'category',
                  boundaryGap: false,
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
                    min: chartConfig.yAxisData.min,
                    max: chartConfig.yAxisData.max,
                },
                visualMap: chartConfig.visualMap,
                series: chartConfig.series
            }
            myChart.setOption(chartData)
        }
    };
</script>
<style lang="scss">
    .wrap {
        height: 100%;
        width: 100%;
    }
</style>
