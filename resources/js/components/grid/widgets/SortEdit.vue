<template slot-scope="scope">
    <div class="rank">
        <input type="text" v-model="sort_value" @blur="sort_change(1)"/>
    </div>
</template>

<script>
    export default {
        props: {
            attrs: Object,
            row: Object,
            column_value: {
                default: null
            },
            value: {
                default: null
            }
        },
        data() {
            return {
                sort_value: this.value,
                old_sort_value: this.value,
            };
        },
        mounted() {
            console.log(this.scope);
            console.log(this.attrs);
            console.log(this.sort_value + " sort value");

        },
        methods: {
            onRequest(uri) {
                this.loading = true;
                this.$http
                    .get(uri)
                    .then(res => {
                        if (res.code == 200) {

                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            sort_change(index) {
                console.log("index = " + index);
                console.log(this.sort_value + " sort value");
                console.log(this.old_sort_value + " old_sort_value");
                console.log(this.old_sort_value + " old_sort_value");
                if (this.attrs.action != null && this.old_sort_value != this.sort_value) {
                    this.onRequest(this.attrs.action + '&id=' +
                        this.row.id + "&sort_value=" + this.sort_value);
                }
            }
        }
    }
</script>
<style lang="scss" scoped>
    .rank {
        input {
            width: 30px;
            display: inline-block;
            border: 1px solid #E0E0E0;
            outline: none;
        }
    }

</style>

