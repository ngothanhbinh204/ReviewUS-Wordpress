/**
 * Header Menu Interactions with Click Events and Tabs
 */

document.addEventListener('DOMContentLoaded', function() {

    // Body scroll lock functions
    function lockBodyScroll() {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = scrollbarWidth + 'px';
    }

    function unlockBodyScroll() {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            const isOpen = !mobileMenu.classList.contains('hidden');

            if (isOpen) {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                unlockBodyScroll(); // Unlock scroll when closing menu
            } else {
                mobileMenu.classList.remove('hidden');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                lockBodyScroll(); // Lock scroll when opening menu
            }
        });
    }

    // Desktop Mega Menu Click Events
    const megaMenuBtns = document.querySelectorAll('.mega-menu-btn');
    const megaMenus = document.querySelectorAll('.mega-menu');
    let currentOpenMenu = null;

    megaMenuBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const targetId = this.getAttribute('data-menu-target');
            const targetMenu = document.querySelector(`[data-menu="${targetId}"]`);
            const arrow = this.querySelector('.mega-menu-arrow');

            // Close all other menus
            megaMenus.forEach(menu => {
                if (menu !== targetMenu) {
                    menu.classList.add('hidden');
                }
            });

            // Reset all arrows
            megaMenuBtns.forEach(otherBtn => {
                if (otherBtn !== this) {
                    otherBtn.setAttribute('aria-expanded', 'false');
                    otherBtn.querySelector('.mega-menu-arrow').classList.remove('rotate-180');
                }
            });

            if (targetMenu) {
                const isOpen = !targetMenu.classList.contains('hidden');

                if (isOpen) {
                    // Close current menu
                    targetMenu.classList.add('hidden');
                    this.setAttribute('aria-expanded', 'false');
                    arrow.classList.remove('rotate-180');
                    currentOpenMenu = null;
                } else {
                    // Open target menu
                    targetMenu.classList.remove('hidden');
                    this.setAttribute('aria-expanded', 'true');
                    arrow.classList.add('rotate-180');
                    currentOpenMenu = targetMenu;

                    // Initialize tabs for this menu
                    initializeMenuTabs(targetMenu, targetId);
                }
            }
        });
    });

    // Initialize Menu Tabs
    function initializeMenuTabs(menuElement, menuId) {
        const tabContainer = menuElement.querySelector(`#mega-menu-tabs-${menuId}`);
        const contentContainer = menuElement.querySelector(`#mega-menu-content-${menuId}`);
        const tabDataElements = menuElement.querySelectorAll('.tab-data');
        const tabPanels = menuElement.querySelectorAll('.tab-panel');

        if (!tabContainer || !contentContainer || tabDataElements.length === 0) return;

        // Clear existing tabs
        tabContainer.innerHTML = '';

        // Create tab buttons from tab-data elements
        let firstTabId = null;
        tabDataElements.forEach((tabData, index) => {
            const tabId = tabData.getAttribute('data-tab-id');
            const tabTitle = tabData.getAttribute('data-tab-title');

            if (index === 0) firstTabId = tabId;

            const tabButton = document.createElement('button');
            tabButton.className = `tab-btn whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 ${
                index === 0
                ? 'border-primary text-primary'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            }`;
            tabButton.setAttribute('data-tab-target', tabId);
            tabButton.textContent = tabTitle;

            tabButton.addEventListener('click', function() {
                switchTab(menuElement, tabId);
            });

            tabContainer.appendChild(tabButton);
        });

        // Show first tab by default
        if (firstTabId) {
            switchTab(menuElement, firstTabId);
        }
    }

    // Switch Tab Function
    function switchTab(menuElement, activeTabId) {
        const tabButtons = menuElement.querySelectorAll('.tab-btn');
        const tabPanels = menuElement.querySelectorAll('.tab-panel');

        // Update tab buttons
        tabButtons.forEach(btn => {
            const isActive = btn.getAttribute('data-tab-target') === activeTabId;
            if (isActive) {
                btn.className = btn.className.replace(/border-transparent|text-gray-500|hover:text-gray-700|hover:border-gray-300/g, '');
                btn.className += ' border-primary text-primary';
            } else {
                btn.className = btn.className.replace(/border-primary|text-primary/g, '');
                btn.className += ' border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
            }
        });

        // Update tab panels
        tabPanels.forEach(panel => {
            const isActive = panel.getAttribute('data-tab') === activeTabId;
            if (isActive) {
                panel.classList.remove('hidden');
            } else {
                panel.classList.add('hidden');
            }
        });
    }

    // Mobile submenu toggles
    const mobileSubmenuToggles = document.querySelectorAll('.mobile-submenu-toggle');
    mobileSubmenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const submenu = this.closest('.menu-item').querySelector('.mobile-submenu');
            const arrow = this.querySelector('svg');

            if (submenu) {
                const isOpen = !submenu.classList.contains('hidden');

                if (isOpen) {
                    submenu.classList.add('hidden');
                    submenu.style.maxHeight = '0px';
                    arrow.classList.remove('rotate-180');
                } else {
                    submenu.classList.remove('hidden');
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    arrow.classList.add('rotate-180');
                }
            }
        });
    });

    // Search overlay functionality
    const searchBtn = document.getElementById('searchBtn');
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    const searchOverlay = document.getElementById('searchOverlay');
    const closeSearch = document.getElementById('closeSearch');

    function openSearch() {
        if (searchOverlay) {
            searchOverlay.classList.remove('hidden');
            const searchInput = searchOverlay.querySelector('input[type="search"]');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        }
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', openSearch);
    }

    if (mobileSearchBtn) {
        mobileSearchBtn.addEventListener('click', openSearch);
    }

    if (closeSearch && searchOverlay) {
        closeSearch.addEventListener('click', function() {
            searchOverlay.classList.add('hidden');
        });
    }

    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        // Close mega menus
        if (currentOpenMenu && !currentOpenMenu.contains(e.target)) {
            const parentTrigger = document.querySelector(`[data-menu-target]`);
            if (parentTrigger && !parentTrigger.contains(e.target)) {
                megaMenus.forEach(menu => menu.classList.add('hidden'));
                megaMenuBtns.forEach(btn => {
                    btn.setAttribute('aria-expanded', 'false');
                    btn.querySelector('.mega-menu-arrow').classList.remove('rotate-180');
                });
                currentOpenMenu = null;
            }
        }

        // Close mobile menu
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                unlockBodyScroll(); // Unlock scroll when closing
            }
        }

        // Close search overlay
        if (searchOverlay && !searchOverlay.classList.contains('hidden')) {
            if (e.target === searchOverlay) {
                searchOverlay.classList.add('hidden');
            }
        }
    });

    // Close menus on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close mega menus
            megaMenus.forEach(menu => menu.classList.add('hidden'));
            megaMenuBtns.forEach(btn => {
                btn.setAttribute('aria-expanded', 'false');
                btn.querySelector('.mega-menu-arrow').classList.remove('rotate-180');
            });
            currentOpenMenu = null;

            // Close search overlay
            if (searchOverlay && !searchOverlay.classList.contains('hidden')) {
                searchOverlay.classList.add('hidden');
            }
        }
    });

    // Header scroll behavior - DO NOT close menus on scroll
    let lastScrollTop = 0;
    const header = document.getElementById('main-header');

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down - hide header
            header.style.transform = 'translateY(-100%)';
            // DO NOT close menus here anymore
        } else {
            // Scrolling up - show header
            header.style.transform = 'translateY(0)';
        }

        lastScrollTop = scrollTop;
    });

    // Mobile submenu full-screen overlays
    const mobileSubmenuOpens = document.querySelectorAll('.mobile-submenu-open');
    mobileSubmenuOpens.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const parent = this.closest('.menu-item-has-children');
            const overlay = parent?.querySelector('.mobile-submenu-overlay');

            if (overlay) {
                overlay.classList.remove('hidden');
                // Body scroll is already locked from main menu, no need to lock again
            }
        });
    });

    // Mobile submenu back buttons
    const mobileSubmenuBacks = document.querySelectorAll('.mobile-submenu-back');
    mobileSubmenuBacks.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const overlay = this.closest('.mobile-submenu-overlay');
            if (overlay) {
                overlay.classList.add('hidden');

                // Close all nested submenus
                overlay.querySelectorAll('.mobile-submenu-nested').forEach(nested => {
                    nested.classList.add('hidden');
                });
                
                // Body scroll remains locked because main menu is still open
            }
        });
    });

    // Mobile submenu expand (level 2 with level 3)
    const mobileSubmenuExpands = document.querySelectorAll('.mobile-submenu-expand');
    mobileSubmenuExpands.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const parent = this.parentElement;
            const nested = parent.querySelector('.mobile-submenu-nested');
            const arrow = this.querySelector('svg');

            if (nested) {
                const isOpen = !nested.classList.contains('hidden');
                nested.classList.toggle('hidden');
                if (arrow) arrow.classList.toggle('rotate-180');
            }
        });
    });

});
