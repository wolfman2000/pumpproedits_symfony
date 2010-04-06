/*
 * This file deals with all of the functions directly
 * called by the different XHTML elements.
 */
// Hide the rectangle when not in use.
function hideRect()
{
  $("#shadow").attr('x', 0).attr('y', 0).hide();
  $("#yCheck").text("???");
  $("#mCheck").text("???");
}

// Determine if a shadow square can be shown.
function checkShadow(e)
{
  var pnt = $("#measureNum1");
  if (pnt.offset()) { shadow(e, pnt); }
}

// Trace the mouse to see where the shadow falls.
function shadow(e, pnt)
{
  var mX = -1000;
  var mY = -1000;

  // Use WebKit hack for now.
  if (navigator.userAgent.indexOf("WebKit") >= 0)
  {
    var curleft = curtop = 0;
    pnt = pnt[0]; // force HTML mode.
    do
    {
      curleft += pnt.offsetLeft;
      curtop += pnt.offsetTop;
    } while (pnt = pnt.offsetParent);
  
    var eX = e.pageX;
    var eY = e.pageY;
    mX = Math.floor(eX - curleft - BUFF_LFT * SCALE);
    mY = Math.floor(eY - curtop - BUFF_TOP * SCALE);
  }
  else
  {
    mX = e.pageX - pnt.offset().left;
    mY = e.pageY - pnt.offset().top;
  }
  var maxY = Math.floor($("#svgMeas > svg:last-child").attr('y')) + 3 * ARR_HEIGHT;
  var maxX = columns * ADJUST_SIZE;
  if (!(mX < 0 || mX > maxX || mY < 0 || mY > SCALE * maxY))
  {
    var nX = 0;
    var nY = 0;
    
    while (nX + ADJUST_SIZE < mX)
    {
      nX += ADJUST_SIZE;
    }
    nX = nX / SCALE;

    var scaledM = ARR_HEIGHT * SCALE * BEATS_PER_MEASURE;
    var wholeM = Math.floor(mY / scaledM);
    var beatM = mY % scaledM;

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

// Add the arrow in the appropriate position.
function changeArrow()
{
  var r = $("#shadow");
  var rX = parseInt(r.attr('x'));
  var rY = parseFloat(r.attr('y'));
  isDirty = true;
  $("#but_val").attr('disabled', true);

  var css = getNote($("#yCheck").text()); // get the class based on the beat.
  var cX = (rX - BUFF_LFT) / ARR_HEIGHT; // which column are we using?
  
  // see if a node exists in this area.
  var coll = $("#svgNote");
  
  // add if empty
  var sA = selectArrow(cX, rX, rY, css);
  var fin = false;
  
  coll.children().each(function(ind){
    if (fin) { return; }
    var nX = parseInt($(this).attr('x'));
    var nY = parseFloat($(this).attr('y'));
    
    if (nX == rX && nY == rY) // exact same: remove old
    {
      var nStyle = $(this).attr('class');
      $("#svgNote > svg:eq(" + ind + ")").remove();
       // No point in adding the same note type again.
      if (nStyle !== css)
      {
        if ($("#svgNote").children().length === 0) { coll.append(sA); }
        else { $("#svgNote > svg:eq(" + (ind - 1) + ")").after(sA); }
      }
      fin = true;
    }
    else if (nY > rY || nY == rY && nX > rX)
    {
      $(this).before(sA);
      fin = true;
    }
  });
  if (!fin) { coll.append(sA); }
}

// Place the selection row as required.
function selectRow()
{
  var rY = parseFloat($("#shadow").attr('y'));
  if ($("#selBot").attr('style').indexOf('none') == -1)
  {
    $("rect[id^=sel]").hide();
  }
  if ($("#selTop").attr('style').indexOf('none') != -1)
  {
    $("#selTop").attr('y', rY).show();
    $("#intro").text("Select the second row, or transform the data now.");
  }
  else
  {
    $("#selBot").attr('y', rY).show();
    $("#intro").text("Transform the rows with the keyboard, or start again.");
    
    if (rY < parseFloat($("#selTop").attr('y')))
    {
      $("#selBot").attr('y', $("#selTop").attr('y'));
      $("#selTop").attr('y', rY);
    }
  }
}

// Determine which player class to retrieve.
function getPlayer(pl)
{
  if (style === "routine") { return "p" + player; }
  return "pS";
}

// Determine the player number based on the player class.
function getPlayerByClass(jQ)
{
  if (style !== "routine") { return 0; } // doesn't matter here.
  if (jQ.indexOf("p0") >= 0) { return 0; }
  if (jQ.indexOf("p1") >= 0) { return 1; }
  return 0; // default.
}

// Determine which synced note is needed.
function getSync(y)
{
  var k = "note";
  if      (!(y % 48)) { k += "_004"; }
  else if (!(y % 24)) { k += "_008"; }
  else if (!(y % 16)) { k += "_012"; }
  else if (!(y % 12)) { k += "_016"; }
  else if (!(y % 8))  { k += "_024"; }
  else if (!(y % 6))  { k += "_032"; }
  else if (!(y % 4))  { k += "_048"; }
  else if (!(y % 3))  { k += "_064"; }
  else                { k += "_192"; }
  return k;
}

// Determine which note type is requested.
function getType(nt)
{
  if (nt == null) { nt = note; }
  var t = "FIX"; // note type.
  if      (nt == "1") { t = "tap";  }
  else if (nt == "2") { t = "hold"; }
  else if (nt == "3") { t = "end";  }
  else if (nt == "4") { t = "roll"; }
  else if (nt == "M") { t = "mine"; }
  else if (nt == "L") { t = "lift"; }
  else if (nt == "F") { t = "fake"; }
  return t;
}

// Determine the note type based on the class.
function getTypeByClass(jQ)
{
  if (jQ.indexOf("tap") >= 0)  { return "1"; }
  if (jQ.indexOf("hold") >= 0) { return "2"; }
  if (jQ.indexOf("end") >= 0)  { return "3"; }
  if (jQ.indexOf("roll") >= 0) { return "4"; }
  if (jQ.indexOf("mine") >= 0) { return "M"; }
  if (jQ.indexOf("lift") >= 0) { return "L"; }
  if (jQ.indexOf("fake") >= 0) { return "F"; }
  return "X"; // this should never happen.
}

// Determine the proper note classes to render based on sync.
function getNote(y, nt, pl)
{
  return getPlayer(pl) + " " + getSync(y) + " " + getType(nt);
}

// Determine which arrow to return to the user.
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

// Display the updated stats. Should this become asynchronous?
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
//Enter this mode upon choosing a song and difficulty.
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
    measures = songData.measures;
    $("#scalelist").val(2.5);
    captured = false;
    columns = getCols();
    $("rect[id^=sel]").attr('width', columns * ARR_HEIGHT).hide();
    fixScale(2.5);
    
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
    if (style !== "routine") { $("nav .routine").hide(); }
    else { $("nav .routine").show(); }
    var phrase = songData.name + " " + style.capitalize();
    $("h2").first().text(phrase);
    $("title").text("Editing " + phrase + " — Pump Pro Edits");
    $("#but_new").removeAttr('disabled');
    $("#editName").removeAttr('disabled');
    
    if (!authed)
    {
      $(".author").hide();
    }
    else
    {
      $(".author").show();
      $("#authorlist").val(0);
      authID = authed;
    }
    
    return true;
  }});
  return false; // this is to ensure the asyncing is done right.
}


