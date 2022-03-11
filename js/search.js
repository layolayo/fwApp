$(document).ready(function() {
    $.ajaxSetup({ cache: false });

    $('#search').keyup(function(keycode){
        // If pressing enter
        if (e.which === 13) {
            $("#searchResult-0").click();
        }

        $.getJSON('/fwApp/api/search.php?l=5&q='+$('#search').val(), function(data) {
            if ($('#search').val().length > 0) {
                $("#result").html(" ");

                $("#result").addClass("show");
                let overall = "";
                $.each(data, function(key, value){
                    overall += '<li> <a id="searchResult-' + key + '" href= "/fwApp/html/question.php?id=' + value.ID + '" class="text-muted dropdown-item">'+ value.ID + ' | ' + value.title+'</a></li>';
                });
                $('#result').html(overall);
            }
        });

        if ($('#search').val().length == 0) {
            $("#result").html(" ");
            $("#result").removeClass("show");
        }
    });
});
