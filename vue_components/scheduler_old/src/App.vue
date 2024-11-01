<template>
  <div id='app'>
      <ejs-schedule :height='height' :width='width'  :selectedDate='selectedDate' :eventSettings='eventSettings'></ejs-schedule>
  </div>
</template>
<script>
  import Vue from 'vue';
//  import { scheduleData } from './datasource.js';
  import { extend } from '@syncfusion/ej2-base';
  import { DataManager, Query, ODataV4Adaptor } from '@syncfusion/ej2-data';
  import { SchedulePlugin, Day, Week, WorkWeek, Month, Agenda, View } from '@syncfusion/ej2-vue-schedule';
  import { setCulture } from '@syncfusion/ej2-base';


  import axios from 'axios';
//setCulture('it-IT');

  Vue.use(SchedulePlugin);
//let res = axios.get('/backend/martinimultimedia/asso/events/events');

    let dataManager = new DataManager({
        url: '/backend/martinimultimedia/asso/events/events',
        adaptor: new ODataV4Adaptor(),
        crossDomain: true
    });
    let dataQuery = new Query().from('Events');
    export default {
        data() {
            return {
                height: 'auto',
                width: 'auto',
                selectedDate: new Date(),
                readOnly: true,
                eventSettings: { dataSource: dataManager, query: dataQuery }
            };
        },
        provide: {
            schedule: [Day, Week, WorkWeek, Month, Agenda]
        },
        workHours: {
        highlight: true,
        start: '09:00',
        end: '11:00'
    },
    }


</script>
<style>
@import '../node_modules/@syncfusion/ej2-base/styles/material.css';
@import '../node_modules/@syncfusion/ej2-buttons/styles/material.css';
@import '../node_modules/@syncfusion/ej2-calendars/styles/material.css';
@import '../node_modules/@syncfusion/ej2-dropdowns/styles/material.css';
@import '../node_modules/@syncfusion/ej2-inputs/styles/material.css';
@import '../node_modules/@syncfusion/ej2-navigations/styles/material.css';
@import '../node_modules/@syncfusion/ej2-popups/styles/material.css';
@import '../node_modules/@syncfusion/ej2-vue-schedule/styles/material.css';
</style>