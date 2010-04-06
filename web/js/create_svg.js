/*
 * This file is meant primarily for generating any SVG
 * structure as required. Any other calculations that
 * need to be done should be handled elsewhere.
 */
// Generate the line required. Apply the class if one exists.
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

// Generate the rect required. Apply the class if one exists.
function genRect(x, y, w, h, rx, ry, css, id)
{
  var r = document.createElementNS(SVG_NS, "rect");
  r.setAttribute("x", x);
  r.setAttribute("y", y);
  r.setAttribute("width", w);
  r.setAttribute("height", h);
  if (rx) { r.setAttribute("rx", rx); }
  if (ry) { r.setAttribute("ry", ry); }
  if (css) { r.setAttribute("class", css); }
  if (id) { r.setAttribute("id", id); }
  return r;
}

// Create the base arrow dimenions. It goes inside the SVG.
function genArrow(x, y, css)
{
  var s = document.createElementNS(SVG_NS, "svg");
  s.setAttribute("x", x);
  s.setAttribute("y", y);
  if (css) { s.setAttribute("class", css); }
  
  var g = document.createElementNS(SVG_NS, "g"); // needed for transforms
  s.appendChild(g);
  
  return s;
}

// The "base" arrow is down left. Use this to generate almost all others.
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
  
  if (css.indexOf("hold") >= 0 || css.indexOf("roll") >= 0 || css.indexOf("lift") >= 0)
  {
    p = document.createElementNS(SVG_NS, "path");
    p.setAttribute("d", "m 1,2 v 12 c 0,0 0,1 1,1 h 12 c 0,0 1,0 1,-1 v -1 c 0,0 0,-1 -1,-1 "
        + "H 7 L 4,9 V 2 C 4,2 4,1 3,1 H 2 C 2,1 1,1 1,2");
    s.firstChild.appendChild(p);
  }
  
  return s;
}

// Take the down left arrow, and rotate it to point up left.
function genULArrow(x, y, css)
{
  var s = genDLArrow(x, y, css);
  s.firstChild.setAttribute("transform", "rotate(90 " + 8 + " " + 8 + ")");
  return s;
}

// Make the center arrow. It's different from the others.
function genCNArrow(x, y, css)
{
  var s = genArrow(x, y, css);
  var p = document.createElementNS(SVG_NS, "path");
  p.setAttribute("d", "m 1,2 v 12 l 1,1 h 12 l 1,-1 V 2 L 14,1 H 2 z");
  s.firstChild.appendChild(p);
  s.firstChild.appendChild(genRect(4, 6, 2, 4, 0.5));
  s.firstChild.appendChild(genRect(10, 6, 2, 4, 0.5));
  
  if (css.indexOf("hold") >= 0 || css.indexOf("roll") >= 0 || css.indexOf("lift") >= 0)
  {
    p = document.createElementNS(SVG_NS, "path");
    p.setAttribute("d", "m 1,2 v 3 h 14 v -3 l -1,-1 h -12 l -1, 1");
    s.firstChild.appendChild(p);
  }
  return s;
}

// Take the down left arrow, and rotate it to point up right.
function genURArrow(x, y, css)
{
  var s = genDLArrow(x, y, css);
  s.firstChild.setAttribute("transform", "rotate(180 " + 8 + " " + 8 + ")");
  return s;
}

// Take the down left arrow, and rotate it to point down right.
function genDRArrow(x, y, css)
{
  var s = genDLArrow(x, y, css);
  s.firstChild.setAttribute("transform", "rotate(270 " + 8 + " " + 8 + ")");
  return s;
}

// Generate a circle, mainly used for mines.
function genCircle(cx, cy, r, css)
{
  var s = document.createElementNS(SVG_NS, "circle");
  s.setAttribute("cx", cx);
  s.setAttribute("cy", cy);
  s.setAttribute("r", r);
  if (css) { s.setAttribute("class", css); }
  return s;
}


// Generate circular mines for the steps one shouldn't hit.
function genMine(x, y, css)
{
  var s = genArrow(x, y, css); // this still works surprisingly.
  for (var i = 7; i > 2; i -= 2)
  {
    s.firstChild.appendChild(genCircle(8, 8, i));
  }
  return s;
}

// Fake arrows may or may not be in Pro 2. Prepare for them.
function genFake(x, y, css)
{
  var s = genArrow(x, y, css);
  var p = document.createElementNS(SVG_NS, "path");
  p.setAttribute("d", "m 1,3 l 5,5 -5,5 2,2 5,-5 5,5 2,-2 -5,-5 5,-5 -2,-2 -5,5 -5,-5 z");
  s.firstChild.appendChild(p);
  return s;
}

// Generate the end of the hold/roll.
function genEnd(x, y, css)
{
  var s = genArrow(x, y, css);
  var p1 = document.createElementNS(SVG_NS, "path");
  p1.setAttribute("d", "m 1,1 l 7,14 7,-14");
  s.firstChild.appendChild(p1);
  
  var p2 = document.createElementNS(SVG_NS, "path");
  p2.setAttribute("d", "m 4,1 l 4,8 4,-8");
  s.firstChild.appendChild(p2);
  
  return s;
}

// Generate the text used either for measure counting or syncing.
function genText(x, y, st, css)
{
  var s = document.createElementNS(SVG_NS, "text");
  s.setAttribute('x', x);
  s.setAttribute('y', y);
  if (css) { s.setAttribute('class', css); }
  s.appendChild(document.createTextNode(st));
  return s;
}

// Generate the measures that will hold the arrows.
function genMeasure(x, y, c)
{
  var s = document.createElementNS(SVG_NS, "svg");
  s.setAttribute("x", x);
  s.setAttribute("y", y);
  s.setAttribute("id", "measureNum" + c);
  
  for (var i = 0; i < BEATS_PER_MEASURE; i++)
  {
    s.appendChild(genRect(0, ARR_HEIGHT * i, columns * ARR_HEIGHT, ARR_HEIGHT,
      null, null, null, "m" + c + "r" + i));
  }
    s.appendChild(genText(2, 8, "" + c + ")"));
  
  s.appendChild(genLine(0, 0.1, columns * ARR_HEIGHT, 0.1));
  s.appendChild(genLine(0.05, 0, 0.05, ARR_HEIGHT * 4));
  var x = columns * ARR_HEIGHT - 0.05;
  s.appendChild(genLine(x, 0, x, ARR_HEIGHT * 4));
  
  return s;
}
