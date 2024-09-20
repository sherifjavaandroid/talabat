$(function () {
    livewire.on("showSelect2", data => {
        const selectorId = "" + data[0] + "";
        const selectorData = data[1];
        const selectorOnchange = "" + data[2] + "";
        const selectorOptions = data[3];

        $(selectorId).select2({
            theme: "classic",
            width: "style"
        });

        if (selectorOptions != null) {
            //remove all current options
            $(selectorId).empty();

            selectorOptions.forEach(element => {
                //
                var optionText = element.name ?? "";
                var optionId = element.id ?? "";

                //
                var newOption = new Option(optionText, optionId, false, false);
                $(selectorId)
                    .append(newOption)
                    .trigger("change");
            });

            //clear current selection
            $(selectorId).val(null).trigger('change');
        }

        if (selectorData) {
            $(selectorId)
                .val(selectorData)
                .trigger("change");
        }

        $(selectorId).on("change", function (e) {
            var data = $(this).select2("val");
            livewire.emit(selectorOnchange, data);
        });
    });


    livewire.on("setQuillTextarea", data => {

        const selectorId = "" + data[0] + "";
        const selectorData = data[1];
        quill.root.innerHTML = selectorData;
        // quill.setContents(selectorData);

    });


    livewire.on("newTab", url => {
        window.open(url, '_blank').focus();
    });

    livewire.on("reloadPage", url => {
        window.location.reload();
    });


});
