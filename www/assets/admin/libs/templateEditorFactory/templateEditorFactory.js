// CSS contants
var typeSelect = 'select#type';
var editorId = 'latteEditor';
var barTitle = editorId + 'BarTitle';
var barSaveButton = editorId + 'BarSave';
var barCancelButton = editorId + 'BarCancel';
var bar = editorId + 'Bar';
var editorBarButtonsSuccessClass = 'btn btn-success pull-right';
var editorBarButtonsCancelClass = 'btn btn-danger pull-right';
var placeholderPrefix = 'latte-';
var templateSelect = 'select.template';
var showClass = 'show';
var hideClass = 'hide';
var templateList = 'ul#templateList';
var placeholders = 'div#latteTemplatesHolders';
var placeholderClass = editorId + 'Placeholder';
var templateExtension = '.latte';
var templatePath = 'input#templatePath';

// labels, alerts and prompts
var createNewTemplateError = 'Při vytváření šablony nastala chyba.';
var saveTemplateError = 'Při ukládání šablony nastala chyba. Prosím, zálohujte si upravenou šablonu a zkuste provést znovu.';
var saveTemplateSuccess = 'Šablona uložena.';
var createNewTemplateNameTaken = 'Šablona se zadaným jménem už existuje. Zvolte prosím jiné.';
var barSaveButtonLabel = 'Uložit';
var barCancelButtonLabel = 'Storno';
var createNewTemplatePrompt = 'Zadejte prosím jméno nové šablony:';
var throwChangesConfirm = 'Opravdu chcete smazat všechny provedené změny? Tento krok nelze vrátit zpět.'

var originalContent = '';

var getUrl = window.location;
var basePath = getUrl.protocol + '//' + getUrl.host + '/' + getUrl.pathname.split('/')[1];
var root_home_adress = basePath + '/www/';

// various paths and addresses
var admin_path = basePath + '/app/AdminModule/';
var ajax_path = 'ajax.php?script=templateEditor';
var redirect_path = 'index.php?p=templateEditorFactory&add_new&type=';

/**
 * Trigger reload on "type" select change
 */
function loadAjax()
{
    var componentName = $(typeSelect).val();
    window.location.replace(root_home_adress + admin_path + redirect_path + componentName);
}

/**
 * Trigger template edit.
 * @param selected  string  Name of editted template.
 */
function editLatteTemplate(selected)
{
    if(!selected) {
        selected = $(templateSelect).val();
    }

    startLatteTemplateEdit(selected);
}

/**
 * Start template edit.
 * @param path  string  path of editted template.
 * @param name  string  Name of editted template.
 */
function startLatteTemplateEdit(path, name)
{
    //$(templateSelect).hide();
    var targetSelect = $('select.template').val();
    if (
        targetSelect !== null &&
        targetSelect !== '' &&
        typeof targetSelect !== 'undefined'
    ) {
        name = targetSelect;
    }

    loadFileContent(path, name);
}

function cancelLatteTemplateEdit(name)
{
    var editor = ace.edit(editorId);

    if(originalContent !== editor.getValue() && !confirm(throwChangesConfirm)) {
        return;
    }

    endLatteTemplateEdit(name);
}

/**
 * Trigged template editting end.
 * @param name  Currently editted template name.
 */
function endLatteTemplateEdit(name)
{
    $(templateSelect).show();

    var editElement = $('#' + editorId);
    var editorBar = $('#' + bar);
    editElement.removeClass(showClass);
    editElement.addClass(hideClass);
    editElement.val('');
    editorBar.remove();
}

/**
 * Create new template. Normalizes name (removes diacritics and forbidden chars).
 * @param placeholder   string  New template name with or without .latte extension
 */
