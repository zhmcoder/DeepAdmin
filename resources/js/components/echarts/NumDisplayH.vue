<template>
    <div class="num-display-h">
      <div class="num-display-wrap">
        <div v-for="(item, index) in attrs.data" :key="index">
          <p class="num-display-wrap-num" :style="`color: ${item.color}`">{{item.count}}</p>
          <p class="num-display-wrap-name">{{item.name}}</p>
        </div>
      </div>
    </div>
</template>
<script>
  export default {
    props: {
        attrs: Object
    }
  };
</script>
<style lang="scss">
.num-display-h {
  width: 100%;
  /*height: 400px;*/
}
.num-display-wrap {
  display: flex;
  justify-content: space-around;
  text-align: center;
  /*padding-top: 100px;*/
  &-num {
    font-size: 70px;
    font-family: PingFangSC-Semibold,PingFang SC;
    font-weight: 600;
    line-height: 98px;
    margin: 0;
  }
  &-name {
    font-size: 16px;
    font-family: PingFangSC-Medium,PingFang SC;
    font-weight: 500;
    line-height: 22px;
    margin: 0;
  }
}
</style>
