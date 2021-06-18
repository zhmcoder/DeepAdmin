<template>
    <div :id="attrs.canvasId"></div>
</template>
<script>
    import { Graph } from "@antv/x6";
    export default {
        props: {
            attrs: Object
        },
        data() {
            return {
                antv: null
            };
        },
        mounted() {
            this.antv = new Graph({
                container: document.getElementById(this.attrs.canvasId),
                ...this.attrs.config
            }).fromJSON(this.attrs.data);

            this.antv.render();
        },
        updated() {
            this.antv.changeData(this.attrs.data);
        },
        destroyed() {
            //this.antv.destory();
        }
    };
</script>
