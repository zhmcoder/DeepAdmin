import Vue from "vue";

Vue.component("login", require("@/components/Login").default);
Vue.component("Root", require("@/components/Root").default);
Vue.component("Content", require("@/components/layout/Content").default);
Vue.component("Row", require("@/components/layout/Row").default);
Vue.component("Column", require("@/components/layout/Column").default);
Vue.component("Grid", require("@/components/grid/Table").default);
Vue.component("LeftGrid", require("@/components/grid/LeftTable").default);
Vue.component("Tree", require("@/components/grid/Tree").default);
Vue.component("Form", require("@/components/form/Form").default);
Vue.component("TabForm", require("@/components/form/TabForm").default);
Vue.component("BaseForm", require("@/components/form/BaseForm").default);
Vue.component("MenuItem", require("@/components/layout/MenuItem").default);
Vue.component("ChartCard", require("@/components/layout/ChartCard").default);
Vue.component("SearchGrid", require("@/components/grid/SearchTable").default);

//Form
Vue.component("Input", require("@/components/widgets/Form/Input").default);
Vue.component(
  "RadioGroup",
  require("@/components/widgets/Form/RadioGroup").default
);
Vue.component(
  "Checkbox",
  require("@/components/widgets/Form/Checkbox").default
);
Vue.component(
  "CheckboxGroup",
  require("@/components/widgets/Form/CheckboxGroup").default
);
Vue.component(
  "InputNumber",
  require("@/components/widgets/Form/InputNumber").default
);
Vue.component("Select", require("@/components/widgets/Form/Select").default);
Vue.component(
  "Cascader",
  require("@/components/widgets/Form/Cascader").default
);
Vue.component("CSwitch", require("@/components/widgets/Form/Switch").default);
Vue.component("Slider", require("@/components/widgets/Form/Slider").default);
Vue.component(
  "Transfer",
  require("@/components/widgets/Form/Transfer").default
);
Vue.component("Upload", require("@/components/widgets/Form/Upload").default);
Vue.component(
  "ColorPicker",
  require("@/components/widgets/Form/ColorPicker").default
);
Vue.component(
  "TimePicker",
  require("@/components/widgets/Form/TimePicker").default
);
Vue.component("Rate", require("@/components/widgets/Form/Rate").default);
Vue.component(
  "DatePicker",
  require("@/components/widgets/Form/DatePicker").default
);
Vue.component(
  "DateTimePicker",
  require("@/components/widgets/Form/DateTimePicker").default
);
Vue.component("WangEditor", () =>
  import("@/components/widgets/Form/WangEditor")
);
Vue.component(
  "ItemSelect",
  require("@/components/widgets/Form/ItemSelect").default
);
Vue.component(
  "IconChoose",
  require("./components/widgets/Form/IconChoose").default
);
Vue.component("AddTag", require("./components/widgets/Form/AddTag").default);
Vue.component("ImageList", require("./components/widgets/Form/Image").default);

//Grid
Vue.component("Avatar", require("./components/widgets/Grid/Avatar").default);
Vue.component("Tag", require("./components/widgets/Grid/Tag").default);
Vue.component("Link", require("./components/widgets/Grid/Link").default);

Vue.component("IImage", require("./components/widgets/Grid/Image").default);
Vue.component("Icon", require("./components/widgets/Grid/Icon").default);
Vue.component("Boole", require("./components/widgets/Grid/Boole").default);
Vue.component(
  "GridRoute",
  require("./components/widgets/Grid/GridRoute").default
);

//Actions
Vue.component(
  "EditAction",
  require("@/components/widgets/Actions/EditAction").default
);
Vue.component(
  "DeleteAction",
  require("@/components/widgets/Actions/DeleteAction").default
);
Vue.component(
  "DeleteDialogAction",
  require("@/components/widgets/Actions/DeleteDialogAction").default
);
Vue.component(
  "VueRouteAction",
  require("@/components/widgets/Actions/VueRouteAction").default
);
Vue.component(
  "ActionButton",
  require("@/components/widgets/Actions/ActionButton").default
);

//BatchAction
Vue.component(
  "BatchAction",
  require("@/components/widgets/BatchActions/BatchAction").default
);

