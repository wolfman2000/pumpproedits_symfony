var isDirty; // has the work changed? Should a prompt for saving take place?
var measures; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var width; // compliment to columns
var height; // compliment to measures
var songID; // the song ID.
var songData; // the song data in JSON format.
var sync; // how much syncing are we dealing with?
var note; // which note are we using right now?
var style; // which style are we playing with? single, double, halfdouble, routine
var player; // Which player are we dealing with for routine steps?
var steps; // How many steps?
var jumps; // How many jumps?
var holds; // How many holds?
var mines; // How many mines?
var trips; // How many trips? (or hands)
var rolls; // How many rolls?
var lifts; // How many lifts?
var fakes; // How many fakes?
var mX; // mouse position at X.
var mY; // mouse position at Y.
const ARR_HEIGHT = 16; // initial arrow heights were 16px.
const SCALE = 3; // scale everything by 2 for now.
const ADJUST_SIZE = ARR_HEIGHT * SCALE; // 
const BEATS_PER_MEASURE = 4; // always 4 beats per measure (for our purposes)
const BEATS_MAX = 192; // LCD of 48 and 64

// These constants may change later, depending on how much spacing is wanted.
const BUFF_TOP = ADJUST_SIZE;
const BUFF_LFT = ADJUST_SIZE;
const BUFF_RHT = ADJUST_SIZE;
const BUFF_BOT = ADJUST_SIZE;

const SVG_NS = "http://www.w3.org/2000/svg"; // required for creating elements.
const SVG_BG = "white"; // background of the SVG element and other key things.

const MEASURE_HEIGHT = ADJUST_SIZE * BEATS_PER_MEASURE; // the height of our measure.

/*
 * Generate the line required. Apply the class if one exists.
 */
function genLine(x1, y1, x2, y2, css)
{
  var l = document.createElementNS(SVG_NS, "line");
  l.setAttribute("x1", x1);
  l.setAttribute("y1", y1);
  l.setAttribute("x2", x2);
  l.setAttribute("y2", y2);
  if (css) { l.setAttribute("class", css); }
  return l;
}

/*
 * Generate the rect required. Apply the class if one exists.
 */
function genRect(x, y, w, h, rx, ry, css)
{
  var r = document.createElementNS(SVG_NS, "rect");
  r.setAttribute("x", x);
  r.setAttribute("y", y);
  r.setAttribute("width", w);
  r.setAttribute("height", h);
  if (rx) { r.setAttribute("rx", rx); }
  if (ry) { r.setAttribute("ry", ry); }
  if (css) { r.setAttribute("class", css); }
  return r;
}

/*
 * Create the base arrow dimenions. It goes inside
 * the SVG.
 */
function genArrow(x, y, css)
{
  var s = document.createElementNS(SVG_NS, "svg");
  s.setAttribute("x", x);
  s.setAttribute("y", y);
  if (css) { s.setAttribute("class", css); }
  
  var g = document.createElementNS(SVG_NS, "g"); // needed for transforms
  g.setAttribute("transform", "");
  s.appendChild(g);
  
  return s;
}

/*
 * Generate the basic arrow.
 * Hopefully I can just rotate this sucker and make it happy.
 */
function genDLArrow(x, y, css)
{
  var s = genArrow(x, y, css);
  
  var p = document.createElementNS(SVG_NS, "path");
  p.setAttribute("d", "m 1,2 v 12 c 0,0 0,1 1,1 h 12 c 0,0 1,0 1,-1 v -1 c 0,0 0,-1 -1,-1 "
      + "H 7 L 15,4 V 2 C 15,2 15,1 14,1 H 12 L 4,9 V 2 C 4,2 4,1 3,1 H 2 C 2,1 1,1 1,2");
  s.firstChild.appendChild(p);
  s.firstChild.appendChild(genLine(14.5, 4.5, 11.5, 1.5));
  s.firstChild.appendChild(genLine(10.75, 8.25, 7.75, 5.25));
  s.firstChild.appendChild(genLine(7, 12, 4, 9));
  
  return s;
}

function genULArrow(x, y, css)
{
  var s = genDLArrow(x, y, css);
  s.firstChild.setAttribute("transform", "rotate(90 " + (ARR_HEIGHT / 2) + " " + (ARR_HEIGHT / 2) + ")");
  return s;
}

/*
 * The center arrow works differently. It relies more on rectangles.
 */
