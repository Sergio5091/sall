import '../css/app.css';
import './bootstrap';

// Import Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// Add global fallback function to prevent ReferenceError
window.toggleMobileMenu = function() {
    console.warn('toggleMobileMenu called but Sidebar component may not be mounted yet');
    // Try to find the actual Vue component and call its method
    const event = new CustomEvent('toggleMobileMenu');
    document.dispatchEvent(event);
    
    // Also try to call the function if it exists (fallback for edge cases)
    if (window.toggleSidebar && typeof window.toggleSidebar === 'function') {
        window.toggleSidebar();
    }
};

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
