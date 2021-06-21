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
            const calendarLevel1 = [
                {
                    zoomTo: 16,
                    period: 'hour',
                    main: true,
                    periodIncrement: 1,
                    format({ timeStart }) {
                        return timeStart.format('HH:mm');
                    },
                },
                {
                    zoomTo: 17,
                    period: 'hour',
                    main: true,
                    periodIncrement: 1,
                    format({ timeStart }) {
                        return timeStart.format('HH');
                    },
                },
                {
                    zoomTo: 19,
                    period: 'day',
                    main: true,
                    periodIncrement: 1,
                    classNames: ['gstc-date-medium'],
                    format({ timeStart, className, vido }) {
                        return vido.html`<span class="${className}-content gstc-date-bold">${timeStart.format(
                            'DD'
                        )}</span> <span class="${className}-content gstc-date-thin">${timeStart.format(
                            'dddd'
                        )}</span>`;
                    },
                },
                {
                    zoomTo: 20,
                    period: 'day',
                    main: true,
                    periodIncrement: 1,
                    format({ timeStart, vido, className }) {
                        return vido.html`<div class="${className}-content gstc-date-top">${timeStart.format(
                            'DD'
                        )}</div><div class="${className}-content gstc-date-small">${timeStart.format(
                            'dddd'
                        )}</div>`;
                    },
                },
                {
                    zoomTo: 21,
                    period: 'day',
                    main: true,
                    periodIncrement: 1,
                    format({ timeStart, vido, className }) {
                        return vido.html`<div class="${className}-content gstc-date-top">${timeStart.format(
                            'DD'
                        )}</div><div class="${className}-content gstc-date-small">${timeStart.format(
                            'ddd'
                        )}</div>`;
                    },
                },
                {
                    zoomTo: 22,
                    period: 'day',
                    main: true,
                    periodIncrement: 1,
                    classNames: ['gstc-date-vertical'],
                    format({ timeStart, className, vido }) {
                        return vido.html`<div class="${className}-content gstc-date-top">${timeStart.format(
                            'DD'
                        )}</div><div class="${className}-content gstc-date-extra-small">${timeStart.format(
                            'ddd'
                        )}</div>`;
                    },
                },
                {
                    zoomTo: 23,
                    period: 'week',
                    main: true,
                    periodIncrement: 1,
                    format({ timeStart, timeEnd, className, vido }) {
                        return vido.html`<div class="${className}-content gstc-date-top">${timeStart.format(
                            'DD'
                        )} - ${timeEnd.format(
                            'DD'
                        )}</div><div class="${className}-content gstc-date-small gstc-date-thin">${timeStart.format(
                            'ddd'
                        )} - ${timeEnd.format('dd')}</div>`;
                    },
                },
                {
                    zoomTo: 25,
                    period: 'week',
                    main: true,
                    periodIncrement: 1,
                    classNames: ['gstc-date-vertical'],
                    format({ timeStart, timeEnd, className, vido }) {
                        return vido.html`<div class="${className}-content gstc-date-top gstc-date-small gstc-date-normal">${timeStart.format(
                            'DD'
                        )}</div><div class="gstc-dash gstc-date-small">-</div><div class="${className}-content gstc-date-small gstc-date-normal">${timeEnd.format(
                            'DD'
                        )}</div>`;
                    },
                },
                {
                    zoomTo: 26,
                    period: 'month',
                    main: true,
                    periodIncrement: 1,
                    classNames: ['gstc-date-month-level-1'],
                    format({ timeStart, vido, className }) {
                        return vido.html`<div class="${className}-content gstc-date-top">${timeStart.format(
                            'MMM'
                        )}</div><div class="${className}-content gstc-date-small gstc-date-bottom">${timeStart.format(
                            'MM'
                        )}</div>`;
                    },
                },
                {
                    zoomTo: 27,
                    period: 'month',
                    main: true,
                    periodIncrement: 1,
                    classNames: ['gstc-date-vertical'],
                    format({ timeStart, className, vido }) {
                        return vido.html`<div class="${className}-content gstc-date-top">${timeStart.format(
                            'MM'
                        )}</div><div class="${className}-content gstc-date-extra-small">${timeStart.format(
                            'MMM'
                        )}</div>`;
                    },
                },
                {
                    zoomTo: 28,
                    period: 'year',
                    main: true,
                    periodIncrement: 1,
                    classNames: ['gstc-date-big'],
                    format({ timeStart }) {
                        return timeStart.format('YYYY');
                    },
                },
                {
                    zoomTo: 29,
                    period: 'year',
                    main: true,
                    periodIncrement: 1,
                    classNames: ['gstc-date-medium'],
                    format({ timeStart }) {
                        return timeStart.format('YYYY');
                    },
                },
                {
                    zoomTo: 30,
                    period: 'year',
                    main: true,
                    periodIncrement: 1,
                    classNames: ['gstc-date-medium'],
                    format({ timeStart }) {
                        return timeStart.format('YY');
                    },
                },
                {
                    zoomTo: 100,
                    period: 'year',
                    main: true,
                    periodIncrement: 1,
                    format() {
                        return null;
                    },
                },
            ];
            const calendarLevel0 = [
                {
                    zoomTo: 17,
                    period: 'day',
                    periodIncrement: 1,
                    classNames: ['gstc-date-medium gstc-date-left'],
                    format({ timeStart }) {
                        return timeStart.format('DD MMMM YYYY (dddd)');
                    },
                },
                {
                    zoomTo: 23,
                    period: 'month',
                    periodIncrement: 1,
                    format({ timeStart }) {
                        return timeStart.format('MMMM YYYY');
                    },
                },
                {
                    zoomTo: 24,
                    period: 'month',
                    periodIncrement: 1,
                    format({ timeStart }) {
                        return timeStart.format("MMMM 'YY");
                    },
                },
                {
                    zoomTo: 25,
                    period: 'month',
                    periodIncrement: 1,
                    format({ timeStart }) {
                        return timeStart.format('MMM YYYY');
                    },
                },
                {
                    zoomTo: 27,
                    period: 'year',
                    periodIncrement: 1,
                    format({ timeStart }) {
                        return timeStart.format('YYYY');
                    },
                },
                {
                    zoomTo: 100,
                    period: 'year',
                    periodIncrement: 1,
                    format() {
                        return null;
                    },
                },
            ];
            const date = GSTC.api.date;

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
                plugins: [TimelinePointer(), Selection(), ItemResizing(), ItemMovement()],
                list: {
                    columns: this.attrs.lists.columns,
                    rows: this.attrs.lists.rows
                },
                chart: {
                    items: this.attrs.chart.items,

                }
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