// Load up this data on new.
function init()
{
  captured = false;
  measures = 4; // temp variable.
  columns = 5; // reasonable default.
  fixScale(2);
  $("title").text("Edit Creator — Pump Pro Edits");
  $("h2").first().text("Edit Creator");
  
  $("nav dt.edit").hide();
  $("nav dd.edit").hide();
  $("nav li.loadChoose").hide();
  $("nav li.loadWeb").hide();
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
  $("#scalelist").val(2.5);
  $("#quanlist").val(4);
  $("#typelist").val(1);
  $("#playerlist").val(0);
  $("#modelist").val(0);
  $("#editName").val('');
  $("#editDiff").val('');
  sync = 4;
  note = "1";
  $("#p1").click();
  player = 0;
  title = "";
  diff = 0;
  editID = 0;
  selMode = 0;

  $("#svgMeas").empty();
  $("#svgSync").empty();
  $("#svgNote").empty();
  
  $("#intro").text("Select your action.");
  
  isDirty = false;
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
  height = SCALE * (ARR_HEIGHT * BEATS_PER_MEASURE * measures + BUFF_TOP + BUFF_BOT);
  $("#svg").attr("height", height);
  width = SCALE * ((BUFF_LFT + BUFF_RHT) + columns * ARR_HEIGHT);
  $("#svg").attr("width", width);
  $("#notes").attr("transform", "scale(" + SCALE + ")");
  $("article").css("height", height + 200);
}

// Swap the cursor mode as required.
function swapCursor()
{
  if (selMode == 0)
  {
    $("#intro").text("Resume placing arrows.");
    $("#selTop").hide();
    $("#selBot").hide();
  }
    else
  {
    $("#intro").text("Select the first row.");
  }
}

