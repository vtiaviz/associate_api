<html>
<head>
    <title>Teste Konekta</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/63cf925c46.js" crossorigin="anonymous"></script>
    <link href="css/demo.css" rel="stylesheet" type="text/css">
    <script src="vendor/Chart.js"></script>
    <script src="src/legend.js"></script>

</head>
<body>
    <div class="container">
        <form method="post" id="user_form"> 
            <div class="fields">
                <div class="row send">
                    <div class="col-sm-3">
                        <input type="text" placeholder="First Name" name="first_name" id="first_name" class="form-control h">
                        <span id="first_name_error" class="text-danger"></span>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Last name" name="last_name" id="last_name" class="form-control h">
                        <span id="last_name_error" class="text-danger"></span>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Participation" name="participation" id="participation" class="form-control h">
                        <span id="participation_error" class="text-danger"></span>
                    </div>
                    <div class="col-sm-2">
                        <input type="hidden" name="user_id" id="user_id" />
                        <input type="hidden" name="data_action" id="data_action" value="Insert" />
                        <input type="submit" name="action" id="action" class="btn btn-info" value="SEND" />
                    </div>
                </div>
            </div>
        </form>
        <br />
        <h2 align="center"><strong>DATA</strong></h2>
        <p align="center">Lorem ipsum dolr sit amet, consectetur adipiscing elit.</p>
        <br />
        <div class="row">
            <div class="col-md-6">
                <div class="panel-body">
                    <span id="success_message"></span>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Participation</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <canvas id="doughnutChart" width="400" height="265"></canvas>
                <div id="doughnutLegend"></div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){

    function fetch_data()
    {
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            type: "POST",
            dataType:"json",
            data:{data_action:'fetch_all'},
            success:function(data)
            {
                console.log(data)
                $('tbody').html(data);
               
            }
        });
    }

    function fetch_part()
    {
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            method:"POST",
            dataType:"json",
            data:{data_action:'fetch_parts'},
            success:function(data)
            {
                console.log(data)
                doughnutChart(data);
                $('#participation_error').html("");
                $('#first_name_error').html("");
                $('#last_name_error').html("");
                $('#action').val('SEND');
            }
        });
    }

    function doughnutChart(data) {
        var dados = [];
        var dado = [];

        var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255-200)+200;
            var g = Math.floor(Math.random() * 255-200)+200;
            var b = Math.floor(Math.random() * 255-200)+200;
            return "rgb(" + r + "," + g + "," + b + ")";
        };

        for (var i in data) {
            dado = {
            'value': data[i].value,
            'color': dynamicColors(),
            'label': data[i].label
            }
            dados.push(dado)
        }

        var data = dados

        var ctx = document.getElementById("doughnutChart").getContext("2d");
        var doughnutChart = new Chart(ctx).Doughnut(data);

        legend(document.getElementById("doughnutLegend"), data, doughnutChart);
    }

    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('#action').val('SEND');
        $('#data_action').val("Insert");
    });

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        $.ajax({
            url:"<?php echo base_url() . 'test_api/action' ?>",
            method:"POST",
            data:$(this).serialize(),
            dataType:"json",
            success:function(data)
            {
                console.log(data)
                if(data.success)
                {
                    $('#user_form')[0].reset();
                    fetch_data();
                    fetch_part();
                    if($('#data_action').val() == "Insert")
                    {
                        $('#success_message').html('<div class="alert alert-success">Data Inserted</div>');
                    }
                    if($('#data_action').val() == "Edit")
                    {
                        $('#success_message').html('<div class="alert alert-success">Data Updated</div>');
                    }
                }

                if(data.error)
                {
                    $('#participation_error').html("");
                    $('#first_name_error').html("");
                    $('#last_name_error').html("");
                    $('#first_name_error').html(data.first_name_error);
                    $('#last_name_error').html(data.last_name_error);
                    $('#participation_error').html(data.participation_error);
                }
            }
        })
    });

    $(document).on('click', '.edit', function(){
        var user_id = $(this).attr('id');
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            method:"POST",
            data:{user_id:user_id, data_action:'fetch_single'},
            dataType:"json",
            success:function(data)
            {
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#participation').val(data.participation);
                $('#user_id').val(user_id);
                $('#action').val('EDIT');
                $('#data_action').val('Edit');
                $('#participation_error').html("");
                $('#first_name_error').html("");
                $('#last_name_error').html("");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var user_id = $(this).attr('id');
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"<?php echo base_url(); ?>test_api/action",
                method:"POST",
                data:{user_id:user_id, data_action:'Delete'},
                dataType:"JSON",
                success:function(data)
                {
                    console.log(data)
                    if(data.success)
                    {
                        $('#success_message').html('<div class="alert alert-success">Data Deleted</div>');
                        fetch_data();
                        fetch_part();
                    }
                }
            })
        }
    });

    fetch_part();
    fetch_data();
    
});
</script>