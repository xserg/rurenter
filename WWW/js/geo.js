var path='/';
function getSelect(j) {
    var options = '';
    for (var i = 0; i < j.length; i++) {
        options += '<option value="' + j[i].id + '">' + j[i].name + '</option>';
    }
    return options;
}

$(function(){
    $("select#countryId").change(function(){
        $.getJSON("" + path + "?op=countries&act=json",{parent_id: $(this).val()}, function(j){
            $("select#regionId").html(getSelect(j));
            $("select#regionId").change();
            $("select#regionId").attr({ disabled: j.length ? "" : "disabled"});
        })
    })
    $("select#regionId").change(function(){
        $.getJSON("" + path + "?op=countries&act=json",{country_id: $("select#countryId").val(), parent_id: $(this).val() ? $(this).val() : null}, function(j){
            $("select#cityId").html(getSelect(j));
        })
    })
})