function genCNArrow(x, y, css)
{
  var s = genArrow(x, y, css);
  var p = document.createElementNS(SVG_NS, "path");
  p.setAttribute("d", "m 1,2 v 12 l 1,1 h 12 l 1,-1 V 2 L 14,1 H 2 z");
  s.firstChild.appendChild(p);
  s.firstChild.appendChild(genRect(4, 6, 2, 4, 0.5));
  s.firstChild.appendChild(genRect(10, 6, 2, 4, 0.5));
  return s;
}

function genURArrow(x, y, css)
{
  var s = genDLArrow(x, y, css);
  s.firstChild.setAttribute("transform", "rotate(180 " + (ARR_HEIGHT / 2) + " " + (ARR_HEIGHT / 2) + ")");
  return s;
}

function genDRArrow(x, y, css)
{
  var s = genDLArrow(x, y, css);
  s.firstChild.setAttribute("transform", "rotate(270 " + (ARR_HEIGHT / 2) + " " + (ARR_HEIGHT / 2) + ")");
  return s;
}


/*
 * Determine the proper note class to render based on sync.
 */
function getNote()
{
  var rY = $("#shadow").attr('y');
  var y = (rY - ADJUST_SIZE) % BEATS_MAX;
  
  var k = "note";
  if (style == "r") { k = "p" + player; }
  
  if (!(y % 48)) { return k + "_004" };
  if (!(y % 24)) { return k + "_008" };
  if (!(y % 16)) { return k + "_012" };
  if (!(y % 12)) { return k + "_016" };
  if (!(y % 8))  { return k + "_024" };
  if (!(y % 6))  { return k + "_032" };
  if (!(y % 4))  { return k + "_048" };
  if (!(y % 3))  { return k + "_064" };
  return k + "_192";
}

/*
 * Determine which arrow to return to the user.
 */
function selectArrow(css)
{
  var rX = $("#shadow").attr('x');
  var rY = $("#shadow").attr('y') / SCALE;
  var x = rX / ADJUST_SIZE - 1;
  rX = rX / SCALE;
  if (style == "r") { x = x + 2; }
  switch (x % 5)
  {
    case 0: return genDLArrow(rX, rY, css);
    case 1: return genULArrow(rX, rY, css);
    case 2: return genCNArrow(rX, rY, css);
    case 3: return genURArrow(rX, rY, css);
    case 4: return genDRArrow(rX, rY, css);
  }
}

/*
 * Generate the measures that will hold the arrows.
 */
function genMeasure(x, y, c)
{
  var s = document.createElementNS(SVG_NS, "svg");
  s.setAttribute("x", x);
  s.setAttribute("y", y);
  
  s.appendChild(genRect(0, 0, columns * ADJUST_SIZE, ADJUST_SIZE));
  s.appendChild(genRect(0, ADJUST_SIZE, columns * ADJUST_SIZE, ADJUST_SIZE));
  s.appendChild(genRect(0, ADJUST_SIZE * 2, columns * ADJUST_SIZE, ADJUST_SIZE));
  s.appendChild(genRect(0, ADJUST_SIZE * 3, columns * ADJUST_SIZE, ADJUST_SIZE));
  
  s.appendChild(genText(BEATS_PER_MEASURE, ARR_HEIGHT, "" + c + ")"));
  
  s.appendChild(genLine(0, 0.1, columns * ADJUST_SIZE, 0.1));
  s.appendChild(genLine(0.05, 0, 0.05, MEASURE_HEIGHT));
  var x = columns * ADJUST_SIZE - 0.05;
  s.appendChild(genLine(x, 0, x, MEASURE_HEIGHT));
  
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
}

/*
 * Show where the rectangle gets placed.
 */
function showRect(x, y)
{
  $("#shadow#").attr('x', x).attr('y', y).show();
}

/**
 * Enter this mode upon choosing a song and difficulty.
 */
