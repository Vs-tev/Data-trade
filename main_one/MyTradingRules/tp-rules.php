<html>

<head>
</head>

<body>
    <div id="TpRulesResult" class="row table-responsive m-auto">
        <!-- Data will load under this tag!-->
    </div>
</body>

</html>



<div class="modal fade " id="RuletpModal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container-fluid ">
            <div class="row">
                <div class=" modal-header border-bottom col-12">
                    <label class="modal-title-rules"></label>
                    <button type="button" class="close" style="vertical-align:baseline" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body tab-content">
                <div class="" id="">
                    <label for="exit_reason" class="mb-1">Exit Reason </label>
                    <input type="text" name="exit_reason" class="form-control mt-0" id="exit_reason">
                    <div style="height:10px" class=""><span class="error" id="error_tprule"></span></div>
                </div>
            </div>
            <div class="row modal-footer mt-4">
                <input type="hidden" name="rules_id1" id="rules_id1" />
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="button" name="save1" id="save1" class="btn btn-primary mr-3"></button>
            </div>
        </div>
    </div>
</div>

<!--- include the modal!--->

<script>
    $(document).ready(function() {

        $("#error_tprule").hide();

        var error_tprule = false;

        $("#exit_reason").focusout(function() {
            check_tprule();
        });

        function check_tprule() {
            var check_tprule = $("#exit_reason").val().length;

            if ((check_tprule < 1) || (check_tprule > 40)) {
                $("#error_tprule").html("Alphanumeric between 1-40. No special charachters allow!");
                $("#error_tprule").show();
                $("#exit_reason").addClass('invalid');
                error_tprule = true;
            } else {
                error_tprule = false;
                $("#error_tprule").hide();
                $("#exit_reason").removeClass('invalid');
            }
        }

        function hide_tp_error() {
            $('#RuletpModal').modal('show');
            $("#error_tprule").hide();
            $("#exit_reason").removeClass('invalid');
        }

        fetchUser();

        function fetchUser() {
            var save1 = "loadingTp";
            $.ajax({
                url: "MyTradingRules/tp-rules-index.php",
                method: "POST",
                data: {
                    save1: save1
                },
                success: function(data) {
                    $('#TpRulesResult').html(data);
                }
            });
        }
        $('#new_tp_button').click(function() {
            hide_tp_error();
            $('#exit_reason').val('');
            $('.modal-title-rules').text("Create Exit Reason");
            $('#save1').val('Create Rule');
            $('#save1').text('Create Rule');
        });


        $('#save1').click(function() {
            var exit_reason = $('#exit_reason').val();
            var id = $('#rules_id1').val();
            var save1 = $('#save1').val();
            if (exit_reason != '' && error_tprule == false) {
                $.ajax({
                    url: "MyTradingRules/tp-rules-index.php",
                    method: "POST",
                    data: {
                        exit_reason: exit_reason,
                        id: id,
                        save1: save1
                    },
                    success: function(response) {
                        if(response == 'exists') {
                            $("#error_tprule").html("This Name already exists.");
                            $("#error_tprule").show();
                            $("#exit_reason").addClass('invalid'); 
                        } else {
                            $('#RuletpModal').modal('hide');
                            fetchUser();
                            }
                    }
                });
            } else {
                $("input.invalid").focus();
            }
        });

        $(document).on('click', '.edit_exit_reason', function() {
            var id = $(this).attr("id");
            var save1 = "select";
            $.ajax({
                url: "MyTradingRules/tp-rules-index.php",
                method: "POST",
                data: {
                    id: id,
                    save1: save1
                },
                dataType: "json",
                success: function(response) {
                    $.each(response, function(index, element) {
                        $('#exit_reason').val(element.exit_reason);
                    });
                    if(response == 'exists') {
                            $("#error_tprule").html("This Name already exists.");
                            $("#error_tprule").show();
                            $("#exit_reason").addClass('invalid'); 
                        } else {
                            hide_tp_error();
                            $('.modal-title-rules').text('Edit Exit Reason');
                            $('#save1').val("Update");
                            $('#save1').text("Save");
                            $('#rules_id1').val(id);  
                        }
                }
            });
        });


        $(document).on('click', '.delete_exit_reason', function() {
            var id = (this.id);
            var idd = $("#deleteTPruleModal").val(id);
            $.ajax({
                cache: false,
                url: "MyTradingRules/tp-rules-index.php",
                method: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    $('#deleteTPruleModal').modal('show');
                }
            });


            $("#deleteTpRule").click(function() {
                var id = $("#deleteTPruleModal").val();
                var save1 = $("#deleteTpRule").text();
                $.ajax({
                    url: "MyTradingRules/tp-rules-index.php",
                    cache: false,
                    method: "POST",
                    data: {
                        id: id,
                        save1: save1
                    },
                    success: function(data) {
                        fetchUser();
                        $('#deleteTPruleModal').modal('hide');
                    }
                });
            })
        });

    });
</script>

<div class="modal fade" id="deleteTPruleModal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container-fluid">
            <div class="row ">
                <div class="modal-header border-bottom col-12">
                    <label class="">Delete Exit Reason</label>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold text-center p-2"> Are you sure you want to delete this Exit Reason?</p>
            </div>
            <div class="row modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button class="btn btn-primary mr-3" id="deleteTpRule" type="button">Delete</button>
            </div>
        </div>
    </div>
</div>