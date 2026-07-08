// resources/js/home/comment.js - Horizontal Progress Bar Version

// Comment System - YouTube Style with Horizontal Progress Bar
const CommentSystem = {
    initialized: false,
    currentModal: null,
    currentVideoId: null,
    isLoggedIn: false,
    currentUser: null,
    csrfToken: null,
    currentPage: 1,
    totalComments: 0,
    hasMoreComments: true,
    isLoading: false,
    isInitialLoad: true,
    scrollThreshold: 100,
    
    init: function() {
        if (this.initialized) return;
        
        console.log('Modern YouTube Comment system initializing...');
        
        try {
            // Get CSRF token from meta tag
            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            // Check Laravel auth status
            this.checkLaravelAuthStatus();
            
            // Setup initial comment buttons
            this.setupCommentButtons();
            
            // Setup global event listeners
            this.setupGlobalListeners();
            
            this.initialized = true;
            console.log('Comment system initialized successfully. User logged in:', this.isLoggedIn);
            
        } catch (error) {
            console.error('Error initializing comment system:', error);
        }
    },
    
    checkLaravelAuthStatus: function() {
        // Check meta tag for auth status
        const authMeta = document.querySelector('meta[name="auth-status"]');
        if (authMeta) {
            this.isLoggedIn = authMeta.content === 'authenticated';
        }
        
        // Check window.Laravel object
        if (window.Laravel) {
            this.isLoggedIn = window.Laravel.isAuthenticated || false;
            this.currentUser = window.Laravel.user || null;
        }
        
        // Check user data meta
        const userMeta = document.querySelector('meta[name="user-data"]');
        if (userMeta && !this.currentUser) {
            try {
                this.currentUser = JSON.parse(userMeta.content);
            } catch (e) {
                console.warn('Failed to parse user data:', e);
            }
        }
        
        return this.isLoggedIn;
    },
    
    setupGlobalListeners: function() {
        // Global click handler for comment icons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.comment-icon-btn')) {
                const btn = e.target.closest('.comment-icon-btn');
                e.preventDefault();
                e.stopPropagation();
                
                const videoId = btn.dataset.videoId;
                const videoCard = btn.closest('.video-card');
                const videoTitle = videoCard?.querySelector('.video-title')?.textContent || 'Video';
                
                this.showComments(videoId, videoTitle);
            }
        });
        
        // Listen for auth changes via custom events
        document.addEventListener('auth:changed', (e) => {
            if (e.detail) {
                this.handleAuthUpdate(e.detail.authenticated, e.detail.user);
            }
        });
    },
    
    handleAuthUpdate: function(isAuthenticated, userData = null) {
        this.isLoggedIn = isAuthenticated;
        this.currentUser = userData;
        this.updateUIForLoginStatus();
        
        if (isAuthenticated) {
            this.showYouTubeNotification('Welcome back! You can now comment.');
        } else {
            this.showYouTubeNotification('Logged out. Comments are now read-only.');
        }
    },
    
    setupCommentButtons: function() {
        // Setup for existing buttons
        document.querySelectorAll('.comment-icon-btn').forEach(btn => {
            if (!btn.hasAttribute('data-listener')) {
                btn.setAttribute('data-listener', 'true');
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const videoId = btn.dataset.videoId;
                    const videoCard = btn.closest('.video-card');
                    const videoTitle = videoCard?.querySelector('.video-title')?.textContent || 'Video';
                    
                    this.showComments(videoId, videoTitle);
                });
            }
        });
    },
    
    showComments: function(videoId, videoTitle) {
        console.log('Showing comments for:', videoId, videoTitle);
        
        // Reset pagination
        this.currentPage = 1;
        this.totalComments = 0;
        this.hasMoreComments = true;
        this.isLoading = false;
        this.isInitialLoad = true;
        
        // Store current video ID
        this.currentVideoId = videoId;
        
        // Create modal
        this.createModal(videoTitle);
        
        // Load first page of comments
        this.loadComments();
        
        // Show modal
        const modal = document.getElementById('videoCommentsModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Add animation
        modal.style.animation = 'fadeIn 0.3s ease';
    },
    
    createModal: function(videoTitle) {
        // Remove existing modal if any
        const existing = document.getElementById('videoCommentsModal');
        if (existing) existing.remove();
        
        const modalHTML = `
            <div class="video-comments-modal" id="videoCommentsModal">
                <div class="modal-overlay"></div>
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 class="modal-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#065fd4">
                                <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                            </svg>
                            ${this.escapeHtml(videoTitle)}
                        </h3>
                        <button class="modal-close-btn" id="modalCloseBtn" aria-label="Close comments">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-content">
                        <div class="comments-section">
                            <div class="comments-header">
                                <h4 class="comments-title">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#065fd4">
                                        <path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                                    </svg>
                                    Comments
                                    <span class="comments-count">(0)</span>
                                </h4>
                                <div class="comments-sort">
                                    <select id="commentsSort" aria-label="Sort comments">
                                        <option value="newest">Newest first</option>
                                        <option value="oldest">Oldest first</option>
                                        <option value="popular">Top comments</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="comments-container">
                                <div class="comments-list" id="commentsList">
                                    <!-- Comments will be loaded here -->
                                </div>
                                
                                <!-- Horizontal Progress Bar -->
                                <div class="horizontal-progress" id="horizontalProgress">
                                    <div class="horizontal-progress-bar"></div>
                                </div>
                                
                                <!-- End of Comments -->
                                <div class="end-of-comments" id="endOfComments">
                                    No more comments to load
                                </div>
                            </div>
                            
                            <!-- Comment Form -->
                            <div class="comment-form-wrapper">
                                <div class="comment-form-container">
                                    <div class="comment-input-wrapper">
                                        <div class="user-avatar">?</div>
                                        <div class="input-container">
                                            <textarea 
                                                class="comment-input" 
                                                placeholder="Please login to comment..." 
                                                rows="1"
                                                maxlength="500"
                                                aria-label="Add a comment"
                                                disabled
                                            ></textarea>
                                            <div class="comment-actions">
                                                <button class="cancel-btn" type="button">Cancel</button>
                                                <button class="submit-btn" type="submit">Login to Comment</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Store reference
        this.currentModal = document.getElementById('videoCommentsModal');
        
        // Setup events
        this.setupModalEvents();
        
        // Setup scroll listener for infinite scroll
        this.setupInfiniteScroll();
        
        // Update UI based on login status
        this.updateUIForLoginStatus();
    },
    
    setupModalEvents: function() {
        const modal = this.currentModal;
        if (!modal) return;
        
        const closeBtn = modal.querySelector('#modalCloseBtn');
        const overlay = modal.querySelector('.modal-overlay');
        const cancelBtn = modal.querySelector('.cancel-btn');
        const submitBtn = modal.querySelector('.submit-btn');
        const commentInput = modal.querySelector('.comment-input');
        const sortSelect = modal.querySelector('#commentsSort');
        
        // Close modal
        closeBtn.addEventListener('click', () => this.hideModal());
        overlay.addEventListener('click', () => this.hideModal());
        
        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                this.hideModal();
            }
        });
        
        // Comment form events (only if logged in)
        if (commentInput && this.isLoggedIn) {
            commentInput.addEventListener('focus', () => {
                const actions = commentInput.closest('.input-container').querySelector('.comment-actions');
                actions.classList.add('show');
                commentInput.style.borderBottomColor = '#065fd4';
            });
            
            commentInput.addEventListener('blur', () => {
                if (!commentInput.value.trim()) {
                    commentInput.style.borderBottomColor = 'transparent';
                }
            });
            
            commentInput.addEventListener('input', (e) => {
                const hasText = e.target.value.trim().length > 0;
                if (submitBtn) {
                    submitBtn.disabled = !hasText;
                }
                
                // Auto-resize
                e.target.style.height = 'auto';
                const newHeight = Math.min(e.target.scrollHeight, 120);
                e.target.style.height = newHeight + 'px';
            });
            
            // Enter to submit
            commentInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                    if (submitBtn && !submitBtn.disabled) {
                        this.submitComment();
                    }
                }
            });
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                if (commentInput && this.isLoggedIn) {
                    commentInput.value = '';
                    commentInput.style.height = 'auto';
                    commentInput.style.borderBottomColor = 'transparent';
                    if (submitBtn) submitBtn.disabled = true;
                    commentInput.closest('.input-container').querySelector('.comment-actions').classList.remove('show');
                }
            });
        }
        
        // Sort comments
        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => {
                this.currentPage = 1;
                this.hasMoreComments = true;
                this.loadComments(true); // Reload with new sort
            });
        }
    },
    
    setupInfiniteScroll: function() {
        const commentsList = document.getElementById('commentsList');
        if (!commentsList) return;
        
        // Throttle scroll events for performance
        let scrollTimeout;
        commentsList.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.handleInfiniteScroll();
            }, 100);
        });
    },
    
    handleInfiniteScroll: function() {
        if (this.isLoading || !this.hasMoreComments) return;
        
        const commentsList = document.getElementById('commentsList');
        if (!commentsList) return;
        
        // Check if we're near the bottom
        const scrollPosition = commentsList.scrollTop + commentsList.clientHeight;
        const scrollHeight = commentsList.scrollHeight;
        
        if (scrollHeight - scrollPosition <= this.scrollThreshold) {
            this.loadMoreComments();
        }
    },
    
    loadComments: function(isSortChange = false) {
        const commentsList = document.getElementById('commentsList');
        if (!commentsList || !this.currentVideoId) return;
        
        // Show initial loading
        if (this.isInitialLoad) {
            commentsList.innerHTML = `
                <div class="loading-comments">
                    <div class="loading-spinner"></div>
                    <p>Loading comments...</p>
                </div>
            `;
        }
        
        this.isLoading = true;
        
        // Simulate API call with delay
        setTimeout(() => {
            const comments = this.getSampleComments(this.currentPage);
            const totalComments = 15; // Simulated total
            
            if (isSortChange || this.isInitialLoad) {
                // Replace all comments
                if (comments.length === 0) {
                    this.showNoComments();
                } else {
                    commentsList.innerHTML = this.generateCommentsHTML(comments);
                    this.setupCommentInteractions();
                }
                this.isInitialLoad = false;
            } else {
                // Append new comments
                const newCommentsHTML = this.generateCommentsHTML(comments);
                commentsList.insertAdjacentHTML('beforeend', newCommentsHTML);
                
                // Setup interactions for new comments
                const newCommentElements = commentsList.querySelectorAll('.comment-item');
                newCommentElements.forEach(element => {
                    this.setupCommentInteractionsForElement(element);
                });
            }
            
            // Update total count
            this.totalComments += comments.length;
            this.updateCommentsCount(this.totalComments);
            
            // Check if we have more comments
            this.hasMoreComments = this.totalComments < totalComments;
            
            // Hide progress bar
            this.hideProgressBar();
            
            // Show/hide end message
            if (!this.hasMoreComments && this.totalComments > 0) {
                this.showEndOfComments();
            } else {
                this.hideEndOfComments();
            }
            
            this.isLoading = false;
            
        }, 800);
    },
    
    loadMoreComments: function() {
        if (this.isLoading || !this.hasMoreComments) return;
        
        this.currentPage++;
        this.showProgressBar();
        this.loadComments();
    },
    
    showProgressBar: function() {
        const progressBar = document.getElementById('horizontalProgress');
        const endMessage = document.getElementById('endOfComments');
        
        if (progressBar) {
            progressBar.classList.add('active');
        }
        if (endMessage) {
            endMessage.classList.remove('active');
        }
    },
    
    hideProgressBar: function() {
        const progressBar = document.getElementById('horizontalProgress');
        if (progressBar) {
            progressBar.classList.remove('active');
        }
    },
    
    showEndOfComments: function() {
        const endMessage = document.getElementById('endOfComments');
        if (endMessage && this.totalComments > 5) {
            endMessage.classList.add('active');
        }
    },
    
    hideEndOfComments: function() {
        const endMessage = document.getElementById('endOfComments');
        if (endMessage) {
            endMessage.classList.remove('active');
        }
    },
    
    getSampleComments: function(page = 1) {
        // Simulate paginated comments
        const allComments = [
            {
                id: 1,
                user: { id: 101, name: 'Gaming Pro', email: 'gaming@example.com' },
                content: 'Amazing gameplay! That 5-man wipeout was incredible! 🎮 Perfect timing on all the ultimates.',
                created_at: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
                likes_count: 1245,
                is_liked: false,
                replies: []
            },
            {
                id: 2,
                user: { id: 102, name: 'ML Fan', email: 'mlfan@example.com' },
                content: 'Which hero were you using? The combo was perfect!',
                created_at: new Date(Date.now() - 3 * 60 * 60 * 1000).toISOString(),
                likes_count: 889,
                is_liked: true,
                replies: []
            },
            {
                id: 3,
                user: { id: 103, name: 'Strategy Master', email: 'strategy@example.com' },
                content: 'Great teamwork! The positioning was on point. Keep up the good work! 🔥',
                created_at: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString(),
                likes_count: 656,
                is_liked: false,
                replies: []
            },
            {
                id: 4,
                user: { id: 104, name: 'Esports Fan', email: 'esports@example.com' },
                content: 'This deserves to be in the top plays compilation! 🔥',
                created_at: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString(),
                likes_count: 489,
                is_liked: false,
                replies: []
            },
            {
                id: 5,
                user: { id: 105, name: 'Mobile Gamer', email: 'mobile@example.com' },
                content: 'Been playing ML for 3 years and this is one of the best plays I\'ve seen!',
                created_at: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString(),
                likes_count: 342,
                is_liked: false,
                replies: []
            },
            {
                id: 6,
                user: { id: 106, name: 'Strategy Coach', email: 'coach@example.com' },
                content: 'The map awareness here is next level. Well done!',
                created_at: new Date(Date.now() - 3 * 24 * 60 * 60 * 1000).toISOString(),
                likes_count: 198,
                is_liked: false,
                replies: []
            },
            {
                id: 7,
                user: { id: 107, name: 'Pro Player', email: 'pro@example.com' },
                content: 'Clean execution! What rank is this gameplay from?',
                created_at: new Date(Date.now() - 4 * 24 * 60 * 60 * 1000).toISOString(),
                likes_count: 567,
                is_liked: false,
                replies: []
            },
            {
                id: 8,
                user: { id: 108, name: 'Game Analyst', email: 'analyst@example.com' },
                content: 'The ward placement at 2:34 was key to this play!',
                created_at: new Date(Date.now() - 5 * 24 * 60 * 60 * 1000).toISOString(),
                likes_count: 234,
                is_liked: false,
                replies: []
            }
        ];
        
        // Paginate: 3 comments per page
        const startIndex = (page - 1) * 3;
        const endIndex = startIndex + 3;
        
        return allComments.slice(startIndex, endIndex);
    },
    
    showNoComments: function() {
        const commentsList = document.getElementById('commentsList');
        if (!commentsList) return;
        
        commentsList.innerHTML = `
            <div class="no-comments">
                <div class="no-comments-icon">💬</div>
                <p style="color: #606060; font-size: 14px;">No comments yet</p>
                <p style="color: #909090; font-size: 13px; margin-top: 4px;">Be the first to comment</p>
            </div>
        `;
        
        this.updateCommentsCount(0);
        this.hideProgressBar();
        this.hideEndOfComments();
    },
    
    updateUIForLoginStatus: function() {
        const modal = this.currentModal;
        if (!modal) return;
        
        const commentInput = modal.querySelector('.comment-input');
        const submitBtn = modal.querySelector('.submit-btn');
        const userAvatar = modal.querySelector('.user-avatar');
        
        if (this.isLoggedIn && this.currentUser) {
            // User is logged in - enable commenting
            if (commentInput) {
                commentInput.disabled = false;
                commentInput.placeholder = 'Add a public comment...';
            }
            
            // Set user avatar
            if (userAvatar && this.currentUser) {
                const initials = this.getUserInitials(this.currentUser.name || this.currentUser.email || 'User');
                userAvatar.textContent = initials;
                userAvatar.style.background = this.getYouTubeAvatarColor(this.currentUser.name || this.currentUser.email);
            }
            
            // Update submit button
            if (submitBtn) {
                submitBtn.textContent = 'Comment';
                submitBtn.disabled = true;
                submitBtn.onclick = (e) => {
                    e.preventDefault();
                    this.submitComment();
                };
            }
            
            // Hide login prompt
            const loginPrompt = modal.querySelector('.login-prompt');
            if (loginPrompt) {
                loginPrompt.style.display = 'none';
            }
        } else {
            // User is not logged in - read only mode
            if (commentInput) {
                commentInput.disabled = true;
                commentInput.placeholder = 'Please login to comment...';
                commentInput.value = '';
            }
            
            // Reset avatar
            if (userAvatar) {
                userAvatar.textContent = '?';
                userAvatar.style.background = '#606060';
            }
            
            // Update submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Login to Comment';
                submitBtn.onclick = (e) => {
                    e.preventDefault();
                    window.location.href = '/login';
                };
            }
            
            // Show login prompt
            this.showLoginPrompt();
        }
    },
    
    showLoginPrompt: function() {
        const modal = this.currentModal;
        if (!modal || this.isLoggedIn) return;
        
        let loginPrompt = modal.querySelector('.login-prompt');
        
        if (!loginPrompt) {
            loginPrompt = document.createElement('div');
            loginPrompt.className = 'login-prompt';
            loginPrompt.innerHTML = `
                <div class="login-prompt-content">
                    <div class="login-prompt-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#065fd4">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                    </div>
                    <div class="login-prompt-text">
                        <p>Login to join the conversation</p>
                        <div class="login-prompt-buttons">
                            <a href="/login" class="btn login-btn">Login</a>
                            <a href="/register" class="btn signup-btn">Sign Up</a>
                        </div>
                    </div>
                </div>
            `;
            
            const commentFormWrapper = modal.querySelector('.comment-form-wrapper');
            if (commentFormWrapper) {
                commentFormWrapper.insertAdjacentElement('beforebegin', loginPrompt);
            }
        }
        
        loginPrompt.style.display = 'flex';
    },
    
    hideModal: function() {
        const modal = this.currentModal;
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
            
            setTimeout(() => {
                if (modal.parentNode) {
                    modal.parentNode.removeChild(modal);
                }
                this.currentModal = null;
                this.currentVideoId = null;
                this.currentPage = 1;
                this.totalComments = 0;
                this.hasMoreComments = true;
                this.isInitialLoad = true;
            }, 300);
        }
    },
    
    generateCommentsHTML: function(comments) {
        return comments.map(comment => {
            const isOwnComment = this.currentUser && comment.user.id === this.currentUser.id;
            
            return `
                <div class="comment-item" data-comment-id="${comment.id}">
                    <div class="comment-avatar" style="background: ${this.getYouTubeAvatarColor(comment.user.name)};">
                        ${this.getUserInitials(comment.user.name)}
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-author">${this.escapeHtml(comment.user.name)}</span>
                            <span class="comment-time">${this.formatTime(comment.created_at)}</span>
                            ${isOwnComment ? '<span class="your-comment-badge">You</span>' : ''}
                        </div>
                        <p class="comment-text">${this.escapeHtml(comment.content)}</p>
                        <div class="comment-actions-bar">
                            <button class="comment-action-btn like-btn ${comment.is_liked ? 'liked' : ''}" 
                                    data-comment-id="${comment.id}"
                                    ${!this.isLoggedIn ? 'disabled' : ''}
                                    aria-label="${comment.is_liked ? 'Unlike' : 'Like'}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="${comment.is_liked ? '#065fd4' : 'currentColor'}">
                                    <path d="${comment.is_liked ? 'M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z' : 'M18.77,11h-4.23l1.52-4.94C16.38,5.03,15.54,4,14.38,4c-0.58,0-1.14,0.24-1.52,0.65L7,11H3v10h4h1h9.43c1.06,0,1.98-0.67,2.19-1.61l1.34-6C21.23,12.15,20.18,11,18.77,11z M5,20H3v-8h2V20z M19.98,13.17l-1.34,6C18.54,19.65,18.03,20,17.43,20H8v-8.61l5.6-6.06 C13.79,5.12,14.08,5,14.38,5c0.26,0,0.5,0.11,0.63,0.3c0.07,0.1,0.15,0.26,0.09,0.47l-1.52,4.94L13.18,12h4.23 c0.41,0,0.8,0.17,1.03,0.46C18.75,12.76,19.02,13.06,19.98,13.17z'}"/>
                                </svg>
                                <span class="stat-count">${this.formatNumber(comment.likes_count || 0)}</span>
                            </button>
                            
                            ${this.isLoggedIn ? `
                                <button class="comment-action-btn reply-btn" 
                                        data-comment-id="${comment.id}"
                                        aria-label="Reply">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/>
                                    </svg>
                                    Reply
                                </button>
                            ` : ''}
                            
                            ${isOwnComment ? `
                                <button class="comment-action-btn edit-btn" 
                                        data-comment-id="${comment.id}"
                                        aria-label="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                    Edit
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    },
    
    setupCommentInteractions: function() {
        // Like buttons
        document.querySelectorAll('.like-btn:not(:disabled)').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (this.isLoggedIn) {
                    this.toggleLike(btn);
                } else {
                    window.location.href = '/login';
                }
            });
        });
        
        // Reply buttons
        document.querySelectorAll('.reply-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (this.isLoggedIn) {
                    const commentId = btn.dataset.commentId;
                    this.showReplyForm(commentId);
                } else {
                    window.location.href = '/login';
                }
            });
        });
        
        // Edit buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const commentId = btn.dataset.commentId;
                this.editComment(commentId);
            });
        });
    },
    
    setupCommentInteractionsForElement: function(element) {
        const likeBtn = element.querySelector('.like-btn');
        const replyBtn = element.querySelector('.reply-btn');
        const editBtn = element.querySelector('.edit-btn');
        
        if (likeBtn && !likeBtn.disabled) {
            likeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (this.isLoggedIn) {
                    this.toggleLike(likeBtn);
                } else {
                    window.location.href = '/login';
                }
            });
        }
        
        if (replyBtn) {
            replyBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (this.isLoggedIn) {
                    const commentId = replyBtn.dataset.commentId;
                    this.showReplyForm(commentId);
                } else {
                    window.location.href = '/login';
                }
            });
        }
        
        if (editBtn) {
            editBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const commentId = editBtn.dataset.commentId;
                this.editComment(commentId);
            });
        }
    },
    
    submitComment: function() {
        const modal = this.currentModal;
        if (!modal || !this.isLoggedIn || !this.csrfToken) return;
        
        const commentInput = modal.querySelector('.comment-input');
        const submitBtn = modal.querySelector('.submit-btn');
        
        const commentText = commentInput.value.trim();
        if (!commentText) return;
        
        // Show loading
        submitBtn.disabled = true;
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Posting...';
        
        // Simulate API call
        setTimeout(() => {
            // Success
            commentInput.value = '';
            commentInput.style.height = 'auto';
            
            // Hide actions
            const actions = commentInput.closest('.input-container').querySelector('.comment-actions');
            actions.classList.remove('show');
            
            // Show success
            this.showYouTubeNotification('Comment posted successfully');
            
            // Reset button
            submitBtn.textContent = originalText;
            submitBtn.disabled = true;
            
            // Reload comments to show new one at top
            this.currentPage = 1;
            this.totalComments = 0;
            this.hasMoreComments = true;
            this.loadComments(true);
            
        }, 1000);
    },
    
    toggleLike: function(likeBtn) {
        const countSpan = likeBtn.querySelector('.stat-count');
        const svg = likeBtn.querySelector('svg');
        const path = svg.querySelector('path');
        
        let count = parseInt(countSpan.textContent) || 0;
        
        if (likeBtn.classList.contains('liked')) {
            // Unlike
            likeBtn.classList.remove('liked');
            count = Math.max(0, count - 1);
            path.setAttribute('d', 'M18.77,11h-4.23l1.52-4.94C16.38,5.03,15.54,4,14.38,4c-0.58,0-1.14,0.24-1.52,0.65L7,11H3v10h4h1h9.43c1.06,0,1.98-0.67,2.19-1.61l1.34-6C21.23,12.15,20.18,11,18.77,11z M5,20H3v-8h2V20z M19.98,13.17l-1.34,6C18.54,19.65,18.03,20,17.43,20H8v-8.61l5.6-6.06 C13.79,5.12,14.08,5,14.38,5c0.26,0,0.5,0.11,0.63,0.3c0.07,0.1,0.15,0.26,0.09,0.47l-1.52,4.94L13.18,12h4.23 c0.41,0,0.8,0.17,1.03,0.46C18.75,12.76,19.02,13.06,19.98,13.17z');
            svg.setAttribute('fill', 'currentColor');
        } else {
            // Like
            likeBtn.classList.add('liked');
            count++;
            path.setAttribute('d', 'M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z');
            svg.setAttribute('fill', '#065fd4');
        }
        
        countSpan.textContent = this.formatNumber(count);
        
        // Animation
        likeBtn.style.transform = 'scale(1.1)';
        setTimeout(() => {
            likeBtn.style.transform = 'scale(1)';
        }, 200);
    },
    
    editComment: function(commentId) {
        const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (!commentItem) return;
        
        const commentText = commentItem.querySelector('.comment-text');
        const originalText = commentText.textContent;
        
        // Replace with textarea for editing
        const textarea = document.createElement('textarea');
        textarea.className = 'edit-comment-input';
        textarea.value = originalText;
        textarea.rows = 3;
        textarea.style.width = '100%';
        textarea.style.padding = '8px';
        textarea.style.borderRadius = '8px';
        textarea.style.border = '1px solid #065fd4';
        textarea.style.resize = 'vertical';
        textarea.style.fontFamily = 'inherit';
        textarea.style.fontSize = '14px';
        
        commentText.replaceWith(textarea);
        textarea.focus();
        
        // Add save/cancel buttons
        const editActions = document.createElement('div');
        editActions.className = 'edit-actions';
        editActions.style.display = 'flex';
        editActions.style.gap = '8px';
        editActions.style.marginTop = '8px';
        
        editActions.innerHTML = `
            <button class="save-edit-btn" style="padding: 6px 12px; background: #065fd4; color: white; border: none; border-radius: 18px; cursor: pointer; font-size: 13px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="white" style="margin-right: 4px;">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Save
            </button>
            <button class="cancel-edit-btn" style="padding: 6px 12px; background: transparent; color: #606060; border: 1px solid #d1d1d1; border-radius: 18px; cursor: pointer; font-size: 13px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 4px;">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
                Cancel
            </button>
        `;
        
        commentItem.querySelector('.comment-actions-bar').insertAdjacentElement('beforebegin', editActions);
        
        // Event listeners
        const saveBtn = editActions.querySelector('.save-edit-btn');
        const cancelBtn = editActions.querySelector('.cancel-edit-btn');
        
        saveBtn.addEventListener('click', () => {
            const newText = textarea.value.trim();
            if (newText && newText !== originalText) {
                commentText.textContent = newText;
                this.showYouTubeNotification('Comment updated');
            }
            this.cancelEdit(commentItem, commentText);
        });
        
        cancelBtn.addEventListener('click', () => {
            this.cancelEdit(commentItem, commentText);
        });
        
        // Enter to save, Escape to cancel
        textarea.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                saveBtn.click();
            } else if (e.key === 'Escape') {
                cancelBtn.click();
            }
        });
    },
    
    cancelEdit: function(commentItem, originalElement) {
        const textarea = commentItem.querySelector('.edit-comment-input');
        const editActions = commentItem.querySelector('.edit-actions');
        
        if (textarea && originalElement) {
            textarea.replaceWith(originalElement);
        }
        if (editActions) {
            editActions.remove();
        }
    },
    
    showReplyForm: function(commentId) {
        const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (!commentItem) return;
        
        // Check if reply form already exists
        const existingForm = commentItem.querySelector('.reply-form');
        if (existingForm) {
            existingForm.remove();
            return;
        }
        
        const replyFormHTML = `
            <div class="reply-form">
                <div class="comment-input-wrapper">
                    <div class="user-avatar" style="font-size: 12px;">${this.getUserInitials(this.currentUser?.name || 'U')}</div>
                    <div class="input-container">
                        <textarea placeholder="Write a reply..." 
                                  maxlength="300"
                                  style="min-height: 36px; font-size: 13px;"
                                  aria-label="Write a reply"></textarea>
                        <div class="comment-actions">
                            <button class="cancel-btn" type="button" style="font-size: 13px; padding: 6px 12px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 4px;">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                                Cancel
                            </button>
                            <button class="submit-btn" type="submit" style="font-size: 13px; padding: 6px 12px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="white" style="margin-right: 4px;">
                                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                </svg>
                                Reply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const commentContent = commentItem.querySelector('.comment-content');
        commentContent.insertAdjacentHTML('beforeend', replyFormHTML);
        
        // Setup reply form
        const replyForm = commentItem.querySelector('.reply-form');
        const textarea = replyForm.querySelector('textarea');
        const cancelBtn = replyForm.querySelector('.cancel-btn');
        const submitBtn = replyForm.querySelector('.submit-btn');
        
        textarea.focus();
        
        // Show actions
        const actions = textarea.closest('.input-container').querySelector('.comment-actions');
        actions.classList.add('show');
        
        textarea.addEventListener('input', (e) => {
            submitBtn.disabled = !e.target.value.trim();
        });
        
        cancelBtn.addEventListener('click', () => {
            replyForm.remove();
        });
        
        submitBtn.addEventListener('click', () => {
            const replyText = textarea.value.trim();
            if (replyText) {
                this.addReply(commentId, replyText);
                replyForm.remove();
            }
        });
        
        // Enter to submit
        textarea.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                const replyText = textarea.value.trim();
                if (replyText) {
                    this.addReply(commentId, replyText);
                    replyForm.remove();
                }
            }
        });
    },
    
    addReply: function(commentId, text) {
        // In a real app, you would send this to your Laravel API
        this.showYouTubeNotification('Reply posted');
        
        // For demo, just reload comments
        this.currentPage = 1;
        this.totalComments = 0;
        this.hasMoreComments = true;
        this.loadComments(true);
    },
    
    updateCommentsCount: function(count) {
        const countSpan = document.querySelector('.comments-count');
        if (countSpan) {
            countSpan.textContent = `(${count})`;
        }
    },
    
    formatTime: function(timestamp) {
        const now = new Date();
        const date = new Date(timestamp);
        const diff = now - date;
        
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        
        return date.toLocaleDateString();
    },
    
    formatNumber: function(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
        }
        if (num >= 1000) {
            return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
        }
        return num.toString();
    },
    
    getUserInitials: function(name) {
        if (!name) return '?';
        return name
            .split(' ')
            .map(part => part.charAt(0))
            .join('')
            .toUpperCase()
            .substring(0, 2);
    },
    
    getYouTubeAvatarColor: function(username) {
        const colors = [
            '#FF6B6B', '#4ECDC4', '#FFD166', '#06D6A0',
            '#118AB2', '#EF476F', '#FF9A76', '#A78BFA',
            '#F15BB5', '#00BBF9', '#00F5D4', '#FB5607'
        ];
        
        if (!username) return colors[0];
        
        let hash = 0;
        for (let i = 0; i < username.length; i++) {
            hash = username.charCodeAt(i) + ((hash << 5) - hash);
        }
        
        return colors[Math.abs(hash) % colors.length];
    },
    
    showYouTubeNotification: function(message, type = 'success') {
        // Create notification
        const notification = document.createElement('div');
        notification.className = `youtube-notification ${type === 'error' ? 'error' : ''}`;
        
        const icon = type === 'error' ? 
            `<svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>` :
            `<svg width="20" height="20" viewBox="0 0 24 24" fill="#065fd4">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>`;
        
        notification.innerHTML = `
            <div class="notification-content">
                ${icon}
                <span>${this.escapeHtml(message)}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove
        setTimeout(() => {
            notification.style.animation = 'slideDownYoutube 0.3s ease forwards';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    },
    
    escapeHtml: function(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// Make globally available
window.CommentSystem = CommentSystem;
window.initCommentSystem = function() {
    return CommentSystem.init();
};

// Auto-initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        CommentSystem.init();
    });
} else {
    setTimeout(() => CommentSystem.init(), 100);
}

// Dynamic content observer
if (typeof MutationObserver !== 'undefined') {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length > 0) {
                CommentSystem.setupCommentButtons();
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

export default CommentSystem;