// Shift the selected arrows up based on the note sync.
function shiftUp()
{
  // remove all notes that are in the way of the shifting operation.
  function removeUp(top, bot)
  {
    if (top > bot)
    {
      var tmp = top;
      top = bot;
      bot = tmp;
    }
    $("#svgNote > svg").filter(function(index){
      var y = $(this).attr('y');
      return y >= top && y < bot;
    }).remove();
  }
  
  var val = Math.floor(-sync);
  var notes = getSelectedArrows();
  var oY = parseFloat($("#selTop").attr('y'));
  var gap = BEATS_MAX / val / MEASURE_RATIO;
  var nY = oY + gap;
  removeUp(oY, nY);
  var tY = parseFloat($("#selTop").attr('y'));
  if (tY > BUFF_TOP)
  {
    var gY = tY + gap;
    $("#selTop").attr('y', (gY < BUFF_TOP ? BUFF_TOP : gY));
  }
  tY = parseFloat($("#selBot").attr('y'));
  if (tY > BUFF_TOP)
  {
    var gY = tY + gap;
    $("#selBot").attr('y', (gY < BUFF_TOP ? BUFF_TOP : gY));
  }
  for (var i = 0; i < notes.length; i++)
  {
    var csses = notes[i].getAttribute('class').split(' ');
    var lOY = parseFloat(notes[i].getAttribute('y'));
    var nOY = lOY + gap;
    notes[i].setAttribute('y', nOY);
    nOY -= BUFF_TOP;
    
    var beatM = Math.round((nOY % (ARR_HEIGHT * SCALE * BEATS_PER_MEASURE)) * MEASURE_RATIO);
    
    notes[i].setAttribute('class', csses[0] + " " + getSync(beatM) + " " + csses[2]);
    
  }
  removeUp(0, BUFF_TOP);
}
// Shift the selected arrows down based on the note sync.
function shiftDown()
{
  // remove all notes that are in the way of the shifting operation.
  function removeDown(top, bot)
  {
    if (top > bot)
    {
      var tmp = top;
      top = bot;
      bot = tmp;
    }
    $("#svgNote > svg").filter(function(index){
      var y = $(this).attr('y');
      return y > top && y <= bot;
    }).remove();
  }
  
  var val = Math.floor(sync);
  var notes = getSelectedArrows();
  var oY = parseFloat($("#selBot").attr('y'));
  var gap = BEATS_MAX / val / MEASURE_RATIO;
  var nY = oY + gap;
  removeDown(oY, nY);
  var sH = Math.floor($("#svg").attr('height')) / SCALE;
  var mB = sH - BUFF_BOT;
  var tY = parseFloat($("#selTop").attr('y'));
  if (tY < mB)
  {
    var gY = tY + gap;
    $("#selTop").attr('y', (gY > mB ? mB : gY));
  }
  tY = parseFloat($("#selBot").attr('y'));
  if (tY < mB)
  {
    var gY = tY + gap;
    $("#selBot").attr('y', (gY > mB ? mB : gY));
  }
  for (var i = 0; i < notes.length; i++)
  {
    var csses = notes[i].getAttribute('class').split(' ');
    var lOY = parseFloat(notes[i].getAttribute('y'));
    var nOY = lOY + gap;
    notes[i].setAttribute('y', nOY);
    nOY += BUFF_BOT;
    
    var beatM = Math.round((nOY % (ARR_HEIGHT * SCALE * BEATS_PER_MEASURE)) * MEASURE_RATIO);
    
    notes[i].setAttribute('class', csses[0] + " " + getSync(beatM) + " " + csses[2]);
    
  }
  removeDown(mB, sH);
}

// Cycle the arrows horizontally, changing arrow orientation as needed.
function rotateColumn(val)
{
  if (!val || val < 0) { val = -ARR_HEIGHT; } else { val = ARR_HEIGHT; }
  var notes = getSelectedArrows();
  
  notes.each(function(ind){
    var x = Math.floor($(this).attr('x')) + val;
    if (x < BUFF_LFT)                              { x += columns * ARR_HEIGHT; }
    else if (x >= BUFF_LFT + columns * ARR_HEIGHT) { x -= columns * ARR_HEIGHT; }
    
    var c = (x - BUFF_LFT) / ARR_HEIGHT;
    var y = Math.floor($(this).attr('y'));
    var a = selectArrow(c, x, y, $(this).attr('class'));
    $(this).attr('x', x).empty().append(a.firstChild);
  });
  
  var sorted = $("#svgNote").children().sort(function(a, b){
    var aX = $(a).attr('x');
    var aY = $(a).attr('y');
    var bX = $(b).attr('x');
    var bY = $(b).attr('y');
    if (aY < bY) { return -1; }
    if (aY > bY) { return  1; }
    if (aX < bX) { return -1; }
    if (aX > bX) { return  1; }
    return 0; // This should NEVER happen.
  });
  $("#svgNote").empty().append(sorted);
}

// Retrieve the selected arrows in an easy to use function.
function getSelectedArrows()
{
  return $("#svgNote > svg").filter(function(index){
    var y = parseFloat($(this).attr('y'));
    if ($("#selBot").attr('style').indexOf('none') > -1)
    {
      return y == $("#selTop").attr('y');
    }
    return y >= parseFloat($("#selTop").attr('y')) &&
        y <= parseFloat($("#selBot").attr('y'));
  });
}
// Load up the chosen user's songs.
function loadWebEdits(user)
{
  $(".loadSite").show();
  $("#intro").text("Loading " + (user == 2 ? "Andamiro's" : "your") + " edits...");
  $("#mem_edit").empty();
  $.getJSON(baseURL + '/loadSite/' + user, function(data)
  {
    for (var i = 0; i < data.length; i++)
    {
      var out = data[i].title + " (" + data[i].name + ") " + data[i].style.charAt(0).capitalize() + data[i].diff;
      var html = '<option id="' + data[i].id + '">' + out + '</option>';
      $("#mem_edit").append(html);
    }
    $("#intro").text("Choose your edit!");
  });
}
