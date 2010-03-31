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
  $("#shadow#").attr('x', x).attr('y', y + BUFF_TOP).show();
  $("#mCheck").text(Math.floor(y / BEATS_MAX * MEASURE_RATIO) + 1);
  $("#yCheck").text(Math.round(y * MEASURE_RATIO) % BEATS_MAX);
}

/**
 * Trace the mouse to see where the shadow falls.
 */
function shadow(e)
{
  var pnt = $("#svgMeas > svg:first-child > rect:first-child")
  if (pnt.offset())
  {
    // Use WebKit hack for now.
    if (navigator.userAgent.indexOf("WebKit") >= 0)
    {
      mX = Math.floor(e.pageX - $("#svg").offset().left - BUFF_LFT);
      mY = Math.floor(e.pageY - $("#svg").offset().top - BUFF_TOP);
    }
    else
    {
      mX = e.pageX - pnt.offset().left;
      mY = e.pageY - pnt.offset().top;
    }
    var localY = mY;
    var maxY = Math.floor($("#svgMeas > svg:last-child").attr('y')) + 3 * ARR_HEIGHT;
    if (!(mX < 0 || mX > columns * ADJUST_SIZE || localY < 0 || localY > SCALE * maxY))
    {
      var nX = 0;
      var nY = 0;
      
      while (nX + ADJUST_SIZE < mX)
      {
        nX += ADJUST_SIZE;
      }
      nX = nX / SCALE;      

      var scaledM = ARR_HEIGHT * SCALE * BEATS_PER_MEASURE;
      var wholeM = Math.floor(localY / scaledM);
      var beatM = localY % scaledM;

      var sY = BEATS_MAX / sync / MEASURE_RATIO * SCALE; // get the current note.

      while (nY + sY < beatM)
      {
        nY += sY;
      }
      nY = wholeM * scaledM + nY;
      nY = nY / SCALE;
      showRect(nX + BUFF_LFT, nY);
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
  isDirty = true;
  $("#but_val").attr('disabled', true);

  var bY = $("#yCheck").text() // which beat? (0'th based);
  var css = getNote(bY);
  var cX = (rX - BUFF_LFT) / ARR_HEIGHT; // which column are we using?
  var mY = $("#mCheck").text() - 1; // which measure? (0'th based)
  
  function defineNote()
  {
    if (notes[player][mY] == null)
    {
      notes[player][mY] = Array();
    }
    if (notes[player][mY][bY] == null)
    {
      notes[player][mY][bY] = Array();
    }
  }
  defineNote(); // unsure if this needs to be a function.
  
  //rX /= SCALE;
  //rY /= SCALE;
  
  // Remove empty rows as required.
  function prune()
  {
    delete(notes[player][mY][bY][cX]);
    if (isEmpty(notes[player][mY][bY]))
    {
      delete(notes[player][mY][bY]);
      if (isEmpty(notes[player][mY]))
      {
        delete(notes[player][mY][bY]);
        if (isEmpty(notes[player][mY]))
        {
          delete(notes[player][mY]);
        }
      }
    } 
  }
  
  // see if a node exists in this area.
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
      prune();
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
        prune();
        return; // No point in adding the same note type again.
      }
      break; // replacing with a new note: start below.
    }
  }
  
  // add if empty
  var sA = selectArrow(cX, rX, rY, css);
  notes[player][mY][bY][cX] = note;
  
  n = coll.children().first();
  nX = n.attr('x');
  nY = n.attr('y');
  
  if (nY > rY || nY == rY && nX > rX)
  {
    n.before(sA);
    return;
  }
  // insert the note somewhere in the middle.
  while (n.length)
  {
    n = n.next();
    nX = n.attr('x');
    nY = n.attr('y');
    
    if (nY > rY || nY == rY && nX > rX)
    {
      n.before(sA);
      return;
    }
  }
  // last note in the line: simple.
  coll.append(sA);
}

