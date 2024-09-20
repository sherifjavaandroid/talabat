function initAnySummerNote() {

    // find any select2 element with class .select2
    $('.summernote').each(async function (index, object) {

        const elementId = $(this).attr('id');
        const inputEntryId = "" + elementId + "-Input";
        const editor = await initEditor(elementId);
        setWindowVariable(elementId, editor);
        syncEditorContent(editor, inputEntryId);


    });






}


function syncEditorContent(editor, inputEntryId) {

    editor.model.document.on('change:data', () => {
        const editorData = editor.getData();
        $("#" + inputEntryId).val(editorData);
        document.getElementById(inputEntryId).dispatchEvent(new Event('input'));
    });

}


async function initEditor(elementId) {
    return await ClassicEditor
        .create(document.querySelector('#' + elementId))
        .catch(error => {
            console.error(error);
        });

}




//
function setWindowVariable(variableName, value) {
    window[variableName] = value;
}

function getWindowVariable(variableName) {
    return window[variableName];
}






// jqery on page load
$(function () {

    initAnySummerNote();

    //
    Livewire.on('loadSummerNote', async function (elementId, text) {
        const inputEntryId = "" + elementId + "-Input";
        const editor = getWindowVariable(elementId);
        editor.setData(text);
        syncEditorContent(editor, inputEntryId);
    });

});
