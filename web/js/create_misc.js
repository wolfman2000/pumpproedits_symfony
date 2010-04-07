/*
 * This file is for the miscelaneous functions that are used
 * that don't directly involve javascript called events or
 * SVG nodes. They are in this file for better abstraction.
 */
// Indicate where the shadow square goes.
function showRect(x, y)
{
  $("#shadow#").attr('x', x).attr('y', y + BUFF_TOP).show();
  $("#mCheck").text(Math.floor(y / BEATS_MAX * MEASURE_RATIO) + 1);
  $("#yCheck").text(Math.round(y * MEASURE_RATIO) % BEATS_MAX);
}

// Trace the mouse to see where the shadow falls.
function shadow(pX, pY, pnt)
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
  
    mX = Math.floor(pX - curleft - BUFF_LFT * SCALE);
    mY = Math.floor(pY - curtop - BUFF_TOP * SCALE);
  }
  else
  {
    mX = pX - pnt.offset().left;
    mY = pY - pnt.offset().top;
  }
  var maxY = Math.floor($("#svgMeas > svg:last-child").attr('y')) + 3 * ARR_HEIGHT;
  var maxX = columns * ADJUST_SIZE;
  if (!(mX < 0 || mX > maxX || mY < 0 || mY > SCALE * maxY))
  {
    var nX = 0;
    var nY = 0;
    
    while (nX + ADJUST_SIZE < mX) { nX += ADJUST_SIZE; }
    nX = nX / SCALE;

    var scaledM = ARR_HEIGHT * SCALE * BEATS_PER_MEASURE;
    var wholeM = Math.floor(mY / scaledM);
    var beatM = mY % scaledM;
    var sY = BEATS_MAX / parseInt($("#quanlist").val()) / MEASURE_RATIO * SCALE; // get the current note.

    while (nY + sY < beatM) { nY += sY; }
    nY = wholeM * scaledM + nY;
    nY = nY / SCALE;
    showRect(nX + BUFF_LFT, nY);
  }
  else hideRect(); // Best to be safe and explicit.
}

// Determine which player class to retrieve.
function getPlayer(pl)
{
  if ($("#stylelist").val() !== "routine") { return "pS"; }
  return "p" + $("#playerlist").val();
}

// Determine the player number based on the player class.
function getPlayerByClass(jQ)
{
  if ($("#stylelist").val() !== "routine") { return 0; } // doesn't matter here.
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
  if (nt == null) { nt = $("#typelist").val(); }
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
  
  switch (($("#stylelist").val() === "halfdouble" ? cX + 2 : cX) % 5)
  {
    case 0: return genDLArrow(rX, rY, css);
    case 1: return genULArrow(rX, rY, css);
    case 2: return genCNArrow(rX, rY, css);
    case 3: return genURArrow(rX, rY, css);
    case 4: return genDRArrow(rX, rY, css);
  }
}

// Retrieve the number of columns we'll be using today.
function getCols()
{
  switch ($("#stylelist").val().charAt(0))
  {
    case "s": return 5;
    case "h": return 6;
    case "d": case "r": return 10;
    default: return 0; // I wonder if an exception should be thrown here.
  }
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

// Sort the SVG nodes in place so it respects the painter's model.
function sortArrows()
{
  var sorted = $("#svgNote").children().sort(function(a, b){
    var aX = parseFloat($(a).attr('x'));
    var aY = parseFloat($(a).attr('y'));
    var bX = parseFloat($(b).attr('x'));
    var bY = parseFloat($(b).attr('y'));
    if (aY < bY) { return -1; }
    if (aY > bY) { return  1; }
    if (aX < bX) { return -1; }
    if (aX > bX) { return  1; }
    return 0; // This should NEVER happen.
  });
  $("#svgNote").empty().append(sorted);
}
