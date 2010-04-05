var diff; // the difficulty presently chosen.

$(document).ready(function()
{
  $("#validate_diff > option:first-child").before("<option value=''>Choose!</option>");
  diff = '';
  $("#validate_diff").val('');
  $("#validate_edits").val(0);
  $("#submit").attr('disabled', 'disabled');
  
  $("#validate_edits").change(function()
  {
    $("#submit").attr('disabled', 'disabled');
    $("#validate_diff > option:not(:first-child)").hide();
    var sid = Math.floor($("#validate_edits").val());
    if (sid > 0)
    {
      diff = $("#validate_diff").val();
      $.getJSON(baseURL + "/diff/" + sid, function(data){
        for (var d in data)
        {
          if (d === "id") { continue; }
          if (data[d]) { $("#validate_diff > option[value=" + d + "]").show(); }
        }
        if ($("#validate_diff > option[value=" + diff + "]").attr('style').indexOf('none') == -1)
        {
          $("#submit").removeAttr('disabled');
        }
        else
        {
          diff = '';
        }
        $("#validate_diff").val(diff);
      });
    }
  });
  
  $("#validate_diff").change(function()
  {
    diff = $("#validate_diff").val();
    if (diff.length)
    {
      $("#submit").removeAttr('disabled');
    }
    else
    {
      $("#submit").attr('disabled', 'disabled');
    }
  });
});