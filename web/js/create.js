var isDirty; // has the work changed? Should a prompt for saving take place?
var measures; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var songs;
var gNote;


function init()
{
  $("nav *.edit").hide();
  $("nav *.choose").show();
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
  
  songs = {};
  $('#songlist > option:not(:first-child)').each(
    function(){
      //songs[$(this).attr('value')] = $(this).text();
      songs[$(this).val()]  = $(this).text();
    }
  );
});