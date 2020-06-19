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
                <h4>Generated Flowchart</h4>
                <div id="chart_container">
                    <div class="flowchart-example-container" id="flowchartworkspace"></div>
                </div>
                <div>
                    <textarea id="flowchart_data" style="display: none;"></textarea>
                </div>

                <!-- Create Modal -->
                <div class="modal fade" id="showModal" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                      <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Card Explanation</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="card_title" class="font-weight-bold">Card's title: </label>
                                    <div id="card_title"></div>
                                </div>
                                <div class="text-left font-weight-bold">
                                    Card's behaviour can be changed when click on card in previous tab
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            /* global $ */
            $(document).ready(function() {
                var $flowchart = $('#flowchartworkspace');                


                // Apply the plugin on a standard, empty div...
                $flowchart.flowchart({
                    grid: 10,
                    multipleLinksOnInput: true,
                    multipleLinksOnOutput: true,
                    canUserMoveOperators:false,
                    canUserEditLinks:false,
                });

                LoadFromLocalStorage();


                //-----------------------------------------
                //--- operator and link properties
                //--- start

                $flowchart.flowchart({
                    onOperatorSelect: function(operatorId) {                        
                        var cards = $flowchart.flowchart('getData');
                        var selectedCard = cards.operators[operatorId];
                        console.log(selectedCard);

                        if(selectedCard.url == '' || selectedCard.url == undefined) {
                            document.getElementById('card_title').innerHTML = selectedCard.properties.title
                            $('#showModal').modal();
                        } else {
                            window.open(selectedCard.url, '_blank');
                        }
                    },
                });


                //-----------------------------------------
                //--- save and load
                //--- start

                function Text2Flow() {
                    var data = JSON.parse($('#flowchart_data').val());
                    $flowchart.flowchart('setData', data);
                }

                function LoadFromLocalStorage() {
                    if (typeof localStorage !== 'object') {
                        alert('local storage not available');
                        return;
                    }
                    var s = localStorage.getItem("stgLocalFlowChart");
                    if (s != null) {
                        $('#flowchart_data').val(s);
                        Text2Flow();
                    }
                    else {
                        alert('local storage empty');
                    }
                }
                //--- end
                //--- save and load
                //-----------------------------------------


            });

        </script>
    </body>
</html>
