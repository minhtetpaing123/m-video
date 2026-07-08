// resources/js/main.js
import { SearchManager } from './modules/SearchManager.js';
import { ThemeManager } from './modules/ThemeManager.js';
import { UserManager } from './modules/UserManager.js';

class HeaderApp {
    constructor() {
        this.modules = {
            search: null,
            theme: null,
            user: null
        };
    }

    async init() {
        console.log('🚀 Initializing Header App...');
        
        // Initialize modules
        this.modules.search = new SearchManager();
        await this.modules.search.init();
        
        this.modules.theme = new ThemeManager();
        await this.modules.theme.init();
        
        this.modules.user = new UserManager();
        await this.modules.user.init();
        
        // Set up global shortcuts
        this.setupShortcuts();
        
        console.log('✅ Header App initialized');
    }

    setupShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl+K to open search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (this.modules.search) {
                    this.modules.search.open();
                }
            }
            
            // / (slash) to open search
            if (e.key === '/' && !this.modules.search.state.isOpen) {
                e.preventDefault();
                if (this.modules.search) {
                    this.modules.search.open();
                }
            }
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.headerApp = new HeaderApp();
        window.headerApp.init();
    });
} else {
    window.headerApp = new HeaderApp();
    window.headerApp.init();
}