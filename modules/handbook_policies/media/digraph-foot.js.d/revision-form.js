$(()=>{
    var $checkbox = $('input.revisionForm_minorRevisionCheckbox');
    var toggle = function(){
        var $toggled = $('div.revisionForm_hiddenForMinor,.FieldWrapper-revisionForm_hiddenForMinor');
        if ($checkbox.is(':checked')) {
            $toggled.hide();
        }else {
            $toggled.show();
        }
    };
    $checkbox.change(toggle);
    toggle();
});