/*
 * Determine the proper note classes to render based on sync.
 */
function getNote(y, nt, pl)
{
  var k = "note";
  if (pl == null) { pl = player; }
  if (style == "routine") { k = "p" + player + " " + k; }
  
  if      (!(y % 48)) { k += "_004"; }
  else if (!(y % 24)) { k += "_008"; }
  else if (!(y % 16)) { k += "_012"; }
  else if (!(y % 12)) { k += "_016"; }
  else if (!(y % 8))  { k += "_024"; }
  else if (!(y % 6))  { k += "_032"; }
  else if (!(y % 4))  { k += "_048"; }
  else if (!(y % 3))  { k += "_064"; }
  else                { k += "_192"; }
  
  if (nt == null) { nt = note; }
  var t; // note type.
  if      (nt == "1") { t = "tap";  }
  else if (nt == "2") { t = "hold"; }
  else if (nt == "3") { t = "end";  }
  else if (nt == "4") { t = "roll"; }
  else if (nt == "M") { t = "mine"; }
  else if (nt == "L") { t = "lift"; }
  else if (nt == "F") { t = "fake"; }
  else                { t = "FIX";  }
  return k + " " + t;
}

/*
 * Determine which arrow to return to the user.
 */
function selectArrow(cX, rX, rY, css)
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

/*
 * This is meant to be an asyncronous function
 * to get the step stats as close to live as
 * possible without tying up the browser.
 */
function updateStats()
{
  var S = steps[0];
  var J = jumps[0];
  var H = holds[0];
  var M = mines[0];
  var T = trips[0];
  var R = rolls[0];
  var L = lifts[0];
  var F = fakes[0];
  if (style == "routine")
  {
    S += "/" + steps[1];
    J += "/" + jumps[1];
    H += "/" + holds[1];
    M += "/" + mines[1];
    T += "/" + trips[1];
    R += "/" + rolls[1];
    L += "/" + lifts[1];
    F += "/" + fakes[1];
  }
  $("#statS").text(S);
  $("#statJ").text(J);
  $("#statH").text(H);
  $("#statM").text(M);
  $("#statT").text(T);
  $("#statR").text(R);
  $("#statL").text(L);
  $("#statF").text(F);

  $("#but_save").attr('disabled', true);
  $("#but_sub").attr('disabled', true);
  if (title && diff > 0)
  {
    if (steps[0] || steps[1] || mines[0] || mines[1] || lifts[0] || lifts[1] || fakes[0] || fakes[1])
    {
      $("#but_val").removeAttr('disabled');
      $("#intro").text("Validate your edit before saving.");
    }
    else
    {
      isDirty = false;
      $("#intro").text("You can't save empty files.");
    }
  }
  else
  {
    $("#intro").text("Provide an edit title and difficulty.");
  }
}

/**
 * Enter this mode upon choosing a song and difficulty.
 */
