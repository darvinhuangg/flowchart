/* global $ */
function openCreateModal() {
    $('#createModal').modal();
}

$(document).ready(function() {
    var $flowchart = $('#flowchartworkspace');
    var $container = $flowchart.parent();


    // Apply the plugin on a standard, empty div...
    $flowchart.flowchart({
        data: defaultFlowchartData,
        defaultSelectedLinkColor: '#000055',
        grid: 10,
        multipleLinksOnInput: true,
        multipleLinksOnOutput: true,
    });

    $('#attr_link, #attr_update').change(function(){
        $("#url").val("").attr("readonly",true);
        if($("#attr_link").is(":checked")){
            $("#url").removeAttr("readonly");
            $("#url").focus();
        }
    })


    function getOperatorData($element) {
        var nbInputs = parseInt($element.data('nb-inputs'), 10);
        var nbOutputs = parseInt($element.data('nb-outputs'), 10);
        var data = {
            properties: {
                title: $element.text(),
                inputs: {},
                outputs: {}
            }
        };

        var i = 0;
        for (i = 0; i < nbInputs; i++) {
            data.properties.inputs['input_' + i] = {
                label: '*'
            };
        }
        for (i = 0; i < nbOutputs; i++) {
            data.properties.outputs['output_' + i] = {
                label: '*'
            };
        }

        return data;
    }

    //-----------------------------------------
    //--- operator and link properties
    //--- start
    var $operatorProperties = $('#operator_properties');
    $operatorProperties.hide();
    var $linkProperties = $('#link_properties');
    $linkProperties.hide();
    var $operatorTitle = $('#operator_title');
    var $operatorLinkUpdate = $('#operator_link_update');
    var $operatorAttrModalUpdate = $('#attr_update_update');
    var $operatorAttrLinkUpdate = $('#attr_link_update');
    var $linkColor = $('#link_color');

    $flowchart.flowchart({
        onOperatorSelect: function(operatorId) {
            $operatorProperties.show();
            $operatorTitle.val($flowchart.flowchart('getOperatorTitle', operatorId));

            var selectedCard = $flowchart.flowchart('getData');

            $operatorLinkUpdate.val(selectedCard.operators[operatorId].url);
            $operatorLinkUpdate.prop('disabled', selectedCard.operators[operatorId].url == '' || selectedCard.operators[operatorId].url == undefined ? true : false);
            $operatorAttrModalUpdate.prop('checked', selectedCard.operators[operatorId].url == '' || selectedCard.operators[operatorId].url == undefined ? true : false);
            $operatorAttrLinkUpdate.prop('checked', selectedCard.operators[operatorId].url ? true : false);

            console.log(selectedCard);

            // if(selectedCard.operators[operatorId].url !== undefined || selectedCard.operators[operatorId].url !== ''){
            //     $operatorLink.val(selectedCard.operators[operatorId].url)
            // }


            return true;
        },
        onOperatorUnselect: function() {
            $operatorProperties.hide();
            return true;
        },
        onLinkSelect: function(linkId) {
            $linkProperties.show();
            $linkColor.val($flowchart.flowchart('getLinkMainColor', linkId));
            return true;
        },
        onLinkUnselect: function() {
            $linkProperties.hide();
            return true;
        }
    });

    $operatorTitle.keyup(function() {
        // var selectedOperatorId = $flowchart.flowchart('getSelectedOperatorId');
        // if (selectedOperatorId != null) {
        //     $flowchart.flowchart('setOperatorTitle', selectedOperatorId, $operatorTitle.val());
        // }
    });

    $linkColor.change(function() {
        var selectedLinkId = $flowchart.flowchart('getSelectedLinkId');
        if (selectedLinkId != null) {
            $flowchart.flowchart('setLinkMainColor', selectedLinkId, $linkColor.val());
        }
    });
    //--- end
    //--- operator and link properties
    //-----------------------------------------

    //-----------------------------------------
    //--- delete operator / link button
    //--- start
    $('#delete_selected_button').click(function() {
        $flowchart.flowchart('deleteSelected');
    });

    //--- end
    //--- delete operator / link button
    //-----------------------------------------

    $('#update_selected_button').click(function() {
        var selectedOperatorId = $flowchart.flowchart('getSelectedOperatorId');        
        var cards = $flowchart.flowchart('getData');  
        var selectedCard = cards.operators[selectedOperatorId];

        
        var operatorData = {
            top: selectedCard.top,
            left: selectedCard.left,
            url: $operatorLinkUpdate.val(),
            properties: selectedCard.properties,
        }

        // console.log(operatorData);
        $flowchart.flowchart('createOperator', selectedOperatorId, operatorData);
        $flowchart.flowchart('setOperatorTitle', selectedOperatorId, $operatorTitle.val());

        $operatorProperties.hide();
    })

    $('#generate_flowchart').click(function() {
        SaveToLocalStorage();
        window.open('/generate', '_blank');
    })

    $('#attr_link').change(function(){
        $('#url').prop('disabled', false);
        $('#url').val('');
    })

    $('#attr_update').change(function(){
        $('#url').prop('disabled', true);
        $('#url').val('');
    }) 

    $('#attr_link_update').change(function(){
        $('#operator_link_update').prop('disabled', false);
        $('#operator_link_update').val('');
    })

    $('#attr_update_update').change(function(){
        $('#operator_link_update').prop('disabled', true);
        $('#operator_link_update').val('');
    })    

    


    //-----------------------------------------
    //--- create operator button
    //--- start
    var operatorI = 0;
    $('#create_operator').click(function() {
        var operatorTitle = $('#card_title').val();
        var url_href = $('#url').val();

        var operatorId = 'created_operator_' + operatorI;
        var operatorData = {
            top: ($flowchart.height() / 2) - 30,
            left: ($flowchart.width() / 2) - 100 + (operatorI * 10),
            url: url_href,
            properties: {
                title: operatorTitle,
                inputs: {
                    input_1: {
                        label: '*',
                    }
                },
                outputs: {
                    output_1: {
                        label: '*',
                    }
                }
            },
        };

        operatorI++;
        $("#createModal").modal('hide');
        $flowchart.flowchart('createOperator', operatorId, operatorData);

    });
    //--- end
    //--- create operator button
    //-----------------------------------------




    //-----------------------------------------
    //--- draggable operators
    //--- start
    //var operatorId = 0;
    var $draggableOperators = $('.draggable_operator');
    $draggableOperators.draggable({
        cursor: "move",
        opacity: 0.7,

        // helper: 'clone',
        appendTo: 'body',
        zIndex: 1000,

        helper: function(e) {
            var $this = $(this);
            var data = getOperatorData($this);
            return $flowchart.flowchart('getOperatorElement', data);
        },
        stop: function(e, ui) {
            var $this = $(this);
            var elOffset = ui.offset;
            var containerOffset = $container.offset();
            if (elOffset.left > containerOffset.left &&
                elOffset.top > containerOffset.top &&
                elOffset.left < containerOffset.left + $container.width() &&
                elOffset.top < containerOffset.top + $container.height()) {

                var flowchartOffset = $flowchart.offset();

                var relativeLeft = elOffset.left - flowchartOffset.left;
                var relativeTop = elOffset.top - flowchartOffset.top;

                var positionRatio = $flowchart.flowchart('getPositionRatio');
                relativeLeft /= positionRatio;
                relativeTop /= positionRatio;

                var data = getOperatorData($this);
                data.left = relativeLeft;
                data.top = relativeTop;

                $flowchart.flowchart('addOperator', data);
            }
        }
    });
    //--- end
    //--- draggable operators
    //-----------------------------------------


    //-----------------------------------------
    //--- save and load
    //--- start
    function Flow2Text() {
        var data = $flowchart.flowchart('getData');
        $('#flowchart_data').val(JSON.stringify(data, null, 2));
    }
    $('#get_data').click(Flow2Text);

    function Text2Flow() {
        var data = JSON.parse($('#flowchart_data').val());
        $flowchart.flowchart('setData', data);
    }
    $('#set_data').click(Text2Flow);

    /*global localStorage*/
    function SaveToLocalStorage() {
        if (typeof localStorage !== 'object') {
            alert('local storage not available');
            return;
        }
        Flow2Text();
        localStorage.setItem("stgLocalFlowChart", $('#flowchart_data').val());
    }
    $('#save_local').click(SaveToLocalStorage);

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
    $('#load_local').click(LoadFromLocalStorage);
    //--- end
    //--- save and load
    //-----------------------------------------


});

var defaultFlowchartData = {
    operators: {
        operator1: {
            top: 80,
            left: 300,
            url:'',
            properties: {
                title: 'Card 1',
                inputs: {
                    input_1: {
                        label: '*',
                    },
                    input_2: {
                        label: '*',
                    },
                },
                outputs: {
                    output_1: {
                        label: '*',
                    },
                    output_2: {
                        label: '*',
                    },
                }
            }
        },
    },
    links: {
        
    }
};
if (false) console.log('remove lint unused warning', defaultFlowchartData);