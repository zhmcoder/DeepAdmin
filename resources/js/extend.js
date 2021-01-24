import Vue from "vue";

VueAdmin.booting((Vue, router, store) => {
    Vue.component("Link", require("./components/widgets/Grid/Link").default);

    Vue.component("SortEdit", require('./components/grid/widgets/SortEdit').default);
    Vue.component("SortUpDown", require('./components/grid/widgets/SortUpDown').default);
    Vue.component("DragGrid", require('./components/grid/DragTable').default);

    Vue.component("AntvGroupColumn", require('./components/antv/AntvGroupColumn').default);
    Vue.component("AntvStackedColumn", require('./components/antv/AntvStackedColumn').default);
    Vue.component("AntvPie", require('./components/antv/AntvPie').default);

    Vue.component("LineChart", require('./components/echarts/LineChart.vue').default);
    Vue.component("PieChart", require('./components/echarts/PieChart.vue').default);
    Vue.component("BarChart", require('./components/echarts/BarChart.vue').default);
    Vue.component("GaugeChart", require('./components/echarts/GaugeChart.vue').default);
    Vue.component("MixChart", require('./components/echarts/MixChart.vue').default);
    Vue.component("RatePieChart", require('./components/echarts/RatePieChart.vue').default);
    Vue.component("LiquidFillChart", require('./components/echarts/LiquidFillChart.vue').default);
    Vue.component("SectionLineChart", require('./components/echarts/SectionLineChart.vue').default);
    Vue.component("NumDisplayH", require('./components/echarts/NumDisplayH.vue').default);
    Vue.component("NumDisplayV", require('./components/echarts/NumDisplayV.vue').default);
    Vue.component("DisplayList", require('./components/echarts/DisplayList.vue').default);
    Vue.component("MarkLineChart", require('./components/echarts/MarkLineChart.vue').default);
});
