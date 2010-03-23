var isDirty; // has the work changed? Should a prompt for saving take place?
var measures; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var songID;
var songs;
var gNote;
var songData;

function editMode()
{
  songID = 7;
  $.ajax({
    url: "/create/song/" + songID, cache: true,
    contentType: "application/xml",
    dataType: "xml", success: function(data)
    {
      songData = data;
    },
  });
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
  
  songs = {};
  $('#songlist > option:not(:first-child)').each(
    function(){
      //songs[$(this).attr('value')] = $(this).text();
      songs[$(this).val()]  = $(this).text();
    }
  );
});