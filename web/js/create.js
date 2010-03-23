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

function genMeasure(x, y)
{
  var s = document.createElementNS(SVG_NS, "svg");
  s.setAttribute("x", x);
  s.setAttribute("y", y);
  
  var r1 = document.createElementNS(SVG_NS, "rect");
  r1.setAttribute("x", 0);
  r1.setAttribute("y", 0);
  r1.setAttribute("height", ARR_HEIGHT * SCALE);
  r1.setAttribute("width", columns * ARR_HEIGHT * SCALE);
  r1.setAttribute("fill", SVG_BG);
  s.appendChild(r1);
  
  var r2 = document.createElementNS(SVG_NS, "rect");
  r2.setAttribute("x", 0);
  r2.setAttribute("y", ARR_HEIGHT * SCALE);
  r2.setAttribute("height", ARR_HEIGHT * SCALE);
  r2.setAttribute("width", columns * ARR_HEIGHT * SCALE);
  r2.setAttribute("fill", "#dcdcdc");
  s.appendChild(r2);
  
  var r3 = document.createElementNS(SVG_NS, "rect");
  r3.setAttribute("x", 0);
  r3.setAttribute("y", ARR_HEIGHT * SCALE * 2);
  r3.setAttribute("height", ARR_HEIGHT * SCALE);
  r3.setAttribute("width", columns * ARR_HEIGHT * SCALE);
  r3.setAttribute("fill", SVG_BG);
  s.appendChild(r3);
  
  var r4 = document.createElementNS(SVG_NS, "rect");
  r4.setAttribute("x", 0);
  r4.setAttribute("y", ARR_HEIGHT * SCALE * 3);
  r4.setAttribute("height", ARR_HEIGHT * SCALE);
  r4.setAttribute("width", columns * ARR_HEIGHT * SCALE);
  r4.setAttribute("fill", "#bebebe");
  s.appendChild(r4);
  
  var l1 = document.createElementNS(SVG_NS, "line");
  l1.setAttribute("x1", 0);
  l1.setAttribute("y1", 0.1);
  l1.setAttribute("x2", columns * ARR_HEIGHT * SCALE);
  l1.setAttribute("y2", 0.1);
  l1.setAttribute("fill", "black");
  l1.setAttribute("stroke", "black");
  l1.setAttribute("stroke-width", 0.1);
  s.appendChild(l1);
  
  var l2 = document.createElementNS(SVG_NS, "line");
  l2.setAttribute("x1", 0.05);
  l2.setAttribute("y1", 0);
  l2.setAttribute("x2", 0.05);
  l2.setAttribute("y2", MEASURE_HEIGHT);
  l2.setAttribute("fill", "black");
  l2.setAttribute("stroke", "black");
  l2.setAttribute("stroke-width", 0.1);
  s.appendChild(l2);
  
  var l3 = document.createElementNS(SVG_NS, "line");
  l3.setAttribute("x1", columns * ARR_HEIGHT * SCALE - 0.05);
  l3.setAttribute("y1", 0);
  l3.setAttribute("x2", columns * ARR_HEIGHT * SCALE - 0.05);
  l3.setAttribute("y2", MEASURE_HEIGHT);
  l3.setAttribute("fill", "black");
  l3.setAttribute("stroke", "black");
  l3.setAttribute("stroke-width", 0.1);
  s.appendChild(l3);
  
  return s;
}

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
    $("article > svg").attr("height", MEASURE_HEIGHT * songData.measures + MEASURE_HEIGHT);
    columns = getCols();
    $("article > svg").attr("width", (columns + 2) * ARR_HEIGHT * SCALE);
    
    // append the measures.
    for (var i = 0; i < songData.measures; i++)
    {
      $("g#notes").append(genMeasure(ARR_HEIGHT * SCALE, ARR_HEIGHT * BEATS_PER_MEASURE + MEASURE_HEIGHT * i));
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