var isDirty; // has the work changed? Should a prompt for saving take place?
var measures; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var songID; // the song ID.
var songData; // the song data in JSON format.
var gNote; // the group of note data.
var steps = jumps = holds = mines = trips = rolls = lifts = fakes = 0;
const ARR_HEIGHT = 16; // initial arrow heights were 16px.
const SCALE = 2; // scale everything by 2 for now.
const BEATS_PER_MEASURE = 4; // always 4 beats per measure (for our purposes)
const SVG_NS = "http://www.w3.org/2000/svg"; // required for creating elements.
const SVG_BG = "white"; // background of the SVG element and other key things.

const MEASURE_HEIGHT = ARR_HEIGHT * SCALE * BEATS_PER_MEASURE; // the height of our measure.

function getCols()
{
  switch ($("#stylelist").val())
  {
    case "s": return 5;
    case "h": return 6;
    case "d": case "r": return 10;
    default: return 0;
  }
}

function editMode()
{
  $.getJSON("/create/song/" + songID, function(data)
  {
    songData = data;
    $("article > svg").attr("height", MEASURE_HEIGHT * songData.measures + MEASURE_HEIGHT / 2);
    columns = getCols();
    $("article > svg").attr("width", (columns + 2) * ARR_HEIGHT * SCALE);
    
    // append the measures.
    for (var i = 0; i < songData.measures; i++)
    {
      var s = document.createElementNS(SVG_NS, 'svg');
      s.setAttribute('x', ARR_HEIGHT * SCALE);
      s.setAttribute('y', MEASURE_HEIGHT + MEASURE_HEIGHT * i);
      
      
      var m = document.createElementNS(SVG_NS, 'use');
      m.setAttribute('xlink:href', '/svg/arrowdef.svg#measure');
      m.setAttribute('transform', 'scale(' + columns + ', 1)');
      s.appendChild(m);
      $("g#notes").append(s);
    }
    
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
  $("#notes").empty(); // remove what's there.
  
  isDirty = false;
  measures = new Array({}, {}); // routine compatible.
  columns = 5; // reasonable default.
  
  
}

$(document).ready(function()
{
  $("svg > g");
  
  init();

  var g = document.createElementNS(SVG_NS, 'g');
  g.setAttribute('id', 'notes');
  $("article > svg").append(g);
  
  $("#songlist").val('');
  $("#stylelist").val('');
  $('#songlist').change(function(){
    songID = $("#songlist > option:selected").val();
    $("#stylelist").removeAttr("disabled");
  });
  $("#stylelist").change(function(){ editMode(); });
  
});