//Tools
Vue.component(
  "GridCreateButton",
  require("@/components/widgets/Tools/Create").default
);
Vue.component(
  "ToolButton",
  require("@/components/widgets/Tools/ToolButton").default
);

//Widget
Vue.component("IText", require("./components/widgets/Base/Text").default);
Vue.component("Button", require("@/components/widgets/Base/Button").default);
Vue.component("Divider", require("@/components/widgets/Base/Divider").default);
Vue.component("Card", require("@/components/widgets/Base/Card").default);
Vue.component("Steps", require("@/components/widgets/Base/Steps").default);
Vue.component("Html", require("@/components/widgets/Base/Html").default);
Vue.component("Alert", require("@/components/widgets/Base/Alert").default);
Vue.component(
  "DialogButton",
  require("@/components/widgets/Base/DialogButton").default
);
Vue.component("Tooltip", require("@/components/widgets/Base/Tooltip").default);
Vue.component("QrWrap", require("@/components/widgets/Base/QrWrap").default);
Vue.component("Markdown", () => import("@/components/widgets/Form/Markdown"));
//antv
Vue.component("AntvLine", () => import("@/components/antv/AntvLine"));
Vue.component("AntvArea", () => import("@/components/antv/AntvArea"));
Vue.component("AntvStepLine", () => import("@/components/antv/AntvStepLine"));
Vue.component("AntvColumn", () => import("@/components/antv/AntvColumn"));

// deep-admin
Vue.component(
  "SortEdit",
  require("./components/grid/widgets/SortEdit").default
);
Vue.component(
  "SortUpDown",
  require("./components/grid/widgets/SortUpDown").default
);
// Vue.component("DragGrid", require('./components/grid/DragTable').default);

Vue.component(
  "AntvGroupColumn",
  require("./components/antv/AntvGroupColumn").default
);
Vue.component(
  "AntvStackedColumn",
  require("./components/antv/AntvStackedColumn").default
);
Vue.component("AntvPie", require("./components/antv/AntvPie").default);
Vue.component("AntvGraph", require("./components/antv/AntvGraph").default);
Vue.component(
  "AntvDualAxes",
  require("./components/antv/AntvDualAxes").default
);

Vue.component(
  "LineChart",
  require("./components/echarts/LineChart.vue").default
);
Vue.component("PieChart", require("./components/echarts/PieChart.vue").default);
Vue.component("BarChart", require("./components/echarts/BarChart.vue").default);
Vue.component(
  "GaugeChart",
  require("./components/echarts/GaugeChart.vue").default
);
Vue.component("MixChart", require("./components/echarts/MixChart.vue").default);
Vue.component(
  "RatePieChart",
  require("./components/echarts/RatePieChart.vue").default
);
Vue.component(
  "LiquidFillChart",
  require("./components/echarts/LiquidFillChart.vue").default
);
Vue.component(
  "SectionLineChart",
  require("./components/echarts/SectionLineChart.vue").default
);
Vue.component(
  "NumDisplayH",
  require("./components/echarts/NumDisplayH.vue").default
);
Vue.component(
  "NumDisplayV",
  require("./components/echarts/NumDisplayV.vue").default
);
Vue.component(
  "DisplayList",
  require("./components/echarts/DisplayList.vue").default
);
Vue.component(
  "MarkLineChart",
  require("./components/echarts/MarkLineChart.vue").default
);
Vue.component(
  "NormalBarChart",
  require("./components/echarts/NormalBarChart.vue").default
);
Vue.component(
  "PropertyList",
  require("./components/echarts/PropertyList.vue").default
);
// Vue.component("GSTC", require('./components/gantt/GSTC.vue').default);
Vue.component("AddRow", require("./components/widgets/AddRow.vue").default);
Vue.component("Info", require("./components/widgets/Info.vue").default);
Vue.component("RowMulti", require("./components/form/RowMulti.vue").default);
// Vue.component("TestButton",require('./components/widgets/TestButton').default);
Vue.component(
  "DeleteActionConfirm",
  require("./components/widgets/Actions/DeleteActionConfirm").default
);
Vue.component(
  "MessageDialog",
  require("./components/widgets/MessageDialog").default
);
