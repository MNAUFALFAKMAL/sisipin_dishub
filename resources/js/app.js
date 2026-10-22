import './bootstrap';
import Alpine from 'alpinejs';

// Import TomSelect
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

// Make TomSelect globally available
window.TomSelect = TomSelect;

// Import components
import './components/surat-search';

window.Alpine = Alpine;

Alpine.start();