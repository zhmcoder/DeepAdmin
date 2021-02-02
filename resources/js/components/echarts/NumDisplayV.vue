<template>
  <div class="num-display-v" :style="wrapStyle">
    <div class="num-display-v-stop">
        <div v-for="(item, index) in attrs.data.data" :key="index">
          <span class="total-stop" :style="`color: ${item.color}`">{{item.count}}</span>
          <span class="otal-stop-unit">{{item.name}}</span>
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
        wrapStyle: {
          width: this.attrs.data.width || '100%',
          height: this.attrs.data.height || '360px'
        }
      };
    },
  };
</script>
<style lang="scss">
.num-display-v {
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
