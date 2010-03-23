var isDirty; // has the work changed? Should a prompt for saving take place?
var measures; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var songID;
var gNote;
var songData;
const ARR_HEIGHT = 16;
const SCALE = 2;
const BEATS_PER_MEASURE = 4;

const MEASURE_HEIGHT = ARR_HEIGHT * SCALE * BEATS_PER_MEASURE;

function editMode()
{
  $.getJSON("/create/song/" + songID, function(data)
  {
    songData = data;
    $("article > svg").attr("height", MEASURE_HEIGHT * songData.measures + MEASURE_HEIGHT / 2);
  });
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