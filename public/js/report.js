$('#printReport').click(function(){
    Popup($('.report')[0].outerHTML);
    function Popup(data)
    {
        window.print();
        return true;
    }
});


$(".clickable-row-new-tab").click(function() {
    //window.location = $(this).data("href");
    window.open( $(this).data("href") , '_blank');
});