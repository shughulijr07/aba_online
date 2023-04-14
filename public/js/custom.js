
jQuery(document).ready(function($) {


	//when data row is clicked
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });


});