<script>
    (() => {
        const storageKey = 'theme';
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        let storedTheme = null;

        try {
            storedTheme = localStorage.getItem(storageKey);
        } catch (error) {
            storedTheme = null;
        }

        const theme = storedTheme || (prefersDark ? 'dark' : 'light');

        document.documentElement.classList.toggle('dark', theme === 'dark');
        document.documentElement.dataset.theme = theme;
    })();
</script>
