/*
 * Add a capitalize function for the first letter.
 */
String.prototype.capitalize = function(){
   return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
  };

/*
 * Hide the shadow rectangle from others.
 */
function hideRect()
{
  $("#shadow").attr('x', 0).attr('y', 0).hide();
  $("#yCheck").text("???");
  $("#mCheck").text("???");
}

/*
 * Show where the rectangle gets placed.
 */
function showRect(x, y)
{
  $("#shadow#").attr('x', x).attr('y', y).show();
  y = y - ADJUST_SIZE;
  $("#mCheck").text(Math.floor(y / BEATS_MAX) + 1);
  $("#yCheck").text(y % BEATS_MAX);
}

/**
 * Enter this mode upon choosing a song and difficulty.
 */
function editMode()
{
  $("#intro").text("Loading... Loading...");
  $.getJSON("/create/song/" + songID, function(data)
  {
    style = $("#stylelist").val();
    /*
     * Retrieve the number of columns we'll be using today.
     */
    function getCols()
    {
      switch (style.substring(0, 1))
      {
        case "s": return 5;
        case "h": return 6;
        case "d": case "r": return 10;
        default: return 0; // I wonder if an exception should be thrown here.
      }
    }
    songData = data;
    height = MEASURE_HEIGHT * songData.measures + BUFF_TOP + BUFF_BOT;
    $("#svg").attr("height", height);
    $("article").css("height", height + 200);
    columns = getCols();
    width = BUFF_LFT + BUFF_RHT + columns * SCALE * ARR_HEIGHT;
    $("#svg").attr("width", width);
    
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
      $("#svgSync").append(genText(0, y + 2 * SCALE, stps[i].time, 'stop'));
      $("#svgSync").append(genLine(BUFF_LFT, y, BUFF_LFT + columns * ADJUST_SIZE / 2, y, 'stop'));
    }
    $("nav *.edit").show();
    $("nav *.choose").hide();
    if (style != "routine") { $("nav .routine").hide(); }
    $("h2").first().text(songData.name + " " + style.capitalize());
    $("#but_new").removeAttr('disabled');
    if (authed == "in") { $("#but_sub").removeAttr('disabled'); }
    $("#intro").text("Have fun editing!");
  });
}

/**
 * Load up this data on new.
 */
