<div class="modal fade" tabindex="0" role="dialog" aria-labelledby="subModal" aria-hidden="true" id="subModal">
    <div class="modal-dialog modal-lg" id="sub-modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subModalTitle">Sub Modal Title</h5>
                <button type="button" class="close" data-dismiss="subModal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body" id="subModalBody">Sub Modal Body Goes Here</div>

            <div class="modal-footer" id="subModalFooter">Modal Footer Goes Here</div>
        </div>
    </div>
</div>


<button type="button" data-toggle="modal" data-target="#subModal" id="showSubModalButton" style="display: none;">View Modal</button>


<script type="text/javascript">

    function displaySubModal(){
        $("#showSubModalButton").trigger({ type: "click" });
    }

    function closeSubModal(){
        //Clear Modal Title
        $("#subModalTitle").html("");

        //Clear Modal Body
        $("#subModalBody").html("");

        //Clear Modal Footer
        $("#subModalFooter").html("");

        $("[data-dismiss=subModal]").trigger({ type: "click" });
    }

</script>



