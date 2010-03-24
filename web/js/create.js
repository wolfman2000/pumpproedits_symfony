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

const SVG_BG = "white"; // background of the SVG element and other key things.

/*
 * Determine the proper note classes to render based on sync.
 */
function getNote()
{
  var rY = $("#shadow").attr('y');
  var y = (rY - ADJUST_SIZE) % BEATS_MAX;
  
  var k = "note";
  if (style == "r") { k = "p" + player; }
  
  if      (!(y % 48)) { k += "_004"; }
  else if (!(y % 24)) { k += "_008"; }
  else if (!(y % 16)) { k += "_012"; }
  else if (!(y % 12)) { k += "_016"; }
  else if (!(y % 8))  { k += "_024"; }
  else if (!(y % 6))  { k += "_032"; }
  else if (!(y % 4))  { k += "_048"; }
  else if (!(y % 3))  { k += "_064"; }
  else                { k += "_192"; }
  
  var t; // note type.
  if      (note == "1") { t = "tap";  }
  else if (note == "2") { t = "hold"; }
  else if (note == "3") { t = "end";  }
  else if (note == "4") { t = "roll"; }
  else if (note == "M") { t = "mine"; }
  else if (note == "L") { t = "lift"; }
  else if (note == "F") { t = "fake"; }
  else                  { t = "FIX";  }
  return k + " " + t;
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
  if (style == "h") { x = x + 2; }
  
  if (css.indexOf("mine") >= 0) { return genMine(rX, rY, css); }
  if (css.indexOf("end")  >= 0) { return genEnd(rX, rY, css);  }
  
  
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
  note = "1";
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