"use strict"

import {ApiClient} from './apiClient';

var apiClient = new ApiClient();

apiClient.getTasks()
.then(data => {
    console.log(`I got back ${data.length} rows`)
})
.catch(err => {
    console.log(`Something went horribly wrong: ${err}`);
})