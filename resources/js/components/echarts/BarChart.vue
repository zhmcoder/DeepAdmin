<template>
    <div class="wrap">
      <div id="barChart" :style="chartStyle"></div>
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
          let myChart = echarts.init(document.getElementById('barChart'))
          let chartConfig = this.attrs.data
          let chartData = {
            grid: {
              top: '30',
              left: '25',
              right: '25',
              bottom: '30',
              containLabel: true
            },
            xAxis: {
              type: 'value',
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
            yAxis: {
              type: 'category',
              data: [],
              axisTick: {
                show: false
              },
              axisLabel: {
                show: false
              },
              offset: 10,
              nameTextStyle: {
                fontSize: 15
              },
              axisLine: {
                show: false
              },
              splitLine: {
                show: false
              }
            },
            series: [
              {
                type: 'bar',
                barWidth: '16',
                itemStyle: {
                  normal: {
                    barBorderRadius: 40,
                    color: 'rgba(0,0,0,0.05)'
                  },
                  emphasis: {
                    barBorderRadius: 40
                  }
                },
                barGap: '-100%',
                barCategoryGap: '50%',
                data: chartConfig.series.maxData || [],
                animation: false
              },
              {
                type: 'bar',
                barWidth: '16',
                data: [],
                smooth: true,
                label: {
                  normal: {
                    show: true,
                    position: 'topLeft',
                    offset: [5, -18],
                    textStyle: {
                      color: '#3E3E3E',
                      fontSize: 14,
                      fontWeight: 'bolder'
                    },
                    formatter: '{b}: {c}'
                  }
                },
                itemStyle: {
                  emphasis: {
                    barBorderRadius: 40
                  },
                  normal: {
                    barBorderRadius: 40
                  }
                }
              }
            ]
          }
          chartConfig.series.data.forEach((item, index) => {
            chartData.series[1].data.push({
              name: item.name,
              value: item.value,
              itemStyle: {
                normal: {
                  color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [{
                    offset: 0,
                    color: item.color
                  }, {
                    offset: 1,
                    color: item.colorEnd
                  } ])
                }
              }
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
