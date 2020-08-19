

<!---MODAL Create, Edit new Strategy WITH AJAX ---->
<html>

<head>
    <link href="my-strategy/mystrategy-style.css" rel="stylesheet">
</head>

<body>
    <div class="row col-12 table-row">
        <button type="button" id="Strategy_modal_button" class="btn btn-link border" data-target="#StrategyModal">Create Strategy</button>
    </div>
    <div id="StrategyOutput" class="row table-responsive m-auto">
        <!-- Data will load under this tag!-->
    </div>
</body>

</html>


<!-- This is Customer Modal. It will be use for Create new Records and Update Existing Records!-->
<div id="StrategyModal" class="modal fade" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container-fluid ">
            <div class="row ">
                <div class="modal-header border-bottom col-12">
                    <label class="modal-title"></label>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <label class="mb-1" for="strategy_name">Strategy Name</label>
                <input type="text" name="strategy_name" id="strategy_name" class="form-control mt-0">
                <div style="height:10px" class=""><span class="error" id="error_strategyname"></span></div>
                <br />
                <label class="mb-1" for="textarea">Description:</label>
                <textarea type="textarea" name="description" id="description" rows="4" placeholder="Type your strategy description" class="form-control mt-0"></textarea>
                <div style="height:10px" class=""><span class="error" id="error_description"></span></div>
                <br />
            </div>
            <div class="row modal-footer">
                <input type="hidden" name="customerid" id="customerid" />
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="button" name="save" id="save" class="btn btn-primary save mr-3">Create Strategy</button>
            </div>
        </div>
    </div>
</div>
 
<script>
    //FETCH STRATEGY DROPDOWN jquery ajax
    $(document).ready(function() {

        $("#error_strategyname").hide();
        $("#error_description").hide();
        var error_strategyname = false;
        var error_description = false;
        $("#strategy_name").focusout(function() {
            check_strategyname();
        });
        $("#description").focusout(function() {
            check_description();
        });
 
        function check_strategyname() {
            var check_strategyname = $("#strategy_name").val().length;

            if ((check_strategyname < 1) || (check_strategyname > 40)) {
                $("#error_strategyname").html("Alphanumeric between 1-40 charachters");
                $("#error_strategyname").show();
                $("#strategy_name").addClass('invalid');
                error_strategyname = true;
            } else {
                error_strategyname = false;
                $("#error_strategyname").hide();
                $("#strategy_name").removeClass('invalid');
            }
        }

        function check_description() {
            var check_description = $("#description").val().length;

            if ((check_description > 600)) {
                $("#error_description").html("Maximum 600 charachters!");
                $("#error_description").show();
                $("#description").addClass('invalid');
                error_description = true;
            } else {
                error_description = false;
                $("#error_description").hide();
                $("#description").removeClass('invalid');
            }
        }

        function hide_errormessage() {
            $('#StrategyModal').modal('show');
            $("#error_description").hide();
            $("#error_strategyname").hide();
            $("#strategy_name").removeClass('invalid');
            $("#description").removeClass('invalid');
        }

        fetchUser();
        function fetchUser() {
            var save = "load";
            $.ajax({
                url: "my-strategy/mystrategy-action.php",
                method: "POST",
                data: {
                    save: save
                },
                success: function(data) {
                    $('#StrategyOutput').html(data);
                }
            });
        }

        $('#Strategy_modal_button').click(function() {
            $('#strategy_name').val('');
            $('#description').val('');
            hide_errormessage();
            $('.modal-title').text("Create Strategy");
            $('#save').val('Create Strategy');
            $('#save').text('Create Strategy');
        });

        $('#save').click(function() {
            var strategy_name = $('#strategy_name').val();
            var description = $('#description').val();
            var id = $('#customerid').val();
            var save = $('#save').val();
            if (strategy_name != '' && error_strategyname == false && error_description == false) //This condition will check both variable has some value
            {
                $.ajax({
                    url: "my-strategy/mystrategy-action.php",
                    method: "POST",
                    data: {
                        strategy_name: strategy_name,
                        description: description,
                        id: id,
                        save: save,
                    },
                    success: function(response) {
                        if (response == 'exists') {
                            $("#strategy_name").addClass('invalid');
                            $("#error_strategyname").html("This strategy name already exists");
                            $("#error_strategyname").show();
                        }else {
                            $('#StrategyModal').modal('hide');
                        fetchUser();
                     
                        }  
                    }
                });
            } else {
                $("input.invalid").focus();
            }
        });

        // Update customer data
        $(document).on('click', '.strategy_edit', function() {
            var id = $(this).attr("id");
            var save = "Selectt";
            $.ajax({
                url: "my-strategy/mystrategy-action.php",
                method: "POST",
                data: {
                    id: id,
                    save: save
                },
                dataType: "json",
                success: function(response) {
                    $.each(response, function(index, element) {
                        $('#strategy_name').val(element.name);
                        $('#description').val(element.description);
                    });
                     if (response == 'exists') {
                            $("#strategy_name").addClass('invalid');
                            $("#error_strategyname").html("This strategy name already exists");
                            $("#error_strategyname").show();
                        } else {
                         hide_errormessage();
                            $('.modal-title').text("Edit Strategy");
                            $('#save').val("Save");
                            $('#save').text("Save");
                            $('#customerid').val(id);
                        }
                }
            });
        });

        //This JQuery code is for Delete customer data.
        $(document).on('click', '.delete_strategy', function() {
            var id = (this.id);
            var idd = $("#deleteStrategyModal").val(id);
            var save = "Selectt";
            $.ajax({
                cache: false,
                url: "my-strategy/mystrategy-action.php",
                method: "POST",
                data: {
                    id: id,
                    save: save
                },
                success: function(data) {
                    $('#deleteStrategyModal').modal('show');
                }
            });

            $("#delete_strategy").click(function() {
                var id = $('#deleteStrategyModal').val();
                var save = $('#delete_strategy').text();
                $.ajax({
                    url: "my-strategy/mystrategy-action.php",
                    method: "POST",
                    data: {
                        id: id,
                        save: save
                    },
                    success: function(data) {
                        fetchUser();
                        $('#deleteStrategyModal').modal('hide');
                    }
                })
            })
        });
    });
</script>

<div class="modal fade " id="deleteStrategyModal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container-fluid">
            <div class="row">
                <div class="modal-header border-bottom col-12">
                    <label>Delete Strategy</label>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold text-center p-2"> Are you sure you want to delete this strategy?</p>
            </div>
            <div class="row modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button class="btn btn-primary mr-3" id="delete_strategy" type="button">Delete</button>
            </div>
        </div>
    </div>
</div>