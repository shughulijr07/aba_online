<div class="modal fade" tabindex="0" role="dialog" aria-labelledby="modal" aria-hidden="true" id="modal" >
    <div class="modal-dialog modal-lg" id="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header" style="background-color: #00838F; color: #ffffff;">
                <h5 class="modal-title" id="modalTitle">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body" id="modalBody">Modal Body Goes Here</div>

            <div class="modal-footer" id="modalFooter">Modal Footer Goes Here</div>
        </div>
    </div>
</div>


<button type="button" data-toggle="modal" data-target="#modal" id="showModalButton" style="display: none;">View Modal</button>


<script type="text/javascript">

    function displayModal(){
        $("#showModalButton").trigger({ type: "click" });
    }

    function closeModal(){

        $("[data-dismiss=modal]").trigger({ type: "click" });
    }

    function clearModalParameters(){
        //Clear Modal Title
        $("#modalTitle").html("");

        //Clear Modal Body
        $("#modalBody").html("");

        //Clear Modal Footer
        $("#modalFooter").html("");

        //Reset Modal Size
        $("#modal-dialog").removeClass("modal-sm").removeClass("modal-xl").addClass("modal-lg");

        //Remove Custom Modal Width
        $("#modal-dialog").css("max-width", "");
        //$("#modal-dialog").removeAttr( 'style' );
    }

</script>



