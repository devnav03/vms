var Nestable = function () {

    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            if (output) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            }
        } else {
            if (output) {
                output.val('JSON browser support required for this demo.');
            }
            
        }
    };



    // activate Nestable for list 2
    $('#nestable_list_2').nestable({
        group: 1,
        maxDepth: 2
    })
        .on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable_list_2').data('output', $('#nestable_list_2_output')));


}();