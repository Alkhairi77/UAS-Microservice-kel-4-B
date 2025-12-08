import './bootstrap';

// Import Alpine
import Alpine from 'alpinejs';

// Alpine global functions
window.Alpine = Alpine;

// Global data function untuk Alpine
window.data = function() {
    return {
        dark: false,
        isSideMenuOpen: false,
        isSidebarCollapsed: false,
        
        toggleTheme() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.dark);
        },
        
        toggleSideMenu() {
            this.isSideMenuOpen = !this.isSideMenuOpen;
        },
        
        closeSideMenu() {
            this.isSideMenuOpen = false;
        },
        
        toggleSidebar() {
            this.isSidebarCollapsed = !this.isSidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.isSidebarCollapsed ? 'true' : 'false');
            console.log('Sidebar toggled:', this.isSidebarCollapsed); // Debug log
        },
        
        init() {
            // Load theme preference
            const theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                this.dark = true;
                document.documentElement.classList.add('dark');
            }
            
            // Load sidebar preference
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
            if (sidebarCollapsed === 'true') {
                this.isSidebarCollapsed = true;
            }
            
            console.log('Alpine initialized:', { dark: this.dark, sidebarCollapsed: this.isSidebarCollapsed }); // Debug log
        }
    }
};

// Start Alpine
Alpine.start();
