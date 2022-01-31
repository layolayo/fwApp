$(document).ready(function() {
    $.ajaxSetup({ cache: false });
    var searchField = $('#search').val();
    $('#search').keyup(function() {
        $.getJSON('../../api/Request.php/search/', function(data) {
            if ($('#search').val().length > 0) {
                var expression = new RegExp($('#search').val(), "i");
                $("#result").addClass("show");
                $.each(data, function(key, value) {
                    if (value.ID.search(expression) != -1 || value.title.search(expression) != -1) {
                        $('#result').html('<li class="p-1"> <a href= "../question.php/?id=' + value.ID + '" class="text-muted">' + value.ID + ' | ' + value.title + '</a></li>');
                    }
                });
            }
        });

        if ($('#search').val().length == 0) {
            $("#result").html(" ");
            $("#result").removeClass("show");
        }
    });


});
