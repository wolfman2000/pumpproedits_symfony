var isDirty; // has the work changed? Should a prompt for saving take place?
var measures; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var songID;
var gNote;
var songData;

function editMode()
{
  $.getJSON("/create/song/" + songID, function(data){songData = data;});
  $("nav *.edit").show();
  $("nav *.choose").hide();
}

function init()
{
  $("nav *.edit").hide();
  $("nav *.choose").show();
  $("#stylelist").attr("disabled", "disabled");
  $("article > svg").attr("width", "224px");
  $("article > svg").attr("height", "448px");
  
  
  $("article > svg").append($('g'));
  
  isDirty = false;
  measures = new Array(new Array(), new Array()); // routine
  columns = 5; // reasonable default.
  
  
}

$(document).ready(function()
{
  init();
  $("#songlist").val('');
  $("#stylelist").val('');
  $('#songlist').change(function(){
    songID = $("#songlist > option:selected").val();
    $("#stylelist").removeAttr("disabled");
  });
  $("#stylelist").change(function(){ editMode(); });
  
});