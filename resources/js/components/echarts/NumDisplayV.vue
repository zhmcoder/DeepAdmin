<template>
  <div class="num-display-v">
    <div class="num-display-v-title">停机总时长</div>
    <div class="num-display-v-stop">
      <div>
        <span :style="`color: ${attrs.data.color[0]}`" class="total-stop">{{downTime[0]}}</span>
        <span class="total-stop-unit">天</span>
      </div>
      <div>
        <span :style="`color: ${attrs.data.color[1]}`" class="total-stop">{{downTime[1]}}</span>
        <span class="total-stop-unit">时</span>
      </div>
      <div>
        <span :style="`color: ${attrs.data.color[2]}`" class="total-stop">{{downTime[2]}}</span>
        <span class="total-stop-unit">分</span>
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    props: {
        attrs: Object
    },
    data() {
      return {
        downTime: this.convertTime(this.attrs.data.duration)
      }
    },
    methods: {
      convertTime (time) {
        if (time) {
          let day = time.split('天')[0] || 0
          let hour = time.split('天')[1].split('小时')[0] || 0
          let min = time.split('天')[1].split('小时')[1].split('分钟')[0] || 0
          let sec = time.split('天')[1].split('小时')[1].split('分钟')[1].split('秒') || 0
          return [day, hour, min, sec]
        } else {
          return [0, 0, 0]
        }
      }
    },
  };
</script>
<style lang="scss">
.num-display-v {
  width: 100%;
  height: 360px;
  display: flex;
  align-items: center;
}
.num-display-v-title {
  font-size: 20px;
  text-align: center;
  color: #3E3E3E;
  position: absolute;
  top: 54px;
  left: 0;
  right: 0;
}
.num-display-v-stop {
  margin: 0 auto;
  div {
    margin-top: 20px;
    text-align: right;
  }
}
.num-display-v .total-stop {
  font-size: 60px;
  font-family: PingFangSC-Semibold,PingFang SC;
  font-weight: 600;
}
.num-display-v .total-stop-unit {
  padding-left: 6px;
  font-size: 20px;
  font-family: PingFangSC-Semibold,PingFang SC;
  font-weight: 600;
  color: #3E3E3E;
}
</style>
