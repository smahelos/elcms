if (typeof datagridSortableTree === 'undefined') {
    datagridSortableTree = function () {
        var active = false;
        if (typeof $('.datagrid-tree-item-children').sortable === 'undefined') {
            return;
        }
        return $('.datagrid-tree-item-children').sortable({
            handle: '.handle-sort',
            items: '.datagrid-tree-item:not(.datagrid-tree-header)',
            toleranceElement: '> .datagrid-tree-item-content',
            connectWith: '.datagrid-tree-item-children',
            update: function (event, ui) {
                if (active) {
                    console.log("killing active"); $.nette.ajax.abort();
                }
                active = true;
                var component_prefix, data, item_id, next_id, parent, parent_id, prev_id, row, url;
                $('.toggle-tree-to-delete').addClass('hidden');
                row = ui.item.closest('.datagrid-tree-item[data-id]');
                row.addClass('sorted');
                $('.editArticleItem').unbind("click");
                item_id = row.data('id');
                prev_id = null;
                next_id = null;
                parent_id = null;
                if (row.prev().length) {
                    prev_id = row.prev().data('id');
                }
                if (row.next().length) {
                    next_id = row.next().data('id');
                }
                parent = row.parent().closest('.datagrid-tree-item');
                if (parent.length) {
                    parent.find('.datagrid-tree-item-children').first().css({
                        display: 'block'
                    });
                    parent.addClass('has-children');
                    parent_id = parent.data('id');
                }
                url = $(this).data('sortable-url');
                if (!url) {
                    return;
                }
                parent.find('[data-toggle-tree]').first().removeClass('hidden');
                component_prefix = row.closest('.datagrid-tree').attr('data-sortable-parent-path');
                data = {};
                data[(component_prefix + '-item_id').replace(/^-/, '')] = item_id;
                data[(component_prefix + '-prev_id').replace(/^-/, '')] = prev_id;
                data[(component_prefix + '-next_id').replace(/^-/, '')] = next_id;
                data[(component_prefix + '-parent_id').replace(/^-/, '')] = parent_id;

                return $.nette.ajax({
                    type: 'GET',
                    url: url,
                    data: data,
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (errorThrown !== 'abort') {
                            return alert(jqXHR.statusText);
                        }
                    }
                });
            },
            stop: function (event, ui) {
                setTimeout(function () {
                    var sorted = $('.sorted');
                    sorted.removeClass('sorted');
                }, 700);

                return $('.toggle-tree-to-delete').removeClass('toggle-tree-to-delete');
            },
            start: function (event, ui) {
                var parent;
                parent = ui.item.parent().closest('.datagrid-tree-item');
                if (parent.length) {
                    if (parent.find('.datagrid-tree-item').length === 2) {
                        parent.find('[data-toggle-tree]').addClass('toggle-tree-to-delete');
                        $(this).find('[data-toggle-tree]').removeClass('toggle-tree-to-delete');
                    }
                }
            }
        });
    };
}

$(function () {
    return datagridSortableTree();
});

$.nette.ext('datagrid.tree', {
    before: function (xhr, settings) {
        var children_block;
        if (settings.nette && settings.nette.el.attr('data-toggle-tree')) {
            settings.nette.el.toggleClass('toggle-rotate');
            children_block = settings.nette.el.closest('.datagrid-tree-item').find('.datagrid-tree-item-children').first();
            if (children_block.hasClass('loaded')) {
                children_block.slideToggle('fast');
                return false;
            }
        }
        return true;
    },
    success: function (payload) {
        var children_block, content, id, name, ref, snippet, template;
        if (payload._datagrid_tree) {
            id = payload._datagrid_tree;
            children_block = $('.datagrid-tree-item[data-id="' + id + '"]').find('.datagrid-tree-item-children').first();
            children_block.addClass('loaded');
            ref = payload.snippets;
            for (name in ref) {
                //if (name !== 'snippet--content' && name !== 'snippet--articleForm' && name !== 'snippet--breadcrumbs' && name !== 'snippet--flashes' && name !== 'snippet--userForm') {
                if (name.indexOf('snippet-articlesTreeDatagrid-articlesGrid') === 0) {
                    snippet = ref[name];
                    content = $(snippet);
                    template = $('<div class="datagrid-tree-item" id="' + name + '">');
                    template.attr('data-id', content.attr('data-id'));
                    template.append(content);
                    if (content.data('has-children')) {
                        template.addClass('has-children');
                    }
                    children_block.append(template);
                }
            }
            children_block.addClass('loaded');
            children_block.slideToggle('fast');
            $.nette.load();

            //* reinitialize after ajax
            clickDatagridItemClass();
        }
        return datagridSortableTree();
    }
});