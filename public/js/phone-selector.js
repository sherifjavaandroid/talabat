$(function () {

    livewire.on("initPhone", data => {
        data = JSON.parse(data);
        var phoneElement = document.getElementById(data[0]);
        var phoneHiddenInputElement = document.getElementById(data[1]);
        // var input = document.querySelector("#" + data[0] + "");
        var utilsScriptLink = document.getElementById("utilsScriptUrl").getAttribute("data-value");
        var iti = window.intlTelInput(phoneElement, {
            initialCountry: data[3] ?? '',
            nationalMode: true,
            utilsScript: utilsScriptLink,
            dropdownContainer: document.body,
        });


        if (data[2] ?? '' !== null) {
            iti.setNumber(data[2]);
            document.dispatchEvent(new Event('telDOMChanged'));
            phoneHiddenInputElement.value = data[2];
        }

        phoneElement.addEventListener("change", function () {
            const phoneNumber = iti.getNumber();
            phoneHiddenInputElement.value = phoneNumber;
            phoneHiddenInputElement.dispatchEvent(new Event('input'));
        });
    });

    function initPhone() {

        document.querySelectorAll(".phoneInitDiv").forEach(element => {
            var data = element.getAttribute("data-value");
            Livewire.emit('initPhone', data);
        });
    }

    //
    initPhone();

});