function editMode()
{
  $.getJSON("/create/song/" + songID, function(data)
  {
    songData = data;
    style = $("#stylelist > option:selected").val();
    height = MEASURE_HEIGHT * songData.measures + BUFF_TOP + BUFF_BOT;
    $("article > svg").attr("height", height);
    $("article").css("height", height + 200);
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
    var x = width / 2;
    var y;
    for (var i = 0; i < bpms.length; i++)
    {
      y = BUFF_TOP + bpms[i].beat * ADJUST_SIZE;
      $("#svgSync").append(genText(width - BUFF_RHT + 2 * SCALE,
          y + 2 * SCALE, bpms[i].bpm, 'bpm'));
      $("#svgSync").append(genLine(x, y, x + columns * ADJUST_SIZE / 2, y, 'bpm'));
    }
    
    var stps = songData.stps;
    for (var i = 0; i < stps.length; i++)
    {
      y = BUFF_TOP + stps[i].beat * ADJUST_SIZE;
      $("#svgSync").append(genText(0, y + 2 * SCALE,
          stps[i].time, 'stop'));
      $("#svgSync").append(genLine(BUFF_LFT, y, BUFF_LFT + columns * ADJUST_SIZE / 2, y, 'stop'));
    }
    $("nav *.edit").show();
    $("nav *.choose").hide();
    if (style != "r") { $("nav .routine").hide(); }
  });
}

/**
 * Round elements to the nearest 10 for easier calculations later.
 */
function round10(n)
{
  n = Math.round(n);
  while (n % 10)
  {
    n = n + 1;
  }
  return n;
}

/**
 * Load up this data on new.
 */
function init()
{
  $("nav *.edit").hide();
  $("#notes > rect").hide();
  $("nav *.choose").show();
  $("#stylelist").attr("disabled", "disabled");
  $("article > svg").css('left', round10($("nav").first().width()) + 70);
  $("article > svg").css('top', round10($("header").first().height()) * 8 + 20);
  $("article").css('height', '50em');
  $("article > svg").attr("width", 5 * ADJUST_SIZE + BUFF_LFT + BUFF_RHT);
  $("article > svg").attr("height", MEASURE_HEIGHT * 2 + BUFF_TOP + BUFF_BOT);

  // reset the drop downs (and corresponding variables) to default values.
  $("#songlist").val('');
  $("#stylelist").val('');
  $("#quanlist").val(4);
  $("#typelist").val(1);
  sync = 4;
  note = 1;
  player = 1;

  $("#svgMeas").empty();
  $("#svgSync").empty();
  
  isDirty = false;
  measures = new Array({}, {}); // routine compatible.
  columns = 5; // reasonable default.
  steps = jumps = holds = mines = trips = rolls = lifts = fakes = 0;
  
  
}

/**
 * Trace the mouse to see where the shadow falls.
 */
function shadow(e)
{
  var pnt = $("#svgMeas > svg:first-child > rect:first-child")
  if (pnt.offset())
  {
    mX = e.pageX - pnt.offset().left;
    mY = e.pageY - pnt.offset().top;
    
    var hnt = $("#svgMeas > svg:last-child");
    if (!(mX < 0 || mX > columns * ADJUST_SIZE || mY < 0 || mY > hnt.attr('y')))
    {
      var nX = 0;
      var nY = 0;
      
      while (nX + ADJUST_SIZE < mX)
      {
        nX += ADJUST_SIZE;
      }
      
      nY = MEASURE_HEIGHT * Math.floor(mY / MEASURE_HEIGHT);
      var rY = mY % MEASURE_HEIGHT;
      
      var sY = BEATS_MAX / sync;
      while (nY + sY < mY)
      {
        nY += sY;
      }
      showRect(nX + ADJUST_SIZE, nY + ADJUST_SIZE);
    }
    else
    {
      hideRect(); // Best to be safe and explicit.
    }
  }
}

/**
 * Add the arrow in the appropriate position.
 */
function changeArrow()
{
  var r = $("#shadow");
  if (!(r.attr('x') && r.attr('y'))) return;
  
  // see if a node exists in this area.
  
  // TODO
  
  // add if empty
  
  var collection = $("#svgNote");
  for (var n in collection.children())
  {
    ;
  }
  
  /*
   * If it hits here, then this is the last note to add.
   * A simple create and append will do.
   */
  
  collection.append(selectArrow(getNote()));
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
  $("article > svg").mouseover(function(e){ shadow(e); });
  
  $("article > svg").mousemove(function(e){ shadow(e); });
  
  $("article > svg").click(function(){ changeArrow(); });
  
  $('#songlist').change(function(){
    songID = $("#songlist > option:selected").val();
    if (songID.length > 0) { $("#stylelist").removeAttr("disabled"); }
    else { $("#stylelist").attr("disabled", "disabled"); }
  });
  $("#stylelist").change(function(){ editMode(); });
  
  $("#quanlist").change(function() { sync = $("#quanlist > option:selected").val();});
  $("#typelist").change(function() { note = $("#typelist > option:selected").val();});
  
  $("#p1").change(function() { player = 1; });
  $("#p2").change(function() { player = 2; });
  
});