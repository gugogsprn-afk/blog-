/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    config.keystrokes = [
        [CKEDITOR.CTRL + 90 /*Z*/, 'undo'],
        [CKEDITOR.CTRL + 89 /*Y*/, 'redo'],
        [CKEDITOR.CTRL + CKEDITOR.SHIFT + 90 /*Z*/, 'redo'],

        [CKEDITOR.CTRL + 76 /*L*/, 'link'],

        [CKEDITOR.CTRL + 66 /*B*/, 'bold'],
        [CKEDITOR.CTRL + 73 /*I*/, 'italic'],
        [CKEDITOR.CTRL + 85 /*U*/, 'underline'],
        [CKEDITOR.CTRL + 83 /*S*/, 'save'],
    ];

    config.extraPlugins = 'youtube';
    config.filebrowserBrowseUrl =
        'index.php?show=filebrowser&type=all&response=block&standalone=1';
    config.filebrowserImageBrowseUrl =
        'index.php?show=filebrowser&type=images&response=block&standalone=1';
    config.filebrowserUploadUrl =
        'index.php?show=filebrowser&type=all&response=block&standalone=1';
    config.filebrowserImageUploadUrl =
        'index.php?show=filebrowser&type=images&response=block&standalone=1';
    config.filebrowserWindowWidth = '950';
    config.filebrowserWindowHeight = '520';
    config.allowedContent = true;

    config.resize_maxWidth = 1200;
    config.width = 1200;
    config.resize_dir = 'vertical';

    config.baseHref = '../{{basehref}}';
    config.templates_replaceContent = false;
    config.line_height =
        '1em;1.1em;1.2em;1.3em;1.4em;1.5em;1.6em;1.7em;1.8em;1.9em;2em;2.5em;3em;3.5em;4em;5em;6em';
};
CKEDITOR.plugins.registered['save'] = {
    init: function (editor) {
        editor.addCommand('save', {
            modes: { wysiwyg: 1, source: 1 },
            exec: function (editor) {
                $(editor.element.$).closest('form').submit();
            },
        });
        editor.ui.addButton('Save', { label: 'Save', command: 'save' });
        editor.dataProcessor.htmlFilter.addRules({
            elements: {
                a: function (element) {
                    if (element.attributes?.class?.includes('follow')) {
                        delete element.attributes.rel;
                    } else {
                        element.attributes.rel = 'nofollow';
                    }
                },
            },
        });
    },
};
