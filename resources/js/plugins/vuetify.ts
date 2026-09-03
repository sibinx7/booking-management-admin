import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

export const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                colors: {
                    primary: '#6366F1',
                    secondary: '#0D9488',
                    accent: '#8B5CF6',
                    error: '#EF4444',
                    info: '#3B82F6',
                    success: '#10B981',
                    warning: '#F59E0B',
                    surface: '#FFFFFF',
                    background: '#F8FAFC',
                },
            },
            dark: {
                colors: {
                    primary: '#818CF8',
                    secondary: '#14B8A6',
                    accent: '#A78BFA',
                    surface: '#1E293B',
                    background: '#0F172A',
                },
            },
        },
    },
});