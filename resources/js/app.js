import '../css/app.css';
import './bootstrap';

// Import Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';

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

// Create a simple route function
window.route = function(name, params = {}) {
    const routes = {
        'login': '/login',
        'register': '/register',
        'logout': '/logout',
        'dashboard': '/dashboard',
        'admin.dashboard': '/admin/dashboard',
        'admin.login': '/admin/login',
        'admin.products.index': '/admin/products',
        'admin.products.create': '/admin/products/create',
        'admin.products.store': '/admin/products',
        'admin.products.show': '/admin/products/{id}',
        'admin.products.edit': '/admin/products/{id}/edit',
        'admin.products.update': '/admin/products/{id}',
        'admin.products.destroy': '/admin/products/{id}',
        'admin.clients.index': '/admin/clients',
        'admin.clients.show': '/admin/clients/{id}',
        'admin.clients.toggle-status': '/admin/clients/{id}/toggle-status',
        'admin.promoteurs.index': '/admin/promoteurs',
        'admin.promoteurs.show': '/admin/promoteurs/{id}',
        'admin.salles.index': '/admin/salles',
        'admin.salles.show': '/admin/salles/{id}',
        'admin.events.index': '/admin/events',
        'admin.events.show': '/admin/events/{id}',
        'admin.news.index': '/admin/news',
        'admin.news.create': '/admin/news/create',
        'admin.news.store': '/admin/news',
        'admin.news.show': '/admin/news/{id}',
        'admin.news.edit': '/admin/news/{id}/edit',
        'admin.news.update': '/admin/news/{id}',
        'admin.news.destroy': '/admin/news/{id}',
        'admin.subadmins.index': '/admin/subadmins',
        'admin.subadmins.create': '/admin/subadmins/create',
        'admin.subadmins.show': '/admin/subadmins/{id}',
        'admin.subadmins.edit': '/admin/subadmins/{id}/edit',
        'admin.sub-admins.index': '/admin/sub-admins',
        'admin.sub-admins.create': '/admin/sub-admins/create',
        'admin.sub-admins.show': '/admin/sub-admins/{subAdmin}',
        'admin.sub-admins.edit': '/admin/sub-admins/{subAdmin}/edit',
        'admin.sub-admins.dashboard': '/admin/sub-admin-dashboard',
        'admin.sub-admins.salles.index': '/admin/sub-admin/salles',
        'admin.profile': '/admin/profile',
        'admin.settings': '/admin/settings',
        'admin.standalone-events.index': '/admin/standalone-events',
        'admin.sub-admins.promoters.index': '/admin/sub-admins/promoters',
        'password.request': '/forgot-password',
        'password.email': '/forgot-password',
        'password.reset': '/reset-password/{token}',
        'password.update': '/reset-password',
        'verification.notice': '/verify-email',
        'verification.verify': '/verify-email/{id}/{hash}',
        'verification.send': '/email/verification-notification',
        'password.confirm': '/confirm-password',
        'password.update': '/password',
        'search': '/search',
    };
    
    // If no parameters, return an object with current method
    if (arguments.length === 0) {
        return {
            current: function(routeName) {
                // Get current URL without query parameters
                const currentPath = window.location.pathname;
                
                // Check if the current path matches the route pattern
                if (routeName.includes('*')) {
                    const baseRoute = routeName.replace('.*', '');
                    return currentPath.startsWith(routes[baseRoute] || `/${baseRoute.replace('.', '/')}`);
                } else {
                    const routePath = routes[routeName] || `/${routeName.replace('.', '/')}`;
                    return currentPath === routePath;
                }
            }
        };
    }
    
    let url = routes[name] || `/${(name || '').replace('.', '/')}`;
    
    // Add parameters
    Object.keys(params).forEach(key => {
        const value = params[key];
        url = url.replace(`{${key}}`, value);
    });
    
    // Remove any remaining placeholders
    url = url.replace(/\{[^}]+\}/g, '');
    
    return url;
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
        const app = createApp({ render: () => h(App, props) })
            .use(plugin);
        
        // Make route function available globally in all Vue components
        app.config.globalProperties.route = window.route;
        
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
