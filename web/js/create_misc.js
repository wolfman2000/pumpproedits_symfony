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
    var sY = BEATS_MAX / sync / MEASURE_RATIO * SCALE; // get the current note.

    while (nY + sY < beatM) { nY += sY; }
    nY = wholeM * scaledM + nY;
    nY = nY / SCALE;
    showRect(nX + BUFF_LFT, nY);
  }
  else hideRect(); // Best to be safe and explicit.
}