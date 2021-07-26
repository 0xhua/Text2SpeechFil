$('document').ready(function () {
    $('#play').click(function () {
        console.log('playing...');
        $("#button").text("⏸");
        text = $('#text').html();
        $.ajax({
            url: "http://localhost:63342/Text2SpeechFil/index.php?_ijt=bb37h0hfcjfc2hir9c2addlqol",
            type: "post",
            data: text,
            success: function(result) {
                data = JSON.parse(result);
               if(data.success === true){
                   console.log(data.file);
                   var audio = new Audio(data.file);
                   audio.play();
               }else {
                   alert(data.message);
               }
            }
        });
    });
});
