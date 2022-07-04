require('./bootstrap');
require('./alerts');
require('./geolocation');

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';


window.Alpine = Alpine;

Alpine.plugin(collapse);
Alpine.start();
