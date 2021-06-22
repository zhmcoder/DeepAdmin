<template>
    <div>
        <!--        <div class="toolbox">-->
        <!--            <button @click="updateFirstRow">Update first row</button>-->
        <!--            <button @click="changeZoomLevel">Change zoom level</button>-->
        <!--        </div>-->
        <div class="gstc-wrapper" ref="gstc"></div>
    </div>
</template>

<script>
    import GSTC from 'gantt-schedule-timeline-calendar';
    import {Plugin as TimelinePointer} from 'gantt-schedule-timeline-calendar/dist/plugins/timeline-pointer.esm.min.js';
    import {Plugin as Selection} from 'gantt-schedule-timeline-calendar/dist/plugins/selection.esm.min.js';
    import {Plugin as ItemResizing} from 'gantt-schedule-timeline-calendar/dist/plugins/item-resizing.esm.min.js';
    import {Plugin as ItemMovement} from 'gantt-schedule-timeline-calendar/dist/plugins/item-movement.esm.min.js';
    import 'gantt-schedule-timeline-calendar/dist/style.css';


    let gstc, state;

    // helper functions
    /**
     * @returns { import("gantt-schedule-timeline-calendar").Rows }
     */
    function generateRows() {
        const rows = {};
        for (let i = 0; i < 100; i++) {
            const id = i.toString();
            rows[id] = {
                id,
                label: `Row ${i}`
            }
        }
        return rows;
    }

    /**
     * @returns { import("gantt-schedule-timeline-calendar").Items }
     */
    function generateItems() {
        const items = {};
        let start = GSTC.api.date().startOf('day').subtract(6, 'day');
        for (let i = 0; i < 15; i++) {
            const id = i.toString();
            const rowId = i.toString();
            start = start.add(1, 'day');
            items[id] = {
                id,
                label: `Item ${i}`,
                rowId,
                time: {
                    start: start.valueOf(),
                    end: start.add(1, 'day').endOf('day').valueOf()
                }
            }
        }
        return items;
    }

    // main component
    export default {
        name: 'GSTC',
        props: {
            attrs: Object,
        },
        mounted() {
            console.log(GSTC.api.GSTCID('id'));
            console.log(this.attrs);
            const rowsFromDB = [
                {
                    id: '1',
                    label: 'Row 1',
                },
                {
                    id: '2',
                    label: 'Row 2',
                },
            ];

            const itemsFromDB = [
                {
                    id: '1',
                    label: 'Item 1',
                    rowId: '1',
                    time: {
                        start: GSTC.api.date('2020-01-01 00:00:00').valueOf(),
                        end: GSTC.api.date('2020-01-01 00:30:00').endOf('minute').valueOf(),
                    },
                },
                {
                    id: '2',
                    label: 'Item 2',
                    rowId: '2',
                    time: {
                        start: GSTC.api.date('2020-01-01 08:00:00').startOf('minute').valueOf(),
                        end: GSTC.api.date('2020-01-01 10:00:00').endOf('minute').valueOf(),
                    },
                },
            ];

            const columnsFromDB = [
                {
                    id: 'id',
                    label: 'ID',
                    data: ({ row }) => Number(GSTC.api.sourceID(row.id)), // show original id
                    sortable: ({ row }) => Number(GSTC.api.sourceID(row.id)), // sort by id converted to number
                    width: 80,
                    header: {
                        content: 'ID',
                    },
                },
                {
                    id: 'label',
                    data: 'label',
                    sortable: 'label',
                    isHTML: false,
                    width: 300,
                    header: {
                        content: 'Label',
                    },
                },
            ];
            const days = [
                {
                    zoomTo: 100, // we want to display this format for all zoom levels until 100
                    period: 'day',
                    periodIncrement: 1,
                    format({ timeStart }) {
                        return timeStart.format('DD MMMM YYYY'); // full list of formats: https://day.js.org/docs/en/display/format
                    },
                },
            ];

            const hours = [
                {
                    zoomTo: 16,
                    period: 'hour',
                    main: true,
                    periodIncrement: 1,
                    format({ timeStart }) {
                        return timeStart.format('HH:mm');
                    },
                },
            ];

            /**
             * @type { import("gantt-schedule-timeline-calendar").Config }
             */
            const config = {
                // licenseKey:'====BEGIN LICENSE KEY====\nXOfH/lnVASM6et4Co473t9jPIvhmQ/l0X3Ewog30VudX6GVkOB0n3oDx42NtADJ8HjYrhfXKSNu5EMRb5KzCLvMt/pu7xugjbvpyI1glE7Ha6E5VZwRpb4AC8T1KBF67FKAgaI7YFeOtPFROSCKrW5la38jbE5fo+q2N6wAfEti8la2ie6/7U2V+SdJPqkm/mLY/JBHdvDHoUduwe4zgqBUYLTNUgX6aKdlhpZPuHfj2SMeB/tcTJfH48rN1mgGkNkAT9ovROwI7ReLrdlHrHmJ1UwZZnAfxAC3ftIjgTEHsd/f+JrjW6t+kL6Ef1tT1eQ2DPFLJlhluTD91AsZMUg==||U2FsdGVkX1/SWWqU9YmxtM0T6Nm5mClKwqTaoF9wgZd9rNw2xs4hnY8Ilv8DZtFyNt92xym3eB6WA605N5llLm0D68EQtU9ci1rTEDopZ1ODzcqtTVSoFEloNPFSfW6LTIC9+2LSVBeeHXoLEQiLYHWihHu10Xll3KsH9iBObDACDm1PT7IV4uWvNpNeuKJc\npY3C5SG+3sHRX1aeMnHlKLhaIsOdw2IexjvMqocVpfRpX4wnsabNA0VJ3k95zUPS3vTtSegeDhwbl6j+/FZcGk9i+gAy6LuetlKuARjPYn2LH5Be3Ah+ggSBPlxf3JW9rtWNdUoFByHTcFlhzlU9HnpnBUrgcVMhCQ7SAjN9h2NMGmCr10Rn4OE0WtelNqYVig7KmENaPvFT+k2I0cYZ4KWwxxsQNKbjEAxJxrzK4HkaczCvyQbzj4Ppxx/0q+Cns44OeyWcwYD/vSaJm4Kptwpr+L4y5BoSO/WeqhSUQQ85nvOhtE0pSH/ZXYo3pqjPdQRfNm6NFeBl2lwTmZUEuw==\n====END LICENSE KEY====',
                licenseKey: this.attrs.licenseKey,

                list: {
                    columns: {
                        data: GSTC.api.fromArray(columnsFromDB),
                    },
                    rows: GSTC.api.fromArray(rowsFromDB),
                },
                chart: {
                    items: GSTC.api.fromArray(itemsFromDB),
                    calendarLevels: [days, hours],
                    time: {
                        zoom: 15.5,
                        from: GSTC.api.date('2020-01-01').startOf('day').valueOf(),
                        to: GSTC.api.date('2020-01-01').endOf('day').valueOf(),
                    },

                },
            };
            console.log(config);
            console.log(JSON.stringify(config));
            state = GSTC.api.stateFromConfig(config);
            gstc = GSTC({
                element: this.$refs.gstc,
                state,
            });
        },
        beforeUnmount() {
            if (gstc) gstc.destroy();
        },
        methods: {
            updateFirstRow() {
                state.update(`config.list.rows.${GSTC.api.GSTCID('0')}`, row => {
                    row.label = 'Changed dynamically';
                    return row;
                });
            },
            changeZoomLevel() {
                state.update('config.chart.time.zoom', 21);
            }
        }
    };
</script>
<style scoped>
    .gstc-component {
        margin: 0;
        padding: 0;
    }

    .toolbox {
        padding: 10px;
    }
</style>
