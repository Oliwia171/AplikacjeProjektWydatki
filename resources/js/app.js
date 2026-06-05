import './bootstrap';

import Alpine from 'alpinejs';

const themeStorageKey = 'theme';
const darkMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

const storedTheme = () => {
    try {
        return localStorage.getItem(themeStorageKey);
    } catch (error) {
        return null;
    }
};

const storeTheme = (theme) => {
    try {
        localStorage.setItem(themeStorageKey, theme);
    } catch (error) {
        // Keep the current page theme even if persistence is unavailable.
    }
};

const systemTheme = () => (darkMediaQuery.matches ? 'dark' : 'light');
const currentTheme = () => storedTheme() || systemTheme();

const applyTheme = (theme) => {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.documentElement.dataset.theme = theme;
};

applyTheme(currentTheme());

window.Alpine = Alpine;

Alpine.data('themeToggle', () => ({
    theme: currentTheme(),

    init() {
        this.apply(this.theme);

        darkMediaQuery.addEventListener('change', () => {
            if (!storedTheme()) {
                this.theme = systemTheme();
                this.apply(this.theme);
            }
        });
    },

    toggle() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        storeTheme(this.theme);
        this.apply(this.theme);
    },

    apply(theme) {
        applyTheme(theme);
    },
}));

Alpine.start();
