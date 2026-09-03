import './theme';
import 'animate.css';
import { createIcons, icons } from 'lucide';

// Initialize Lucide icons on DOM load
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});
