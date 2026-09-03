import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const getInitialTheme = (): string => {
    if (typeof window === 'undefined') return 'light';
    const stored = localStorage.getItem('appearance');
    if (stored === 'light' || stored === 'dark') {
        return stored;
    }
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

export const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: getInitialTheme(),
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#BE185D', // Sensual Deep Rose / Velvet Wine
                    secondary: '#D97706', // Warm Amber & Candlelight Gold
                    accent: '#F43F5E', // Exotic Passion Blossom
                    background: '#FFF5F7',
                    surface: '#FFFFFF',
                    'on-background': '#1F121E',
                    'on-surface': '#1F121E',
                    'on-primary': '#FFFFFF',
                    'on-secondary': '#FFFFFF',
                    info: '#0284C7',
                    success: '#10B981',
                    warning: '#F59E0B',
                    error: '#E11D48',
                },
            },
            dark: {
                dark: true,
                colors: {
                    primary: '#FB7185', // Luminous Rose
                    secondary: '#FBBF24', // Luminous Warm Gold
                    accent: '#F43F5E',
                    background: '#110713', // Deep Midnight Plum
                    surface: '#1E0E22', // Rich Velvet Aubergine
                    'on-background': '#FFF1F2',
                    'on-surface': '#FFF1F2',
                    'on-primary': '#110713',
                    'on-secondary': '#000000',
                    info: '#38BDF8',
                    success: '#34D399',
                    warning: '#FBBF24',
                    error: '#FB7185',
                },
            },
        },
    },
});