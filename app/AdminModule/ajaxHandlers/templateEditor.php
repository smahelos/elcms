<?php
/* to use nette functions, we need to autoload it */
require __DIR__ . '/../../../vendor/autoload.php';

if(empty($_POST)) {
    return;
}
$templateEditor = new \App\Models\TemplateEditorModel($_POST['template_path']);

if(isset($_POST['template_editor_filename_new'])) {
    try {
        $templateEditor->create($_POST['template_editor_filename_new']);
    } catch (\Nette\Application\ApplicationException $e) {
    }
} else if(isset($_POST['template_editor_filename'])) {
    try {
        $templateEditor->edit($_POST['template_editor_filename'], $_POST['template_editor_template']);
    } catch (\Nette\Application\ApplicationException $e) {
    }
}