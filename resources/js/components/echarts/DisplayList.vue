<template>
    <div class="display-list">
      <div class="display-list-status"
        :style="{'background-color': deviceStatusColor[item.status].backgoundColor, 'border-left': `6px solid ${deviceStatusColor[item.status].borderColor}`}"
        v-for="(item,index) in attrs.data" :key="index">
        {{item.name}}
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
        deviceStatusColor: {
          1: { backgoundColor: '#CEF3F8', borderColor: '#31C1EB' },
          2: { backgoundColor: '#FFF3E3', borderColor: '#FCB422' },
          3: { backgoundColor: '#FDD4D4', borderColor: '#F65677' }
        }
      }
    }
  };
</script>
<style lang="scss">
.display-list {
  height: 360px;
  overflow: auto;
  width: 100%;
}
.display-list::-webkit-scrollbar-thumb{/*滚动条里面的滑块*/
  background: #DDDDDD;
  border-radius:10px;
  width: 5px;
}
.display-list::-webkit-scrollbar {/*滚动条整体样式*/
  width: 4px;

}
.display-list-status {
  margin: 0 12px;
  // width: 100%;
  height: 36px;
  line-height: 36px;
  border-radius: 4px;
  text-align: left;
  border-left: 6px solid #31C1EB;
  padding: 0 14px;
  font-size: 14px;
  font-family: PingFangSC-Regular,PingFang SC;
  font-weight: 400;
  color: #3E3E3E;
  margin-bottom: 8px;
}
</style>
