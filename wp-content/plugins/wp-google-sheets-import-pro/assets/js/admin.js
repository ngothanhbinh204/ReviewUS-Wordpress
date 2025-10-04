/**
 * WP Google Sheets Import Pro - Admin JavaScript
 */

(function($) {
    'use strict';

    var WPGSIP = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initTooltips();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Add any global event handlers here
            $(document).on('click', '.wpgsip-ajax-action', this.handleAjaxAction);
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            // Add tooltips if needed
        },

        /**
         * Handle AJAX action
         */
        handleAjaxAction: function(e) {
            e.preventDefault();
            var $btn = $(this);
            var action = $btn.data('action');
            var data = $btn.data('params') || {};
            
            data.action = action;
            data.nonce = wpgsipAdmin.nonce;
            
            $btn.prop('disabled', true);
            
            $.ajax({
                url: wpgsipAdmin.ajaxUrl,
                type: 'POST',
                data: data,
                success: function(response) {
                    $btn.prop('disabled', false);
                    
                    if (response.success) {
                        WPGSIP.showNotice('success', response.data.message || 'Action completed successfully.');
                    } else {
                        WPGSIP.showNotice('error', response.data.message || 'Action failed.');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    WPGSIP.showNotice('error', 'An error occurred. Please try again.');
                }
            });
        },

        /**
         * Show admin notice
         */
        showNotice: function(type, message) {
            var $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
            $('.wrap h1').after($notice);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },

        /**
         * Format number with thousand separators
         */
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        /**
         * Escape HTML
         */
        escapeHtml: function(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        WPGSIP.init();
    });

    // Expose to global scope
    window.WPGSIP = WPGSIP;

})(jQuery);
