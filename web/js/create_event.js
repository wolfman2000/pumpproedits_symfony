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
  // No placing arrows while loading stuff.
  if ($(".buttons li[class^=load]:visible").length) { return; }
  var pnt = $("#m1r0");
  if (pnt.offset()) { shadow(e.pageX, e.pageY, pnt); }
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

// Display the updated stats. Primarily asynchronous.
function updateStats(data)
{
  var S = data.steps[0];
  var J = data.jumps[0];
  var H = data.holds[0];
  var M = data.mines[0];
  var T = data.trips[0];
  var R = data.rolls[0];
  var L = data.lifts[0];
  var F = data.fakes[0];
  if ($("#stylelist").val() === "routine")
  {
    S += "/" + data.steps[1];
    J += "/" + data.jumps[1];
    H += "/" + data.holds[1];
    M += "/" + data.mines[1];
    T += "/" + data.trips[1];
    R += "/" + data.rolls[1];
    L += "/" + data.lifts[1];
    F += "/" + data.fakes[1];
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
  var t = $("#editName").val().length;
  if (t > 0 && t <= 12 && parseInt($("#editDiff").val()) > 0)
  {
    if (data.steps[0] || data.steps[1] || data.mines[0] || data.mines[1] ||
        data.lifts[0] || data.lifts[1] || data.fakes[0] || data.fakes[1])
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
// The author will load an edit from the hard drive.
function loadHardDrive()
{
  $("#fCont").val('');
  $(".loadChoose").hide();
  $(".loadFile").show();
  $("li.loadFile > *").removeAttr('disabled');
  $("#but_file").attr('disabled', true);
  $("#intro").text("You can load your edit now.");
}
// Load the chosen edit...or at least, load the common stuff here.
function loadEdit(data)
{
  $(".edit").hide();
  songID = data.id;
  $("#stylelist").val(data.style);
  $("#editDiff").val(data.diff);
  $("#editName").val(data.title);
  updateStats(data);
  $("#fCont").val('');
  $(".loadFile").hide();
  $(".loadSite").hide();
  $("li.edit").show();
  editMode();
  $("#intro").text("Loading chart...");
  loadChart(data.notes);
      
}

// Cancel the edit loading process, restoring the normal buttons.
function cancelLoad()
{
  $("#fCont").val('');
  $(".loadSite").hide();
  $(".loadFile").hide();
  $("li.edit").show();
  if (!$("#stylelist").val().length) { $(".choose").show(); }
}

//Enter this mode upon choosing a song and difficulty.
function editMode()
{
  $("#intro").text("Loading song data...");
  $.ajax({ async: false, dataType: 'json', url: baseURL + '/song/' + songID, success: function(data)
  {
    songData = data;
    measures = songData.measures;
    $("#scalelist").val(2.5);
    captured = false;
    columns = getCols();
    $("rect[id^=sel]").attr('width', columns * ARR_HEIGHT).hide();
    fixScale(2.5, 600);
    
    loadSVGMeasures();
    
    $("nav dt.edit").show();
    $("nav dd.edit").show();
    $("nav *.choose").hide();
    if ($("#stylelist").val() !== "routine") { $("nav .routine").hide(); }
    else { $("nav .routine").show(); }
    var phrase = songData.name + " " + $("#stylelist").val().capitalize();
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
      if (andamiro) { $(".author").show(); $("#authorlist").removeAttr('disabled'); }
      else          { $(".author").hide(); $("#authorlist").attr('disabled', true); }
      $("#authorlist").val(0);
      authID = authed;
    }
    clipboard = null;
    return true;
  }});
  return false; // this is to ensure the asyncing is done right.
}


// Load up this data on new.
function init()
{
  captured = false;
  clipboard = null;
  measures = 3; // temp variable.
  columns = 5; // reasonable default.
  $("article").css('height', '50em');
  fixScale(2, 1000,
    5 * ARR_HEIGHT * SCALE + BUFF_LFT + BUFF_RHT,
    ADJUST_SIZE * BEATS_PER_MEASURE * 3 + BUFF_TOP + BUFF_BOT);
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
  if (authed > 0) { $("#cho_site").removeAttr('disabled'); }
  else            { $("#cho_site").attr('disabled', true); }
  
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
  editID = 0;
  selMode = 0;

  $("#svgMeas").empty();
  $("#svgSync").empty();
  $("#svgNote").empty();
  
  $("#intro").text("Select your action.");
  
  isDirty = false;
}

// Dynamically adjust the scale as needed.
function fixScale(num, len, w, h)
{
  /*
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
  if (!len) { var len = 1000; }
  SCALE = num;
  ADJUST_SIZE = ARR_HEIGHT * SCALE;
  MEASURE_HEIGHT = ADJUST_SIZE * BEATS_PER_MEASURE;
  if (!h) { var h = SCALE * (ARR_HEIGHT * BEATS_PER_MEASURE * measures + BUFF_TOP + BUFF_BOT); }
  if (!w) { var w = SCALE * ((BUFF_LFT + BUFF_RHT) + columns * ARR_HEIGHT); }
  
  $("#svg").animate({
    left: round10($("nav").first().width()) + 70,
    top: round10($("header").first().height()) * 8 + 20,
    width: w,
    height: h,
  }, len).attr("width", w).attr("height", h);
  
  $("#notes").attr("transform", "scale(" + SCALE + ")");
  $("article").css("height", h + 150);
}

// Swap the cursor mode as required.
function swapCursor()
{
  if (selMode == 0)
  {
    $("#intro").text("Resume placing arrows.");
    $("#selTop").hide();
    $("#selBot").hide();
    clipboard = null;
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
  
  var val = Math.floor(-parseInt($("#quanlist").val()));
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

// Shift the selected arrows down based on the note sync.
function shiftDown()
{
  var val = Math.floor(parseInt($("#quanlist").val()));
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

// Cut the arrows, and place onto the clipboard.
function cutArrows()
{
  copyArrows();
  if (clipboard == null || !clipboard.length)
  {
    clipboard = null;
    $("#intro").text("You didn't cut or copy anything.");
    return;
  }
  getSelectedArrows().each(function(){
    $(this).remove();
  });
}

// Copy the arrows, and place onto the clipboard.
function copyArrows()
{
  clipboard = getSelectedArrows().clone();
  if (clipboard == null || !clipboard.length)
  {
    clipboard = null;
    $("#intro").text("You didn't cut or copy anything.");
  }
}

// Paste the arrows in the clipboard.
function pasteArrows()
{
  var tY = parseFloat($("#selTop").attr('y'));
  if ($("#selBot").attr('style').indexOf('none') != -1)
  {
    $("#selBot").attr('y', tY).attr('x', BUFF_LFT).show();
  }
  var bY = parseFloat($("#selBot").attr('y'));
  var range = bY - tY; // How big is the range for copy/pasting?
  var rY = parseFloat($("#shadow").attr('y'));
  var shift = rY - tY; // How much are we changing each note?
  
  // Move the selection rectangles to their new location.
  $("#selTop").attr('y', rY);
  $("#selBot").attr('y', rY + range);
  
  // Remove what's inside the pasting location.
  getSelectedArrows().each(function(){
    $(this).remove();
  });
  
  clipboard.each(function(){
    var csses = $(this).attr('class').split(' ');
    var oY = parseFloat($(this).attr('y'));
    var nY = oY + shift;
    $(this).attr('y', nY);
    nY += BUFF_BOT;
    
    var beatM = Math.round((nY % (ARR_HEIGHT * SCALE * BEATS_PER_MEASURE)) * MEASURE_RATIO);
    
    $(this).attr('class', csses[0] + " " + getSync(beatM) + " " + csses[2]);
  });
  $("#svgNote").append(clipboard);
  var sH = Math.floor($("#svg").attr('height')) / SCALE;
  removeDown(sH - BUFF_BOT - 0.1, sH * 2 * SCALE); // ensure nothing went BELOW the measures.
  sortArrows();
  clipboard = null;
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
  
  sortArrows();
}

// Mirror the arrows across the middle point of the chart.
function mirrorRows()
{
  var m = (BUFF_LFT + BUFF_RHT + columns * ARR_HEIGHT) / 2;
  getSelectedArrows().each(function(ind){
    var x = Math.floor($(this).attr('x'));
    var y = Math.floor($(this).attr('y'));
    
    // Note to code improvers: find a way to NOT hardcode this as bad.
    FIX_X:
    switch (columns)
    {
      case 5:
      {
        switch (x)
        {
          case 32: { x = 96; break FIX_X; }
          case 48: { x = 80; break FIX_X; }
          case 64: { x = 64; break FIX_X; }
          case 80: { x = 48; break FIX_X; }
          case 96: { x = 32; break FIX_X; }
        }
      }
      case 6:
      {
        switch (x)
        {
          case 32:  { x = 112; break FIX_X; }
          case 48:  { x = 96;  break FIX_X; }
          case 64:  { x = 80;  break FIX_X; }
          case 80:  { x = 64;  break FIX_X; }
          case 96:  { x = 48;  break FIX_X; }
          case 112: { x = 32;  break FIX_X; }
        }
      }
      case 10:
      {
        switch (x)
        {
          case 32:  { x = 176; break FIX_X; }
          case 48:  { x = 160; break FIX_X; }
          case 64:  { x = 144; break FIX_X; }
          case 80:  { x = 128; break FIX_X; }
          case 96:  { x = 112; break FIX_X; }
          case 112: { x = 96;  break FIX_X; }
          case 128: { x = 80;  break FIX_X; }
          case 144: { x = 64;  break FIX_X; }
          case 160: { x = 48;  break FIX_X; }
          case 176: { x = 32;  break FIX_X; }
        }
      }
    }
    
    var c = (x - BUFF_LFT) / ARR_HEIGHT;
    var a = selectArrow(c, x, y, $(this).attr('class'));
    $(this).attr('x', x).empty().append(a.firstChild);
  });
  sortArrows();
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
