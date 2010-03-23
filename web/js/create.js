var isDirty; // has the work changed? Should a prompt for saving take place?
var measures; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var songID; // the song ID.
var songData; // the song data in JSON format.
var sync; // how much syncing are we dealing with?
var note; // which note are we using right now?
var steps = jumps = holds = mines = trips = rolls = lifts = fakes = 0;
const ARR_HEIGHT = 16; // initial arrow heights were 16px.
const SCALE = 3; // scale everything by 2 for now.
const BEATS_PER_MEASURE = 4; // always 4 beats per measure (for our purposes)
const BUFF_TOP = ARR_HEIGHT * SCALE;
const BUFF_LFT = ARR_HEIGHT * SCALE;
const BUFF_RHT = ARR_HEIGHT * SCALE;
const BUFF_BOT = ARR_HEIGHT * SCALE;
const SVG_NS = "http://www.w3.org/2000/svg"; // required for creating elements.
const SVG_BG = "white"; // background of the SVG element and other key things.

const MEASURE_HEIGHT = ARR_HEIGHT * SCALE * BEATS_PER_MEASURE; // the height of our measure.

/*
 * Generate the measures that will hold the arrows.
 */
function genMeasure(x, y, c)
{
  var s = document.createElementNS(SVG_NS, "svg");
  s.setAttribute("x", x);
  s.setAttribute("y", y);
  
  var r1 = document.createElementNS(SVG_NS, "rect");
  r1.setAttribute("x", 0);
  r1.setAttribute("y", 0);
  r1.setAttribute("height", ARR_HEIGHT * SCALE);
  r1.setAttribute("width", columns * ARR_HEIGHT * SCALE);
  s.appendChild(r1);
  
  var t = document.createElementNS(SVG_NS, "text");
  t.setAttribute("x", BEATS_PER_MEASURE);
  t.setAttribute("y", ARR_HEIGHT);
  t.appendChild(document.createTextNode("" + c + ")"));
  s.appendChild(t);
  
  var r2 = document.createElementNS(SVG_NS, "rect");
  r2.setAttribute("x", 0);
  r2.setAttribute("y", ARR_HEIGHT * SCALE);
  r2.setAttribute("height", ARR_HEIGHT * SCALE);
  r2.setAttribute("width", columns * ARR_HEIGHT * SCALE);
  s.appendChild(r2);
  
  var r3 = document.createElementNS(SVG_NS, "rect");
  r3.setAttribute("x", 0);
  r3.setAttribute("y", ARR_HEIGHT * SCALE * 2);
  r3.setAttribute("height", ARR_HEIGHT * SCALE);
  r3.setAttribute("width", columns * ARR_HEIGHT * SCALE);
  s.appendChild(r3);
  
  var r4 = document.createElementNS(SVG_NS, "rect");
  r4.setAttribute("x", 0);
  r4.setAttribute("y", ARR_HEIGHT * SCALE * 3);
  r4.setAttribute("height", ARR_HEIGHT * SCALE);
  r4.setAttribute("width", columns * ARR_HEIGHT * SCALE);
  s.appendChild(r4);
  
  var l1 = document.createElementNS(SVG_NS, "line");
  l1.setAttribute("x1", 0);
  l1.setAttribute("y1", 0.1);
  l1.setAttribute("x2", columns * ARR_HEIGHT * SCALE);
  l1.setAttribute("y2", 0.1);
  s.appendChild(l1);
  
  var l2 = document.createElementNS(SVG_NS, "line");
  l2.setAttribute("x1", 0.05);
  l2.setAttribute("y1", 0);
  l2.setAttribute("x2", 0.05);
  l2.setAttribute("y2", MEASURE_HEIGHT);
  s.appendChild(l2);
  
  var l3 = document.createElementNS(SVG_NS, "line");
  l3.setAttribute("x1", columns * ARR_HEIGHT * SCALE - 0.05);
  l3.setAttribute("y1", 0);
  l3.setAttribute("x2", columns * ARR_HEIGHT * SCALE - 0.05);
  l3.setAttribute("y2", MEASURE_HEIGHT);
  s.appendChild(l3);
  
  return s;
}

/*
 * Generate the text that indicates the BPM or beat pause.
 */
