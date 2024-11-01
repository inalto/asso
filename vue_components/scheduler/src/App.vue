<script setup>
import { extend } from '@syncfusion/ej2-base';
import { DataManager, Query, ODataV4Adaptor } from '@syncfusion/ej2-data';
import { SchedulePlugin } from '@syncfusion/ej2-vue-schedule';
import { setCulture } from '@syncfusion/ej2-base';
import { ref } from 'vue';

// Optional: Set culture if needed
setCulture('en-US');

// Scheduler configuration
const height = '650px';
const width = '100%';
const selectedDate = ref(new Date());

// Example event settings with DataManager (if using remote data)
const dataManager = new DataManager({
  url: 'https://your-odata-service-url', // Replace with your OData URL
  adaptor: new ODataV4Adaptor(),
});
const eventSettings = {
  dataSource: dataManager,
};

</script>

<template>
    <main>
      <ejs-schedule :height='height' :width='width'  :selectedDate='selectedDate' :eventSettings='eventSettings'></ejs-schedule>

aaa    

  </main>
</template>

<style scoped>

</style>
