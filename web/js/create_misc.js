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