function editMode()
{
  $("#intro").text("Loading song data...");
  $.ajax({ async: false, dataType: 'json', url: baseURL + '/song/' + songID, success: function(data)
  {
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
    height = SCALE * (ARR_HEIGHT * BEATS_PER_MEASURE * songData.measures + BUFF_TOP + BUFF_BOT);
    $("#svg").attr("height", height);
    $("article").css("height", height + 200);
    columns = getCols();
    width = SCALE * ((BUFF_LFT + BUFF_RHT) + columns * ARR_HEIGHT);
    $("#svg").attr("width", width);
    
    // append the measures.
    for (var i = 0; i < songData.measures; i++)
    {
      $("g#svgMeas").append(genMeasure(BUFF_LFT, BUFF_TOP + ARR_HEIGHT * BEATS_PER_MEASURE * i, i + 1));
    }
    
    // place the BPM data.
    var bpms = songData.bpms;
    var x = width / 2 / SCALE;
    var y;
    for (var i = 0; i < bpms.length; i++)
    {
      y = BUFF_TOP + bpms[i].beat * ARR_HEIGHT;
      $("#svgSync").append(genText(BUFF_LFT + columns * ARR_HEIGHT + 2 * SCALE,
          y + SCALE, bpms[i].bpm, 'bpm'));
      $("#svgSync").append(genLine(x, y, x + columns * ARR_HEIGHT / 2, y, 'bpm'));
    }
    
    var stps = songData.stps;
    for (var i = 0; i < stps.length; i++)
    {
      y = BUFF_TOP + stps[i].beat * ARR_HEIGHT;
      $("#svgSync").append(genText(SCALE * 3, y + SCALE, stps[i].time, 'stop'));
      $("#svgSync").append(genLine(BUFF_LFT, y, BUFF_LFT + columns * ARR_HEIGHT / 2, y, 'stop'));
    }
    $("nav dt.edit").show();
    $("nav dd.edit").show();
    $("nav *.choose").hide();
    if (style != "routine") { $("nav .routine").hide(); }
    var phrase = songData.name + " " + style.capitalize();
    $("h2").first().text(phrase);
    $("title").text("Editing " + phrase + " — Pump Pro Edits");
    $("#but_new").removeAttr('disabled');
    $("#editName").removeAttr('disabled');
    return true;
  }});
  return false; // this is to ensure the asyncing is done right.
}


/**
 * Load up this data on new.
 */
function init()
{
  fixScale(2);
  $("title").text("Edit Creator — Pump Pro Edits");
  $("h2").first().text("Edit Creator");
  
  $("nav dt.edit").hide();
  $("nav dd.edit").hide();
  $("nav li.loadChoose").hide();
  $("nav li.loadSite").hide();
  $("nav li.loadFile").hide();
  $("#notes > rect").hide();
  $("nav *.choose").show();
  $("#stylelist").attr("disabled", true);
  $("#but_sub").attr("disabled", true);
  $("#but_save").attr("disabled", true);
  $("#but_val").attr("disabled", true);
  $("#but_new").attr("disabled", true);
  $("#cho_file").removeAttr('disabled');
  if (authed > 0)
  {
    $("#cho_site").removeAttr('disabled');
  }
  else
  {
    $("#cho_site").attr('disabled', true);
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
  $("#svg").css('left', round10($("nav").first().width()) + 70);
  $("#svg").css('top', round10($("header").first().height()) * 8 + 20);
  $("article").css('height', '50em');
  $("#svg").attr("width", 5 * ARR_HEIGHT * SCALE + BUFF_LFT + BUFF_RHT);
  $("#svg").attr("height", ADJUST_SIZE * BEATS_PER_MEASURE * 2 + BUFF_TOP + BUFF_BOT);

  // reset the drop downs (and corresponding variables) to default values.
  $("#songlist").val('');
  $("#stylelist").val('');
  $("#quanlist").val(4);
  $("#typelist").val(1);
  $("#editName").val('');
  $("#editDiff").val('');
  sync = 4;
  note = "1";
  $("#p1").click();
  player = 0;
  title = "";
  diff = 0;
  editID = 0;

  $("#svgMeas").empty();
  $("#svgSync").empty();
  $("#svgNote").empty();
  
  $("#intro").text("Select your action.");
  
  isDirty = false;
  notes = new Array(Array(), Array()); // routine compatible.
  columns = 5; // reasonable default.
  steps = new Array(0, 0);
  jumps = new Array(0, 0);
  holds = new Array(0, 0);
  mines = new Array(0, 0);
  trips = new Array(0, 0);
  rolls = new Array(0, 0);
  lifts = new Array(0, 0);
  fakes = new Array(0, 0);
  badds = new Array();
}

// Dynamically adjust the scale as needed.
function fixScale(num)
{
  SCALE = num;
  ADJUST_SIZE = ARR_HEIGHT * SCALE;
  MEASURE_HEIGHT = ADJUST_SIZE * BEATS_PER_MEASURE;
  
}