function init()
{
  $("nav *.edit").hide();
  $("#notes > rect").hide();
  $("nav *.choose").show();
  $("#stylelist").attr("disabled", true);
  $("#but_sub").attr("disabled", true);
  $("#but_save").attr("disabled", true);
  $("#but_val").attr("disabled", true);
  $("#but_new").attr("disabled", true);
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
  $("#svg").css('left', round10($("nav").first().width()) + 70);
  $("#svg").css('top', round10($("header").first().height()) * 8 + 20);
  $("article").css('height', '50em');
  $("#svg").attr("width", 5 * ADJUST_SIZE + BUFF_LFT + BUFF_RHT);
  $("#svg").attr("height", MEASURE_HEIGHT * 2 + BUFF_TOP + BUFF_BOT);

  // reset the drop downs (and corresponding variables) to default values.
  $("#songlist").val('');
  $("#stylelist").val('');
  $("#quanlist").val(4);
  $("#typelist").val(1);
  $("#editName").val('');
  $("#editDiff").val('');
  sync = 4;
  note = "1";
  player = 0;
  title = "";
  diff = 0;

  $("#svgMeas").empty();
  $("#svgSync").empty();
  $("#svgNote").empty();
  
  $("#intro").text("Select your action.");
  
  isDirty = false;
  notes = new Array({}, {}); // routine compatible.
  columns = 5; // reasonable default.
  steps = new Array();
  jumps = new Array();
  holds = new Array();
  mines = new Array();
  trips = new Array();
  rolls = new Array();
  lifts = new Array();
  fakes = new Array();
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
  var rX = r.attr('x');
  var rY = r.attr('y');
  if (!(rX && rY)) return;
  
  isDirty = true;
  $("#but_save").removeAttr('disabled');
  /*
   * Determine the proper note classes to render based on sync.
   */
  function getNote()
  {
    var y = (rY - ADJUST_SIZE) % BEATS_MAX;
    var k = "note";
    if (style == "routine") { k = "p" + player; }
    
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

  var css = getNote();
  var cX = rX / ADJUST_SIZE - 1; // which column are we using?
  var mY = Math.floor((rY - ADJUST_SIZE) / BEATS_MAX); // which measure? (0'th based)
  var bY = (rY - ADJUST_SIZE) % BEATS_MAX; // which beat? (0'th based)
  
  function defineNote()
  {
    if (notes[player][mY] == null)
    {
      notes[player][mY] = {};
    }
    if (notes[player][mY][bY] == null)
    {
      notes[player][mY][bY] = {};
    }
  }
  defineNote(); // unsure if this needs to be a function.
  
  rX /= SCALE;
  rY /= SCALE;
  
  /*
   * Determine which arrow to return to the user.
   */
  function selectArrow()
  {
    // Take care of the special shaped arrows first.
    if (css.indexOf("mine") >= 0) { return genMine(rX, rY, css); }
    if (css.indexOf("end")  >= 0) { return  genEnd(rX, rY, css); }
    if (css.indexOf("fake") >= 0) { return genFake(rX, rY, css); }
    
    switch ((style == "halfdouble" ? cX + 2 : cX) % 5)
    {
      case 0: return genDLArrow(rX, rY, css);
      case 1: return genULArrow(rX, rY, css);
      case 2: return genCNArrow(rX, rY, css);
      case 3: return genURArrow(rX, rY, css);
      case 4: return genDRArrow(rX, rY, css);
    }
  }
  
  // see if a node exists in this area.
  
  /*
   * Mini rant time. SVG and jQuery don't fully get
   * along right now. Until such a time comes when
   * they do, I have to loop through each element
   * manually until I hit it, and then figure out
   * the rest from there.
   */

  var coll = $("#svgNote");
  
  var n = coll.children().first();
  var nX = n.attr('x');
  var nY = n.attr('y');
  
  if (nX == rX && nY == rY)
  {
    var nStyle = n.attr('class');
    nStyle = nStyle.substring(nStyle.charAt(' '));
    n.remove();
    if (nStyle == css.substring(css.charAt(' ')))
    {
      return; // No point in adding the same note type again.
    }
  }
  else while (n.length)
  {
    n = n.next();
    nX = n.attr('x');
    nY = n.attr('y');
    
    if (nX == rX && nY == rY)
    {
      var nStyle = n.attr('class');
      nStyle = nStyle.substring(nStyle.charAt(' '));
      n.remove();
      if (nStyle == css.substring(css.charAt(' ')))
      {
        notes[player][mY][bY][cX] = '0';
        return; // No point in adding the same note type again.
      }
      break; // replacing with a new note: start below.
    }
  }
  
  // add if empty
  
  notes[player][mY][bY][cX] = note;
  
  n = coll.children().first();
  nX = n.attr('x');
  nY = n.attr('y');
  
  if (nY > rY || nY == rY && nX > rX)
  {
    n.before(selectArrow());
    return;
  }
  
  while (n.length)
  {
    n = n.next();
    nX = n.attr('x');
    nY = n.attr('y');
    
    if (nY > rY || nY == rY && nX > rX)
    {
      n.before(selectArrow());
      return;
    }
  }
  
  /*
   * If it hits here, then this is the last note to add.
   * A simple create and append will do.
   */
  
  coll.append(selectArrow());
}



/*
 * Load all of the following when the page is done loading.
 */
$(document).ready(function()
{
  init();
  
  $("#shadow").attr('width', ADJUST_SIZE).attr('height', ADJUST_SIZE);
  $("#songlist").val('');
  
  /*
   * The various action functions are set here.
   */
  $("#svg").mouseout(function(){ hideRect(); });
  $("#svg").mouseover(function(e){ shadow(e); });
  $("#svg").mousemove(function(e){ shadow(e); });
  $("#svg").click(function(){ changeArrow(); });
  
  $("#but_new").click(function(){
    $("#intro").text("Working... Working...");
    if (isDirty) // implement later.
    {
      
    }
    init();
  });
  
  $("#but_load").click(function(){
    if (isDirty) // implement later.
    {
    
    }
    alert("This function is not yet available.");
  });
  
  $("#but_help").click(function(){
    alert("This function is not yet available.");
  });
  
  $("#but_val").click(function(){
    alert("This function is not yet available.");
  });
  
  $("#but_save").click(function(){
    if (isDirty) // implement later.
    {
      alert("Saving doesn't work yet. The dirty variable is clean in the meantime.");
      isDirty = false;
      $("#but_save").attr('disabled', true);
    }
    else
    {
      alert("Saving doesn't work yet. The dirty variable...wasn't dirty.");
    }
  });
  
  $("#but_sub").click(function(){
    alert("This function is not yet available.");
  });
  
  $('#songlist').change(function(){
    songID = $("#songlist").val();
    if (songID.length > 0) { $("#stylelist").removeAttr("disabled"); }
    else { $("#stylelist").attr("disabled", "disabled"); }
  });
  $("#stylelist").change(function(){ editMode(); });
  
  $("#quanlist").change(function() { sync = $("#quanlist").val();});
  $("#typelist").change(function() { note = $("#typelist").val();});
  
  $("#editName").change(function(){
    var t = $("#editName").val();
    if (t.length > 0 && t.length <= 12)
    {
      title = t;
    }
  });
  $("#editDiff").change(function(){
    var t = parseInt($("#editDiff").val());
    if (t > 0 && t < 100)
    {
      diff = t;
    }
  });
  
  $("#p1").change(function() { player = 0; });
  $("#p2").change(function() { player = 1; });
  
});
