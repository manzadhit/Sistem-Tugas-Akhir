import './bootstrap';

import Alpine from 'alpinejs';
import { fileUpload } from './fileUpload';

window.Alpine = Alpine;

Alpine.data('fileUpload', fileUpload);

Alpine.start();
