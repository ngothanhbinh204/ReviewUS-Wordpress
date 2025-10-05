/**
 * Import Page Enhanced JavaScript
 * Supports selective import, post types, and taxonomy selection
 */

(function($) {
    'use strict';

    var WPGSIP_Import = {
        tenantId: wpgsipImport.tenantId || 'default',
        postType: 'post',
        previewData: [],
        selectedRows: [],
        taxonomyData: {},

        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            var self = this;

            // Post type change
            $('#wpgsip-post-type').on('change', function() {
                self.postType = $(this).val();
                
                // Update displayed sheet range and column description
                var sheetRange, columnsDesc;
                if (self.postType === 'thing_to_do') {
                    sheetRange = $('#wpgsip-thing-sheet-range').val();
                    columnsDesc = 'Columns: A=outline, B=meta_title, C=meta_description, D=keyword, E=status, F=content, G=province, H=themes, I=seasons';
                } else {
                    sheetRange = $('#wpgsip-post-sheet-range').val();
                    columnsDesc = 'Columns: A=outline, B=meta_title, C=meta_description, D=keyword, E=status, F=content, G=CPT, H=category, I=tags';
                }
                $('#wpgsip-current-sheet-range').text(sheetRange);
                $('#wpgsip-sheet-columns-desc').text(columnsDesc);
                
                // Clear preview when post type changes
                $('#wpgsip-preview-data').html('');
                $('#wpgsip-preview-container').hide();
                $('#wpgsip-taxonomy-section').hide();
            });

            // Preview button
            $('#wpgsip-preview-btn').on('click', function() {
                self.loadPreview();
            });

            // Select all checkbox
            $('#wpgsip-select-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('.wpgsip-row-checkbox').prop('checked', isChecked);
                self.updateSelectedCount();
                self.updateImportButton();
            });

            // Import button
            $('#wpgsip-import-btn').on('click', function() {
                self.startImport();
            });

            // Row checkbox delegation
            $(document).on('change', '.wpgsip-row-checkbox', function() {
                self.updateSelectedCount();
                self.updateImportButton();
                
                // Update select all checkbox
                var total = $('.wpgsip-row-checkbox').length;
                var checked = $('.wpgsip-row-checkbox:checked').length;
                $('#wpgsip-select-all').prop('checked', total === checked);
            });

            // Taxonomy selection delegation - handle multi-select
            $(document).on('change', '.wpgsip-row-taxonomy-select', function() {
                var rowId = $(this).data('row-id');
                var taxonomy = $(this).data('taxonomy');
                var selectedValues = $(this).val(); // Returns array for multi-select
                
                if (!self.taxonomyData[rowId]) {
                    self.taxonomyData[rowId] = {};
                }
                // Store as array of term IDs
                self.taxonomyData[rowId][taxonomy] = selectedValues || [];
                
                console.log('Taxonomy selection changed:', {
                    rowId: rowId,
                    taxonomy: taxonomy,
                    selectedValues: selectedValues
                });
            });
        },

        loadPreview: function() {
            var self = this;
            
            $('#wpgsip-preview-container').show();
            $('#wpgsip-preview-loading').show();
            $('#wpgsip-preview-data').html('');

            $.ajax({
                url: wpgsipAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wpgsip_import_preview_enhanced',
                    nonce: wpgsipAdmin.nonce,
                    tenant_id: self.tenantId,
                    post_type: self.postType
                },
                success: function(response) {
                    $('#wpgsip-preview-loading').hide();

                    if (response.success) {
                        self.previewData = response.data.data;
                        console.log(response.data);
                        
                        self.renderPreview(response.data);
                        self.renderTaxonomySection(response.data);
                    } else {
                        $('#wpgsip-preview-data').html(
                            '<div class="notice notice-error"><p>' + 
                            response.data.message + 
                            '</p></div>'
                        );
                    }
                },
                error: function() {
                    $('#wpgsip-preview-loading').hide();
                    $('#wpgsip-preview-data').html(
                        '<div class="notice notice-error"><p>Failed to load preview</p></div>'
                    );
                }
            });
        },

        renderPreview: function(data) {
            var self = this;
            var html = '<p><strong>' + data.count + ' rows found</strong></p>';
            
            // Store available terms for later use
            self.availableTerms = data.available_terms || {};
            self.taxonomies = data.taxonomies || {};
            
            html += '<table class="wp-list-table widefat fixed striped wpgsip-preview-table">';
            html += '<thead><tr>';
            html += '<th class="check-column"><input type="checkbox" id="wpgsip-select-all-header"></th>';
            html += '<th>Row</th>';
            html += '<th style="width: 250px;">Title</th>';
            html += '<th style="width: 100px;">Status</th>';
            html += '<th style="width: 80px;">Content</th>';
            
            // Add taxonomy columns if detected
            if (data.taxonomies) {
                $.each(data.taxonomies, function(slug, info) {
                    html += '<th style="width: 200px;">' + info.label + '</th>';
                });
            }
            
            html += '<th style="width: 100px;">Action</th>';
            html += '</tr></thead><tbody>';

            $.each(data.data, function(i, row) {
                var existingPost = row.existing_post || false;
                var actionText = existingPost ? '✏️ Update' : '➕ Create';
                var actionClass = existingPost ? 'update' : 'create';
                
                html += '<tr>';
                html += '<td class="check-column">';
                html += '<input type="checkbox" class="wpgsip-row-checkbox" value="' + row.row_id + '">';
                html += '</td>';
                html += '<td>' + row.row_id + '</td>';
                
                // Display title with truncation
                var title = row.meta_title || row.outline || '-';
                var displayTitle = title.length > 100 ? title.substring(0, 100) + '...' : title;
                html += '<td><strong>' + self.escapeHtml(displayTitle) + '</strong>';
                if (existingPost) {
                    html += '<br><small style="color:#999;">Existing: ' + existingPost.title + '</small>';
                }
                html += '</td>';
                html += '<td>' + self.escapeHtml(row.status || '-') + '</td>';
                
                // Display content status with truncation
                var hasContent = row.content && row.content.trim().length > 0;
                var contentPreview = '';
                if (hasContent) {
                    var contentText = row.content.substring(0, 50);
                    contentPreview = '✅ Yes (' + row.content.length + ' chars)';
                } else {
                    contentPreview = '❌ Empty';
                }
                html += '<td>' + contentPreview + '</td>';
                
                // Add taxonomy dropdowns for each row
                if (data.taxonomies) {
                    $.each(data.taxonomies, function(slug, info) {
                        html += '<td>';
                        html += self.renderTaxonomyDropdown(slug, info, row, data.available_terms);
                        html += '</td>';
                    });
                }
                
                html += '<td><span class="wpgsip-action-badge wpgsip-action-' + actionClass + '">' + actionText + '</span></td>';
                html += '</tr>';
            });

            html += '</tbody></table>';

            $('#wpgsip-preview-data').html(html);
            
            // Bind header select all
            $('#wpgsip-select-all-header').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('.wpgsip-row-checkbox').prop('checked', isChecked);
                $('#wpgsip-select-all').prop('checked', isChecked);
                WPGSIP_Import.updateSelectedCount();
                WPGSIP_Import.updateImportButton();
            });
        },
        
        renderTaxonomyDropdown: function(slug, info, row, availableTerms) {
            var html = '';
            var columnValue = row[info.column_name] || '';
            var isHierarchical = (slug === 'category' || slug === 'provinces_territories' || slug === 'thing_themes');
            
            // Create select element
            html += '<select name="row_taxonomy[' + row.row_id + '][' + slug + '][]" ';
            html += 'class="wpgsip-row-taxonomy-select" ';
            html += 'data-row-id="' + row.row_id + '" ';
            html += 'data-taxonomy="' + slug + '" ';
            html += 'multiple style="width:100%; max-width:200px; height:60px;">';
            
            // Add available terms as options
            if (availableTerms && availableTerms[slug]) {
                $.each(availableTerms[slug], function(i, term) {
                    var isSelected = '';
                    
                    // Check if this term matches the column value
                    if (columnValue) {
                        var columnTerms = columnValue.toLowerCase().split(',').map(function(t) {
                            return t.trim();
                        });
                        if (columnTerms.indexOf(term.name.toLowerCase()) !== -1 || 
                            columnTerms.indexOf(term.slug.toLowerCase()) !== -1) {
                            isSelected = ' selected';
                        }
                    }
                    
                    html += '<option value="' + term.term_id + '">' + term.name + '</option>';
                    if (isSelected) {
                        html = html.replace('<option value="' + term.term_id + '">', '<option value="' + term.term_id + '" selected>');
                    }
                });
            }
            
            html += '</select>';
            
            // Show original column value if exists
            if (columnValue) {
                html += '<br><small style="color:#666;">From sheet: ' + columnValue + '</small>';
            }
            
            return html;
        },

        renderTaxonomySection: function(data) {
            if (!data.taxonomies || Object.keys(data.taxonomies).length === 0) {
                return;
            }

            var hasEmptyTaxonomies = false;
            $.each(data.taxonomies, function(slug, info) {
                if (!info.has_data) {
                    hasEmptyTaxonomies = true;
                    return false;
                }
            });

            if (!hasEmptyTaxonomies) {
                $('#wpgsip-taxonomy-section').hide();
                return;
            }

            var html = '<p>' + wpgsipImport.i18n.taxonomyHelp + '</p>';
            html += '<table class="form-table">';

            $.each(data.taxonomies, function(slug, info) {
                if (!info.has_data) {
                    html += '<tr>';
                    html += '<th>' + info.label + '</th>';
                    html += '<td>';
                    html += '<select name="default_taxonomy[' + slug + '][]" multiple class="wpgsip-taxonomy-select">';
                    html += '<option value="">- Select -</option>';
                    if (data.available_terms && data.available_terms[slug]) {
                        $.each(data.available_terms[slug], function(i, term) {
                            html += '<option value="' + term.term_id + '">' + term.name + '</option>';
                        });
                    }
                    html += '</select>';
                    html += '<p class="description">Select default ' + info.label.toLowerCase() + ' for all imported items (optional)</p>';
                    html += '</td>';
                    html += '</tr>';
                }
            });

            html += '</table>';

            $('#wpgsip-taxonomy-options').html(html);
            $('#wpgsip-taxonomy-section').show();
        },

        updateSelectedCount: function() {
            var count = $('.wpgsip-row-checkbox:checked').length;
            $('#wpgsip-selected-count').text(count);
            this.selectedRows = $('.wpgsip-row-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
        },

        updateImportButton: function() {
            var hasSelection = this.selectedRows.length > 0;
            $('#wpgsip-import-btn').prop('disabled', !hasSelection);
        },

        startImport: function() {
            var self = this;
            
            if (self.selectedRows.length === 0) {
                alert(wpgsipImport.i18n.noSelection);
                return;
            }

            if (!confirm(wpgsipImport.i18n.confirmImport.replace('%d', self.selectedRows.length))) {
                return;
            }

            $('#wpgsip-import-btn').prop('disabled', true);
            $('#wpgsip-import-progress').show();
            $('#wpgsip-import-results').hide();

            // Get default taxonomies (for rows without specific taxonomy selections)
            var defaultTaxonomies = {};
            $('select[name^="default_taxonomy"]').each(function() {
                var name = $(this).attr('name');
                var match = name.match(/default_taxonomy\[(.+?)\]/);
                if (match) {
                    defaultTaxonomies[match[1]] = $(this).val();
                }
            });
            
            // Get row-specific taxonomies
            var rowTaxonomies = {};
            $('.wpgsip-row-taxonomy-select').each(function() {
                var rowId = $(this).data('row-id');
                var taxonomy = $(this).data('taxonomy');
                var value = $(this).val();
                
                if (!rowTaxonomies[rowId]) {
                    rowTaxonomies[rowId] = {};
                }
                
                if (value && value.length > 0) {
                    rowTaxonomies[rowId][taxonomy] = value;
                }
            });

            self.runBatchImport(0, self.selectedRows, defaultTaxonomies, rowTaxonomies);
        },

        runBatchImport: function(batch, rowIds, defaultTaxonomies, rowTaxonomies) {
            var self = this;
            var batchSize = 10;
            var offset = batch * batchSize;
            var batchRowIds = rowIds.slice(offset, offset + batchSize);

            $.ajax({
                url: wpgsipAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wpgsip_import_selective',
                    nonce: wpgsipAdmin.nonce,
                    tenant_id: self.tenantId,
                    post_type: self.postType,
                    row_ids: batchRowIds,
                    batch: batch,
                    default_taxonomies: defaultTaxonomies,
                    row_taxonomies: rowTaxonomies
                },
                success: function(response) {
                    if (response.success) {
                        var result = response.data;
                        var processed = offset + result.processed;
                        var total = rowIds.length;
                        var progress = (processed / total) * 100;

                        $('.wpgsip-progress-fill').css('width', progress + '%');
                        $('#wpgsip-import-status').text(
                            'Processing... (' + processed + '/' + total + ')'
                        );

                        if (processed < total) {
                            self.runBatchImport(batch + 1, rowIds, defaultTaxonomies, rowTaxonomies);
                        } else {
                            self.showResults(result);
                        }
                    } else {
                        $('#wpgsip-import-status').html(
                            '<div class="notice notice-error"><p>' + 
                            response.data.message + 
                            '</p></div>'
                        );
                        $('#wpgsip-import-btn').prop('disabled', false);
                    }
                },
                error: function() {
                    $('#wpgsip-import-status').html(
                        '<div class="notice notice-error"><p>Import failed</p></div>'
                    );
                    $('#wpgsip-import-btn').prop('disabled', false);
                }
            });
        },

        showResults: function(result) {
            $('#wpgsip-import-progress').hide();
            $('#wpgsip-import-results').show();
            $('#wpgsip-import-btn').prop('disabled', false);

            var html = '<p><strong>Import completed!</strong></p>';
            html += '<ul>';
            html += '<li>Created: ' + result.created + '</li>';
            html += '<li>Updated: ' + result.updated + '</li>';
            html += '<li>Skipped: ' + result.skipped + '</li>';
            html += '<li>Errors: ' + result.errors + '</li>';
            html += '</ul>';

            $('#wpgsip-import-summary').html(html);

            if (result.messages && result.messages.length > 0) {
                var messagesHtml = '<h4>Messages:</h4><ul>';
                $.each(result.messages.slice(0, 20), function(i, msg) {
                    messagesHtml += '<li>' + msg + '</li>';
                });
                if (result.messages.length > 20) {
                    messagesHtml += '<li><em>... and ' + (result.messages.length - 20) + ' more</em></li>';
                }
                messagesHtml += '</ul>';
                $('#wpgsip-import-messages').html(messagesHtml);
            }

            // Reload preview to show updated status
            setTimeout(function() {
                WPGSIP_Import.loadPreview();
            }, 2000);
        },
        
        // Escape HTML to prevent XSS and display issues
        escapeHtml: function(text) {
            if (!text) return '';
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        WPGSIP_Import.init();
    });

})(jQuery);