function genText(x, y, st, css)
{
  var s = document.createElementNS(SVG_NS, "text");
  s.setAttribute('x', x);
  s.setAttribute('y', y);
  if (css) { s.setAttribute('class', css); }
  s.appendChild(document.createTextNode(st));
  return s;
}

/*
 * Generate the BPM Change / Stop line.
 */
function genLine(x, y, css)
{
  var s = document.createElementNS(SVG_NS, "line");
  s.setAttribute("x1", x);
  s.setAttribute("y1", y);
  s.setAttribute("x2", x + columns * ARR_HEIGHT * SCALE / 2);
  s.setAttribute("y2", y);
  s.setAttribute("class", css);
  return s;
}

/*
 * Retrieve the number of columns we'll be using today.
 */
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

/*
 * Hide the shadow rectangle from others.
 */
function hideRect()
{
  $("#notes > rect").attr('x', 0).attr('y', 0).hide();
}

function showRect(x, y)
{
  return;
}

function editMode()
{
  $.getJSON("/create/song/" + songID, function(data)
  {
    songData = data;
    $("article > svg").attr("height", MEASURE_HEIGHT * songData.measures + BUFF_TOP * 2);
    columns = getCols();
    $("article > svg").attr("width", (columns + 2) * ARR_HEIGHT * SCALE);
    
    // append the measures.
    for (var i = 0; i < songData.measures; i++)
    {
      $("g#svgMeas").append(genMeasure(ARR_HEIGHT * SCALE, BUFF_TOP + MEASURE_HEIGHT * i, i + 1));
    }
    
    // place the BPM data.
    var bpms = songData.bpms;
    for (var i = 0; i < bpms.length; i++)
    {
      $("#svgSync").append(genText($("article > svg").attr("width") - ARR_HEIGHT * SCALE + 2 * SCALE,
          BUFF_TOP + bpms[i].beat * ARR_HEIGHT * SCALE + 2 * SCALE, bpms[i].bpm, 'bpm'));
      $("#svgSync").append(genLine(columns * ARR_HEIGHT * SCALE / 2 + ARR_HEIGHT * SCALE,
          BUFF_TOP + bpms[i].beat * ARR_HEIGHT * SCALE, 'bpm'));
    }
    
    var stps = songData.stps;
    for (var i = 0; i < stps.length; i++)
    {
      $("#svgSync").append(genText(0, BUFF_TOP + stps[i].beat * ARR_HEIGHT * SCALE + 2 * SCALE,
          stps[i].time, 'stop'));
      $("#svgSync").append(genLine(ARR_HEIGHT * SCALE, BUFF_TOP + stps[i].beat * ARR_HEIGHT * SCALE, 'stop'));
    }
    
  });
  $("nav *.edit").show();
  $("nav *.choose").hide();
  
}

function init()
{
  $("nav *.edit").hide();
  $("#notes > rect").hide();
  $("nav *.choose").show();
  $("#stylelist").attr("disabled", "disabled");
  $("article > svg").attr("width", "224px");
  $("article > svg").attr("height", "448px");

  // reset the drop downs (and corresponding variables) to default values.
  $("#songlist").val('');
  $("#stylelist").val('');
  $("#quanlist").val(4);
  $("#typelist").val(1);
  sync = 4;
  note = 1;


  $("#svgMeas").empty();
  $("#svgSync").empty();
  
  isDirty = false;
  measures = new Array({}, {}); // routine compatible.
  columns = 5; // reasonable default.
  
  
}

$(document).ready(function()
{
  init();
  
  $("#notes > rect").attr('width', ARR_HEIGHT * SCALE).attr('height', ARR_HEIGHT * SCALE);
  $("#songlist").val('');
  
  /*
   * The various action functions are set here.
   */
  $("article > svg").mouseout(function(){ hideRect(); });
  $("article > svg").mouseover(function(){
    
  });
  
  $('#songlist').change(function(){
    songID = $("#songlist > option:selected").val();
    if (songID.length > 0) { $("#stylelist").removeAttr("disabled"); }
    else { $("#stylelist").attr("disabled", "disabled"); }
  });
  $("#stylelist").change(function(){ editMode(); });
  
  $("#quanlist").change(function() { sync = $("#quanlist > option:selected").val();});
  $("#typelist").change(function() { note = $("#typelist > option:selected").val();});
  
});