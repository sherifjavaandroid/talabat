$(function () {

    $("#chatListDiv").hide();

    livewire.on("clearChats", data => {
        $("#chatList").val("");
    });


    livewire.on("loadChats", data => {

        //
        if (data != null || data[1].lenght != 0) {
            $("#emptyChat").hide();
            $("#chatListDiv").show();
            //
            const ownerId = data[0];
            //fill the chat list div
            data[1].forEach(chat => {
                //




                var chatSection = "<div class='chat-message'>";
                if (chat['userId'] == ownerId) {
                    chatSection += "<div class='flex flex-col items-start order-2 max-w-xs mx-2 space-y-1 text-xs'>";
                } else {
                    chatSection += "<div class='flex flex-col items-end order-2 max-w-xs mx-2 space-y-1 text-xs'>";
                }
                chatSection += "<div>";
                //chat if chat has photos or is not empty
                var photos = chat['photos'];
                if (photos != null && photos.length > 0) {
                    chatSection += "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-1'>";
                    photos.forEach(photoObject => {
                        var photoSrc = photoObject['url'];
                        if (chat['userId'] == ownerId) {
                            chatSection += '<img src="' + photoSrc + '" class="w-24 h-24 rounded-lg rounded-bl-none">';
                        } else {
                            chatSection += '<img src="' + photoSrc + '" class="w-24 h-24 rounded-lg rounded-br-none">';
                        }
                    });
                    chatSection += "</div>";
                }
                //if text is not empty
                if (chat['text'] != null && chat['text'].length > 0) {
                    //chat text
                    if (chat['userId'] == ownerId) {
                        chatSection += '<span class="inline-block px-4 py-2 text-gray-600 bg-gray-300 rounded-lg rounded-bl-none">';
                    } else {
                        chatSection += '<span class="inline-block px-4 py-2 text-gray-100 bg-gray-500 rounded-lg rounded-bl-none">';
                    }
                    chatSection += chat['text'];
                    chatSection += "</span>";
                }
                chatSection += "</div>";
                chatSection += "</div>";
                chatSection += "</div >";
                //
                $("#chatList").append(chatSection);
            });


        } else {
            $("#emptyChat").show();
            $("#chatListDiv").hide();
        }



    });


});
