var diff; // the difficulty presently chosen.

$(document).ready(function()
{
  $("#validate_diff > option:first-child").before("<option value=''>Choose!</option>");
  $("#validate_diff").val('');
  $("#submit").attr('disabled', 'disabled');
  
  $("#validate_edits").change(function()
  {
    
  });
  
});