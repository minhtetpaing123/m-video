// resources/js/components/category.js

class CategoryNavigation {
    constructor() {
        this.categoryContainer = document.querySelector('.category-scroll');
        this.categoryItems = document.querySelectorAll('.category-item');
        this.scrollIndicators = {
            left: document.querySelector('.scroll-indicator.left'),
            right: document.querySelector('.scroll-indicator.right')
        };
        
        this.init();
    }

    init() {
        this.setupScrollIndicators();
        this.setupCategoryClicks();
        this.setupHoverEffects();
        this.setupResponsive();
        this.checkScrollPosition();
    }

    setupScrollIndicators() {
        if (!this.categoryContainer) return;

        // Create scroll indicators if they don't exist
        if (!this.scrollIndicators.left) {
            this.createScrollIndicators();
        }

        // Update indicators on scroll
        this.categoryContainer.addEventListener('scroll', () => {
            this.checkScrollPosition();
        });

        // Add scroll buttons functionality
        document.querySelector('.scroll-btn.left')?.addEventListener('click', () => {
            this.scrollLeft();
        });

        document.querySelector('.scroll-btn.right')?.addEventListener('click', () => {
            this.scrollRight();
        });
    }

    createScrollIndicators() {
        const container = this.categoryContainer.parentElement;
        
        // Left indicator
        const leftIndicator = document.createElement('div');
        leftIndicator.className = 'scroll-indicator left';
        leftIndicator.innerHTML = `
            <button class="scroll-btn left">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        `;
        
        // Right indicator
        const rightIndicator = document.createElement('div');
        rightIndicator.className = 'scroll-indicator right';
        rightIndicator.innerHTML = `
            <button class="scroll-btn right">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        `;

        container.appendChild(leftIndicator);
        container.appendChild(rightIndicator);
        
        // Update references
        this.scrollIndicators = {
            left: leftIndicator,
            right: rightIndicator
        };
    }

    checkScrollPosition() {
        if (!this.categoryContainer || !this.scrollIndicators.left) return;

        const { scrollLeft, scrollWidth, clientWidth } = this.categoryContainer;
        
        // Show/hide left indicator
        if (scrollLeft > 10) {
            this.scrollIndicators.left.classList.add('visible');
        } else {
            this.scrollIndicators.left.classList.remove('visible');
        }
        
        // Show/hide right indicator
        if (scrollLeft < scrollWidth - clientWidth - 10) {
            this.scrollIndicators.right.classList.add('visible');
        } else {
            this.scrollIndicators.right.classList.remove('visible');
        }
    }

    scrollLeft() {
        if (!this.categoryContainer) return;
        this.categoryContainer.scrollBy({ left: -200, behavior: 'smooth' });
    }

    scrollRight() {
        if (!this.categoryContainer) return;
        this.categoryContainer.scrollBy({ left: 200, behavior: 'smooth' });
    }

    setupCategoryClicks() {
        this.categoryItems.forEach(item => {
            item.addEventListener('click', (e) => {
                this.handleCategoryClick(e, item);
            });
        });
    }

    handleCategoryClick(e, clickedItem) {
        // Prevent default only if it's not a link
        if (!clickedItem.href) {
            e.preventDefault();
        }

        // Remove active class from all items
        this.categoryItems.forEach(item => {
            item.classList.remove('active');
        });

        // Add active class to clicked item
        clickedItem.classList.add('active');

        // Add click animation
        this.animateClick(clickedItem);

        // If it's a link, navigate after animation
        if (clickedItem.href) {
            setTimeout(() => {
                window.location.href = clickedItem.href;
            }, 300);
        }
    }

    animateClick(element) {
        // Add ripple effect
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.7);
            transform: scale(0);
            animation: ripple 0.6s linear;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            pointer-events: none;
        `;

        element.appendChild(ripple);

        // Add scale animation
        element.style.transform = 'scale(0.95)';
        setTimeout(() => {
            element.style.transform = '';
            ripple.remove();
        }, 200);
    }

    setupHoverEffects() {
        this.categoryItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                this.animateHoverEnter(item);
            });

            item.addEventListener('mouseleave', () => {
                this.animateHoverLeave(item);
            });
        });
    }

    animateHoverEnter(element) {
        if (!element.classList.contains('active')) {
            element.style.transform = 'translateY(-2px)';
        }
        
        // Add subtle glow effect
        const glow = document.createElement('div');
        glow.className = 'category-glow';
        glow.style.cssText = `
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            background: linear-gradient(45deg, rgba(239, 68, 68, 0.1), rgba(219, 39, 119, 0.1));
            filter: blur(4px);
            opacity: 0;
            animation: fadeIn 0.3s ease forwards;
            pointer-events: none;
            z-index: -1;
        `;
        
        element.appendChild(glow);
    }

    animateHoverLeave(element) {
        if (!element.classList.contains('active')) {
            element.style.transform = '';
        }
        
        const glow = element.querySelector('.category-glow');
        if (glow) {
            glow.style.animation = 'fadeOut 0.3s ease forwards';
            setTimeout(() => glow.remove(), 300);
        }
    }

    setupResponsive() {
        // Handle window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.checkScrollPosition();
            }, 100);
        });

        // Add CSS for ripple animation
        if (!document.querySelector('#category-animations')) {
            const style = document.createElement('style');
            style.id = 'category-animations';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
                
                @keyframes fadeOut {
                    to {
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    // Public method to set active category by name
    setActiveCategory(categoryName) {
        this.categoryItems.forEach(item => {
            const nameElement = item.querySelector('.category-name');
            if (nameElement && nameElement.textContent.trim() === categoryName) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Public method to add new category
    addCategory(name, icon, href = '#', isActive = false) {
        const categoryHTML = `
            <a href="${href}" class="category-item ${isActive ? 'active' : ''}">
                <span class="material-icons-outlined category-icon">${icon}</span>
                <span class="category-name">${name}</span>
            </a>
        `;

        const container = document.querySelector('.category-items');
        if (container) {
            container.insertAdjacentHTML('beforeend', categoryHTML);
            
            // Reinitialize with new items
            this.categoryItems = document.querySelectorAll('.category-item');
            this.setupCategoryClicks();
            this.setupHoverEffects();
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const categoryNav = new CategoryNavigation();
    
    // Expose to global scope if needed
    window.CategoryNavigation = categoryNav;
});

// Export for use in app.js
export default CategoryNavigation;