function createNewTemplate(placeholder)
{
    var place = placeholder || '';
    var original = prompt(createNewTemplatePrompt, place);
    if (original == null || typeof original === 'undefined') {
        return;
    }

    if (!original.trim()) {
        createNewTemplate(); // loop if no name entered
        return;
    }

    var name = original.replace(/[\/|&;$%@"<>()+, ]/g, '_');
    name = removeDiacritics(name);

    if (name.indexOf(templateExtension, name.length - templateExtension.length) !== -1) // ends with
    {
        name = name.replace(templateExtension, '');
    }

    var exists = false;
    $(templateSelect + ' option').each(function () {
        if ($(this).val() === name) {
            exists = true;
        }
    });

    if (exists) {
        alert(createNewTemplateNameTaken);
        createNewTemplate(original);
        return;
    }

    $.ajax({
        type: 'POST',
        url: root_home_adress + admin_path + ajax_path,
        data: {"template_editor_filename_new": name, "type": $(typeSelect).val(), "template_path": $(templatePath).val()},
        async: true,
        success: function (returnedData) {
            $(templateSelect).append(
                $('<option></option>').val(name).text(name)
            );
            $(templateList).append(
                $('<li></li>').append(
                    $('<a></a>').attr("href", "javascript:editLatteTemplate('" + name + "')").text(name)
                )
            );
            $(placeholders).append(
                $('<textearea></textearea>')
                    .attr('id', placeholderPrefix + name)
                    .addClass(placeholderClass)
            );

            editLatteTemplate(name);
        },
        fail: function (e) {
            alert(createNewTemplateError);
        }
    });
}

/**
 * Save template.
 * @param path  string  Template path.
 * @param name  string  Template name (without extension).
 */
function saveLatteTemplate(path, name)
{
    var editor = ace.edit(editorId);
    var value = editor.getValue();

    $.ajax({
        type: 'POST',
        url: admin_path + ajax_path,
        data: {"template_editor_filename": name, "type": $(typeSelect).val(), "template_editor_template": value, "template_path": path},
        async: true,
        success: function (returnedData) {
            alert(saveTemplateSuccess);
            var textarea = $('#' + placeholderPrefix + name);
            textarea.val(value);
            endLatteTemplateEdit(name);
        },
        fail: function (e) {
            alert(saveTemplateError);
        }
    });
}

/**
 * Remove czech diacritics
 * @param txt   string
 * @returns {string}
 */
function removeDiacritics(txt)
{
    var sdiak = 'áäčďéěíĺľňóôőöŕšťúůűüýřžÁÄČĎÉĚÍĹĽŇÓÔŐÖŔŠŤÚŮŰÜÝŘŽ';
    var bdiak = 'aacdeeillnoooorstuuuuyrzAACDEEILLNOOOORSTUUUUYRZ';
    var tx = '';

    for (p = 0; p < txt.length; p++) {
        if (sdiak.indexOf(txt.charAt(p)) !== -1)
            tx += bdiak.charAt(sdiak.indexOf(txt.charAt(p)));
        else tx += txt.charAt(p);
    }

    return tx;
}

// Ajax Load TEXT Content Function
function loadFileContent(path, name)
{
    var editElement = $('#' + editorId);
    $.ajax({
        type: 'POST',
        dataType: 'text',
        url: path + name,
        cache: false,
        success: function(data, textStatus, jqXHR){
            editElement.before(editBar(name));
            editElement.removeClass(hideClass);
            editElement.addClass(showClass);

            ace.config.set('basePath', basePath + '/www/assets/admin/libs/ace/');
            //var langTools = ace.require('ace/ext/language_tools');
            var editor = ace.edit(editorId);
            editor.setValue(data);
            editor.setTheme('ace/theme/monokai');
            editor.getSession().setMode('ace/mode/smarty');
            editor.setOptions({
                enableBasicAutocompletion: true,
                enableSnippets: true,
                enableLiveAutocompletion: true
            });

            editor.focus();
            var lines = editor.getSession().getValue().split('\n');
            editor.gotoLine(lines.length, lines[lines.length - 1].length);

            originalContent = editor.getValue();
            document.getElementById('latteEditor').style.fontSize = '16px';

            editElement.off('keydown');
            editElement.on('keydown', function(event) {
                if (!( (String.fromCharCode(event.which).toLowerCase() === 's' ||
                        String.fromCharCode(event.which).toLowerCase() === 'i' ||
                        String.fromCharCode(event.which).toLowerCase() === 'e')
                        && event.ctrlKey) && !(event.which === 19) && !(event.which === 27)) return true;

                if (String.fromCharCode(event.which).toLowerCase() === 's') {
                    saveLatteTemplate(path, name);
                    editElement.off('keydown');
                    return false;
                } else if (event.which === 27) {
                    cancelLatteTemplateEdit(name);
                    return false;
                }

                event.preventDefault();
                return false;
            });

            function editBar(name)
            {
                var title = $('<span></span>')
                    .attr('id', barTitle)
                    .text(path + name);

                var save = $('<a></a>')
                    .attr('href', 'javascript:saveLatteTemplate(\'' + path + '\',\'' + name + '\');')
                    .attr('id', barSaveButton)
                    .attr('class', editorBarButtonsSuccessClass)
                    .text(barSaveButtonLabel);

                var cancel = $('<a></a>')
                    .attr('href', 'javascript:cancelLatteTemplateEdit(\'' + name + '\');')
                    .attr('id', barCancelButton)
                    .attr('class', editorBarButtonsCancelClass)
                    .text(barCancelButtonLabel);

                var retBar = $('<div></div>')
                    .attr('id', bar)
                    .html(title.prop('outerHTML') + save.prop('outerHTML') + cancel.prop('outerHTML'));

                return retBar;
            }
            return data;
        },
        complete: function (data)
        {
        },
        error: function(data)
        {
        }
    });

    return false;
}

// get "type" from get and if set, change type select to that value
$(document).ready(function ()
{
    var QueryString = function () {
        // This function is anonymous, is executed immediately and
        // the return value is assigned to QueryString!
        var query_string = {};
        var query = window.location.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            // If first entry with this name
            if (typeof query_string[pair[0]] === 'undefined') {
                query_string[pair[0]] = pair[1];
                // If second entry with this name
            } else if (typeof query_string[pair[0]] === 'string') {
                var arr = [query_string[pair[0]], pair[1]];
                query_string[pair[0]] = arr;
                // If third or later entry with this name
            } else {
                query_string[pair[0]].push(pair[1]);
            }
        }
        return query_string;
    }();

    var type = QueryString.type;
    if ((typeof type) !== 'undefined') {
        $(typeSelect).val(QueryString.type);
    }
});


