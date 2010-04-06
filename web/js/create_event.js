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
    songData = data;
    measures = songData.measures;
    $("#scalelist").val(2.5);
    captured = false;
    columns = getCols();
    $("rect[id^=sel]").attr('width', columns * ARR_HEIGHT).hide();
    fixScale(2.5);
    
    loadSVGMeasures();
    
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
