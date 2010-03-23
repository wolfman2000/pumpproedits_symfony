var isDirty; // has the work changed? Should a prompt for saving take place?
var measures; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var width; // compliment to columns
var songID; // the song ID.
var songData; // the song data in JSON format.
var sync; // how much syncing are we dealing with?
var note; // which note are we using right now?
var steps = jumps = holds = mines = trips = rolls = lifts = fakes = 0;
var mX; // mouse position at X.
var mY; // mouse position at Y.
const ARR_HEIGHT = 16; // initial arrow heights were 16px.
const SCALE = 3; // scale everything by 2 for now.
const ADJUST_SIZE = ARR_HEIGHT * SCALE; // 
const BEATS_PER_MEASURE = 4; // always 4 beats per measure (for our purposes)

// These constants may change later, depending on how much spacing is wanted.
const BUFF_TOP = ADJUST_SIZE;
const BUFF_LFT = ADJUST_SIZE;
const BUFF_RHT = ADJUST_SIZE;
const BUFF_BOT = ADJUST_SIZE;

const SVG_NS = "http://www.w3.org/2000/svg"; // required for creating elements.
const SVG_BG = "white"; // background of the SVG element and other key things.

const MEASURE_HEIGHT = ADJUST_SIZE * BEATS_PER_MEASURE; // the height of our measure.

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
  r1.setAttribute("height", ADJUST_SIZE);
  r1.setAttribute("width", columns * ADJUST_SIZE);
  s.appendChild(r1);
  
  var t = document.createElementNS(SVG_NS, "text");
  t.setAttribute("x", BEATS_PER_MEASURE);
  t.setAttribute("y", ARR_HEIGHT);
  t.appendChild(document.createTextNode("" + c + ")"));
  s.appendChild(t);
  
  var r2 = document.createElementNS(SVG_NS, "rect");
  r2.setAttribute("x", 0);
  r2.setAttribute("y", ADJUST_SIZE);
  r2.setAttribute("height", ADJUST_SIZE);
  r2.setAttribute("width", columns * ADJUST_SIZE);
  s.appendChild(r2);
  
  var r3 = document.createElementNS(SVG_NS, "rect");
  r3.setAttribute("x", 0);
  r3.setAttribute("y", ADJUST_SIZE * 2);
  r3.setAttribute("height", ADJUST_SIZE);
  r3.setAttribute("width", columns * ADJUST_SIZE);
  s.appendChild(r3);
  
  var r4 = document.createElementNS(SVG_NS, "rect");
  r4.setAttribute("x", 0);
  r4.setAttribute("y", ADJUST_SIZE * 3);
  r4.setAttribute("height", ADJUST_SIZE);
  r4.setAttribute("width", columns * ADJUST_SIZE);
  s.appendChild(r4);
  
  var l1 = document.createElementNS(SVG_NS, "line");
  l1.setAttribute("x1", 0);
  l1.setAttribute("y1", 0.1);
  l1.setAttribute("x2", columns * ADJUST_SIZE);
  l1.setAttribute("y2", 0.1);
  s.appendChild(l1);
  
  var l2 = document.createElementNS(SVG_NS, "line");
  l2.setAttribute("x1", 0.05);
  l2.setAttribute("y1", 0);
  l2.setAttribute("x2", 0.05);
  l2.setAttribute("y2", MEASURE_HEIGHT);
  s.appendChild(l2);
  
  var l3 = document.createElementNS(SVG_NS, "line");
  l3.setAttribute("x1", columns * ADJUST_SIZE - 0.05);
  l3.setAttribute("y1", 0);
  l3.setAttribute("x2", columns * ADJUST_SIZE - 0.05);
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
  s.setAttribute("x2", x + columns * ADJUST_SIZE / 2);
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
  $("#shadow").attr('x', 0).attr('y', 0).hide();
  mX = 0;
  mY = 0;
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
    width = BUFF_LFT + BUFF_RHT + columns * SCALE * ARR_HEIGHT;
    $("article > svg").attr("width", width);
    
    // append the measures.
    for (var i = 0; i < songData.measures; i++)
    {
      $("g#svgMeas").append(genMeasure(ADJUST_SIZE, BUFF_TOP + MEASURE_HEIGHT * i, i + 1));
    }
    
    // place the BPM data.
    var bpms = songData.bpms;
    for (var i = 0; i < bpms.length; i++)
    {
      $("#svgSync").append(genText(width - BUFF_RHT + 2 * SCALE,
          BUFF_TOP + bpms[i].beat * ADJUST_SIZE + 2 * SCALE, bpms[i].bpm, 'bpm'));
      $("#svgSync").append(genLine(width / 2,
          BUFF_TOP + bpms[i].beat * ADJUST_SIZE, 'bpm'));
    }
    
    var stps = songData.stps;
    for (var i = 0; i < stps.length; i++)
    {
      $("#svgSync").append(genText(0, BUFF_TOP + stps[i].beat * ADJUST_SIZE + 2 * SCALE,
          stps[i].time, 'stop'));
      $("#svgSync").append(genLine(BUFF_LFT, BUFF_TOP + stps[i].beat * ADJUST_SIZE, 'stop'));
    }
    $("nav *.edit").show();
    $("nav *.choose").hide();
  });
}

function round10(n)
{
  n = Math.round(n);
  while (n % 10)
  {
    n = n + 1;
  }
  return n;
}

function init()
{
  $("nav *.edit").hide();
  $("#notes > rect").hide();
  $("nav *.choose").show();
  $("#stylelist").attr("disabled", "disabled");
  $("article > svg").attr("width", 5 * ADJUST_SIZE + BUFF_LFT + BUFF_RHT);
  $("article > svg").attr("height", MEASURE_HEIGHT * 2 + BUFF_TOP + BUFF_BOT);

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
  
  $("#shadow").attr('width', ADJUST_SIZE).attr('height', ADJUST_SIZE);
  $("#songlist").val('');
  
  /*
   * The various action functions are set here.
   */
  $("article > svg").mouseout(function(){ hideRect(); });
  $("article > svg").mouseover(function(e){
    // convert as required.
    mX = e.pageX;// - $("article > svg").offset().left;
    mY = e.pageY;// - $("article > svg").offset().top;
    
    
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