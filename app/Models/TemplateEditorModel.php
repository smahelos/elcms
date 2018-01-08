<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 2. 11. 2017
 * Time: 13:48
 */

namespace App\Models;

use \Nette\Application\ApplicationException;

class TemplateEditorModel
{
    const EXTENSION = 'latte';

    /** @var $path */
    protected $path;

    public function __construct($path = '../templates/')
    {
        $this->path = $path;
    }

//    /**
//     * Return all necessary HTML to create editable templates. JS is required. (templateFactory.js,
//     * ace/ace.js and modes).
//     * @param       $name         string  Name of created select with available templates (e. g. template).
//     * @param       $active       string  Active template (i. e. selected option in select).
//     * @param       $filter       string  Inlude only files that will pass the filter (their names w/o extension).
//     * @param       $exclude      array   Exclude certain files.
//     * @return string HTML. Should be used inside a table.
//     */
//    public function getEditableTemplates($name, $active = null, $filter = null, array $exclude = null)
//    {
//        $wildCard = $filter ?: '*';
//        $path = $this->getPath(sprintf('%s.%s', $wildCard, self::EXTENSION));
//        $latteFiles = glob($path);
//
//        if ($exclude !== null)
//        {
//            $latteFiles = $this->filterFiles($latteFiles, $exclude);
//        }
//
//        return $this->getTemplate($latteFiles, $name, $active);
//    }
//
//    /**
//     * Create HTML for templates editing.
//     * @param $latteFiles array     Template files.
//     * @param $name       string    Name of the template field (e. g. 'template').
//     * @param $active     string    Name of active template file (will be selected).
//     * @return string   HTML code.
//     */
//    protected function getTemplate(array $latteFiles, $name, $active)
//    {
//        $files = array();
//
//        $placeholders = '';
//        foreach ($latteFiles as $latte)
//        {
//            $fileName = str_replace(array($this->path, '.' . self::EXTENSION), '', $latte);
//            $fileName = substr($fileName, 1); // remove leading /
//            $files[$fileName] = $fileName;
//            $placeholders .= sprintf('<textarea id="latte-%s" class="latteEditorPlaceholder">%s</textarea>', $fileName, htmlspecialchars(file_get_contents($latte)));
//        }
//
//        $select = array(
//            '<b>Šablona</b>' => $name . ';;6;; ;; ;;' . $this->form->make_select_ret_from_pole($files),
//        );
//
//        $ret = $this->form->make_form_line_retezec($select, 7, array($name => $active));
//        $ret .= sprintf('<td style="padding-left: 15px;"><input type="button" style="display:none;" name="template_editor_filename_delete" onclick="return confirm(\'Opravdu chcete smazat šablonu?\')" name="deleteTemplate" value="%s" ></td><td><input type="button" value="%s" onclick="editLatteTemplate();"></td><td><input type="button" value="%s" onclick="createNewTemplate();"></td>', $GLOBALS['asay']['smazat'], $GLOBALS['asay']['editacesablon'], $GLOBALS['asay']['vytvoritnovou']);
//        $ret .= sprintf('<td><div id="latteTemplatesHolders">%s</div><div id="latteEditor" class="hide"></div></td>', $placeholders);
//        $ret .= sprintf('<td><input type="hidden" id="templatePath" value="%s"></td>', $this->path);
//
//        return $ret;
//    }
//
//    /**
//     * Filter files by given exclude filter. Pass an array of files (without extension) you want to exclude.
//     * @param $files    array   Current files (unfiltered).
//     * @param $exclude  array   Files to exclude: array("file1", "file2" ...)
//     * @return array    Filtered result.
//     */
//    protected function filterFiles(array $files, array $exclude)
//    {
//        $filteredFiles = array_filter($files, function ($name) use ($exclude)
//        {
//            $file = explode('/', $name);
//            $fileName = $file[count($file) - 1];
//            $fileName = str_replace('.' . self::EXTENSION, '', $fileName);
//            return !in_array($fileName, $exclude);
//        });
//
//        return $filteredFiles;
//    }

    /**
     * Create new template.
     * @param $name string Name of created file (can have extension .latte (or self::EXTENSION, to be specific), but isn't mandatory).
     * @see TemplateEditor::EXTENSION
     *
     * @throws ApplicationException
     *
     * @return bool True on success
     */
    public function create($name): bool
    {
        $path = $this->getPath($name);
        $realPath = $this->getRealPath($path);

        if(fopen($realPath, 'b') === false) {
            throw new ApplicationException('Soubor se nepodařilo vytvořit');
        }

        return true;
    }

    /**
     * Save template. Uses write lock so nobody else can edit the template in the same time.
     * @param $name
     * @param $content
     * @return bool True on success.
     * @throws ApplicationException
     */
    public function edit($name, $content): bool
    {
        $path = $this->getPath($name);
        $realPath = $this->getRealPath($path);

        if(file_put_contents($realPath, $content) === false)
        {
            throw new ApplicationException('Soubor se nepodařilo uložit');
        }

        return true;
    }

    /**
     * Creates path to file from its name.
     * @param $name string  Name of the file.
     * @return string   Path to file.
     */
    protected function getPath($name): string
    {
        $file = $name;
        if(!$this->endsWith($name, '.' . self::EXTENSION)) {
            $file = sprintf('%s.%s', $name, self::EXTENSION);
        }

        return sprintf('%s/%s', $this->path, $file);
    }

    /**
     * Get real path to file
     * @param $path string
     * @return string
     */
    protected function getRealPath($path): string
    {
        $path = parse_url($path, PHP_URL_PATH);
        return realpath($_SERVER['DOCUMENT_ROOT'] . $path);
    }

    /**
     * Ends the $haystack string with the suffix $needle?
     * @param  string $haystack
     * @param  string $needle
     * @return bool
     */
    public function endsWith($haystack, $needle): bool
    {
        return strlen($needle) === 0 || substr($haystack, -strlen($needle)) === $needle;
    }
}