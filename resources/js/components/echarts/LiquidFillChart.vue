<template>
    <div class="wrap">
      <div id="liquidFillChart" :style="chartStyle"></div>
    </div>
</template>
<script>
import echarts from 'echarts'
import 'echarts-liquidfill/src/liquidFill.js'
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
      let myChart = echarts.init(document.getElementById('liquidFillChart'))
      let chartConfig = this.attrs.data
      let chartData = {
        series: [{
          type: 'liquidFill',
          data: [chartConfig.series.data / 100],
          name: chartConfig.series.name,
          outline: {
            show: false
          },
          backgroundStyle: {
            color: chartConfig.series.background || '#F5F7FA'
          },
          radius: chartConfig.series.radius || '90%',
          label: {
            formatter: function (param) {
              return param.seriesName + '\n' + param.value * 100 + '%'
            },
            fontSize: 28,
            color: chartConfig.series.color || '#3E3E3E',
            insideColor: chartConfig.series.color || '#3E3E3E'
          },
          itemStyle: {
            color: chartConfig.series.itemColor || '#4BB9FF'
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
