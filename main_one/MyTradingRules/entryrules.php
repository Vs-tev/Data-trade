<html>

<head>
    <link href="MyTradingRules/my-trading-rules.css" rel="stylesheet">

</head>

<body>
    <div id="EntryRulesResult" class="row table-responsive m-auto">
        <!-- Data will load under this tag!-->
    </div>
</body>

</html>

<div class="modal fade " id="RuleModal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container-fluid ">
            <div class="row">
                <div class="modal-header border-bottom col-12">
                    <label class="modal-title-rules "></label>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="" id="">
                    <label for="entry_rule" class="mb-1">Entry Rule </label>
                    <input type="text" name="entry_rule" class="form-control mt-0" id="entry_rule">
                    <div style="height:10px" class=""><span class="error" id="error_entryrule"></span></div>
                </div>
            </div>
            <div class="row modal-footer mt-4">
                <input type="hidden" name="rules_id1" id="rules_id1" />
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="button" name="saving" id="saving" class="btn btn-primary mr-3">Create Rule</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#error_entryrule").hide();

        var error_entryrule = false;

        $("#entry_rule").focusout(function() {
            check_entryrule();
        });

        function check_entryrule() {
            var check_entryrule = $("#entry_rule").val().length;


            if ((check_entryrule < 3) || (check_entryrule > 40)) {
                $("#error_entryrule").html("Alphanumeric between 3-40. No special charachters allow!");
                $("#error_entryrule").show();
                $("#entry_rule").addClass('invalid');
                error_entryrule = true;
            } else {
                error_entryrule = false;
                $("#error_entryrule").hide();
                $("#entry_rule").removeClass('invalid');
            }
        }

        function hide_entryrule_error() {
            $('#RuleModal').modal('show');
            $("#error_entryrule").hide();
            $("#entry_rule").removeClass('invalid');
        }

        fetchUser();

        function fetchUser() {
            var saving = "loading";
            $.ajax({
                url: "MyTradingRules/entryrules-index.php",
                method: "POST",
                data: {
                    saving: saving
                },
                success: function(data) {
                    $('#EntryRulesResult').html(data);
                }
            });
        }

        $('#new_rule_button').click(function() {
            hide_entryrule_error();
            $('#entry_rule').val('');
            $('.modal-title-rules').text("Create Entry Rule");
            $('#saving').val('Create Rule');
            $('#saving').text('Create Rule');
        });

        $('#saving').click(function() {
            var entry_rule = $('#entry_rule').val();
            var id = $('#rules_id1').val();
            var saving = $('#saving').val();
            if (entry_rule != '' && error_entryrule == false) {
                $.ajax({
                    url: "MyTradingRules/entryrules-index.php",
                    method: "POST",
                    data: {
                        entry_rule: entry_rule,
                        id: id,
                        saving: saving
                    },
                    success: function(response) {
                        console.log(response);
                        if (response == 'exists') {
                            $("#error_entryrule").html("This name already exists.");
                            $("#error_entryrule").show();
                            $("#entry_rule").addClass('invalid');
                        } else {
                            $('#RuleModal').modal('hide');
                            fetchUser();

                        }
                    }
                });
            } else {
                $("input.invalid").focus();
            }
        });


        $(document).on('click', '.edit_entryRule', function() {
            var id = $(this).attr("id");
            var saving = "selectt";
            $.ajax({
                url: "MyTradingRules/entryrules-index.php",
                method: "POST",
                data: {
                    id: id,
                    saving: saving
                },
                dataType: "json",
                success: function(response) {
                    $.each(response, function(index, element) {
                        $('#entry_rule').val(element.entry_rule);
                    });
                    if (response == 'exists') {
                        $("#error_entryrule").html("This name already exists.");
                        $("#error_entryrule").show();
                        $("#entry_rule").addClass('invalid');
                    } else {
                        hide_entryrule_error();
                        $('.modal-title-rules').text('Edit Entry Rule');
                        $('#saving').val("Update");
                        $('#saving').text("Save change");
                        $('#rules_id1').val(id);

                    }
                }
            });
        });

        //Delete customer data. 
        $(document).on('click', '.delete_entryRule', function() {
            var id = (this.id);
            var idd = $('#deleteEntryruleModal').val(id);
            $.ajax({
                cache: false,
                url: "MyTradingRules/entryrules-index.php",
                method: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    $('#deleteEntryruleModal').modal('show');
                }
            })
            $("#deleteEntryrule").click(function() {
                var id = $('#deleteEntryruleModal').val();
                var saving = "Delete"
                $.ajax({
                    url: "MyTradingRules/entryrules-index.php",
                    cache: false,
                    method: "POST",
                    data: {
                        id: id,
                        saving: saving
                    },
                    success: function(data) {
                        fetchUser();
                        $('#deleteEntryruleModal').modal('hide');
                    }
                })
            })
        });
    });
</script>

<div class="modal fade" id="deleteEntryruleModal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container-fluid">
            <div class="row ">
                <div class="modal-header border-bottom col-12">
                    <label class="modal-title">Delete Entry Rule</label>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold text-center p-2"> Are you sure you want to delete this Entry rule?
                </p>
            </div>
            <div class="row modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button class="btn btn-primary mr-3" id="deleteEntryrule" type="button">Delete</button>
            </div>
        </div>
    </div>
</div>