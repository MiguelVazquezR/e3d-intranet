require('./bootstrap');
require('./alerts');
require('./utils');
require('./geolocation');
require('./reminder');

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';


window.Alpine = Alpine;

Alpine.plugin(collapse);
Alpine.start();
