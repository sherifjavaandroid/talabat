$(function () {

    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],

        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction

        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
    ];

    //check if there is any element with id #newDescription
    if (document.getElementById("newDescription") != null) {
        var newEditor = new Quill("#newDescription", {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            }
        });

        //text change listeners
        newEditor.on('text-change', function () {
            const contents = newEditor.root.innerHTML;
            document.getElementById("description").value = contents;
            document.getElementById("description").dispatchEvent(new Event('input'));
        });
    }

    //check if there is any element with id #editDescription
    if (document.getElementById("editDescription") != null) {
        var editEditor = new Quill("#editDescription", {
            theme: 'snow', modules: {
                toolbar: toolbarOptions
            }
        });


        editEditor.on('text-change', function () {
            const contents = editEditor.root.innerHTML;
            document.getElementById("description").value = contents;
            document.getElementById("description").dispatchEvent(new Event('input'));
        });
    }





    livewire.on("prepCustomWYSISYG", data => {

        const selectorData = data[0];
        if (selectorData != null) {
            editEditor.root.innerHTML = selectorData;
            newEditor.root.innerHTML = selectorData;
        }

    });



    // Custom WYSISYGs array
    // var customWYSISYGs = [];
    livewire.on("initNewEditor", data => {
        var selector = data[0];
        const selectorDataKeeper = data[1];
        const selectorData = data[2] ?? null;
        //if starts with #, it is an id
        if (!selector.startsWith("#")) {
            selector = "#" + selector;
        }


        const editorField = new Quill(selector, {
            theme: 'snow', modules: {
                toolbar: toolbarOptions
            }
        });

        if (selectorData != null) {
            editorField.root.innerHTML = selectorData;
            editorField.root.innerHTML = selectorData;
        }

        //text change listeners
        editorField.on('text-change', function () {
            const contents = editorField.root.innerHTML;
            document.getElementById(selectorDataKeeper).value = contents;
            document.getElementById(selectorDataKeeper).dispatchEvent(new Event('input'));
        });


    });



});
