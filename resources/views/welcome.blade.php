<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

        <!-- Flowchart CSS and JS -->
        <link rel="stylesheet" href="{{URL::asset('dist/jquery.flowchart.css')}}">
        <script src="{{URL::asset('dist/jquery.flowchart.js')}}"></script>
        <script src="{{URL::asset('app.js')}}"></script>

        <!-- Styles -->
        <style>
            .flowchart-example-container {
                width: 800px;
                height: 400px;
                background: white;
                border: 1px solid #BBB;
                margin-bottom: 10px;
            }

            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <h1>Flowchart</h1>
                <div id="chart_container">
                    <div class="flowchart-example-container" id="flowchartworkspace"></div>
                </div>
                <div class="draggable_operators">
                    <div class="draggable_operators_label font-weight-bold">
                        Operators (drag and drop them in the flowchart):
                    </div>
                    <div class="draggable_operators_divs btn-group mt-2" role="group">
                        <div class="draggable_operator btn btn-info" data-nb-inputs="1" data-nb-outputs="0">1 input</div>
                        <div class="draggable_operator btn btn-info" data-nb-inputs="0" data-nb-outputs="1">1 output</div>
                        <div class="draggable_operator btn btn-info" data-nb-inputs="1" data-nb-outputs="1">1 input &amp; 1 output</div>
                        <div class="draggable_operator btn btn-info" data-nb-inputs="1" data-nb-outputs="2">1 in &amp; 2 out</div>
                        <div class="draggable_operator btn btn-info" data-nb-inputs="2" data-nb-outputs="1">2 in &amp; 1 out</div>
                        <div class="draggable_operator btn btn-info" data-nb-inputs="2" data-nb-outputs="2">2 in &amp; 2 out</div>
                    </div>
                </div>

                <div id="operator_properties" style="display: block;" class="mt-4 text-left border rounded p-2">
                    <div class="form-group">
                        <label for="operator_title" class="font-weight-bold">Card's title: </label>
                        <input id="operator_title" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="operator_title" class="font-weight-bold">Add Attributes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="attribute" id="attr_link_update" value="1">
                        <label class="form-check-label font-weight-bold" for="attr_link">Link</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="attribute" id="attr_update_update" value="2">
                        <label class="form-check-label font-weight-bold" for="attr_update">Modal Click</label>
                    </div>
                    <div class="form-group">
                        <label for="url" class="font-weight-bold">Link:</label>
                        <input id="operator_link_update" type="text" class="form-control" placeholder="URL Here ...">
                    </div>
                    <button id="update_selected_button" class="btn btn-primary mt-2">Update</button>
                    <button id="delete_selected_button" class="btn btn-danger mt-2">Delete</button>
                </div>
                
                <button class="btn btn-primary mt-4" onclick="openCreateModal()">Create operator</button>
                <button class="btn btn-primary mt-4" id="save_local">Save Flowchart</button>
                <button class="btn btn-primary mt-4" id="load_local">Load Flowchart</button>
                <button class="btn btn-primary mt-4" id="generate_flowchart">Generate Flowchart</button>

                <!-- Create Modal -->
                <div class="modal fade" id="createModal" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                      <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Create New Card</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="card_title" class="font-weight-bold">Card's title: </label>
                                    <input id="card_title" type="text" class="form-control" placeholder="Title Here ...">
                                </div>
                                <div class="form-check form-check-inline">
                                    <label for="card_title" class="font-weight-bold">Add Attributes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="attribute" id="attr_link" value="1">
                                    <label class="form-check-label font-weight-bold" for="attr_link">Link</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="attribute" id="attr_update" value="2">
                                    <label class="form-check-label font-weight-bold" for="attr_update">Modal Click</label>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="font-weight-bold">Link:</label>
                                    <input id="url" type="text" class="form-control" placeholder="URL Here ..." readonly>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="create_operator">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="mt-4" style="display: none;">
                    <textarea id="flowchart_data" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </body>
</html>
