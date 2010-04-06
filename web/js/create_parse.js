/*
 * This file deals with parsing the SVG file to gather stats,
 * load charts, and save/upload charts.
 */
// Load the data from JSON to JS/SVG.
function loadChart(nd)
{
  $("#svgNote").empty();
  $("#svg").attr('width', width);
  $("#svgMeas").empty();
  $("#svgSync").empty();
  
  loadSVGMeasures();
  
  var eRow = stringMul("0", columns); // completely empty row.
  
  LOOP_PLAYER:
  for (var iP = 0; iP < 2; iP++)
  {
    if (iP && $("#stylelist").val() !== "routine") { break LOOP_PLAYER; }
    
    LOOP_MEASURE:
    for (var iM = 0; iM < songData.measures; iM++)
    {
      // NONE of these start off empty in nd.
      var rows = nd[iP][iM].length;
      
      LOOP_BEAT:
      for (var iB = 0; iB < rows; iB++)
      {
        if (nd[iP][iM][iB] === eRow) { continue LOOP_BEAT; }
        
        var mul = (BEATS_MAX / rows) * iB;
        LOOP_ROW:
        for (var iR = 0; iR < columns; iR++)
        {
          var ch = nd[iP][iM][iB].charAt(iR);
          if (ch === "0") { continue LOOP_ROW; }
          var note = getNote(mul, ch, iP);
          var x = iR * ARR_HEIGHT + BUFF_LFT;
          var y = ((iM * BEATS_MAX + mul) / MEASURE_RATIO) + BUFF_TOP;
          $("#svgNote").append(selectArrow(iR, x, y, note));
        }
      }
    }
  }
}

// Load the measures and other related data for the SVG chart.
function loadSVGMeasures()
{
  // append the measures.
  for (var i = 0; i < songData.measures; i++)
  {
    $("#svgMeas").append(genMeasure(BUFF_LFT, BUFF_TOP + ARR_HEIGHT * BEATS_PER_MEASURE * i, i + 1));
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
  // place the Stop data.
  var stps = songData.stps;
  for (var i = 0; i < stps.length; i++)
  {
    y = BUFF_TOP + stps[i].beat * ARR_HEIGHT;
    $("#svgSync").append(genText(SCALE * 3, y + SCALE, stps[i].time, 'stop'));
    $("#svgSync").append(genLine(BUFF_LFT, y, BUFF_LFT + columns * ARR_HEIGHT / 2, y, 'stop'));
  }
}

// Determine how much to increment a loop in saveChart.
function getMultiplier(row)
{
  var mul = Array(1, 2, 4, 6, 8, 12, 16, 24, 32, 48, 64, 192);
  
  MULTIPLIER:
  for (var m = 0; m < mul.length; m++)
  {
    NOTE:
    for (var i = 0; i < BEATS_MAX; i++)
    {
      if (isEmpty(row[i])) { continue NOTE; }
      if (i % (BEATS_MAX / mul[m]) > 0) { continue MULTIPLIER; }
    }
    return BEATS_MAX / mul[m];
  }
  return BEATS_MAX; // just in case it doesn't catch it up there.
}

// Turn the SVG data structure into the 4 layered arrays.
function SVGtoNOTES()
{
  var notes = Array();
  notes[0] = Array();
  if ($("#stylelist").val() === "routine") { notes[1] = Array() };
  
  $("#svgNote").children().each(function(ind){
    var p = getPlayerByClass($(this).attr('class'));
    var y = parseFloat($(this).attr('y')) - BUFF_TOP;
    var m = Math.floor(y * MEASURE_RATIO / BEATS_MAX);
    var b = Math.round(y * MEASURE_RATIO % BEATS_MAX);
    var x = parseFloat($(this).attr('x'));
    var c = (x - BUFF_LFT) / ARR_HEIGHT;
    var t = getTypeByClass($(this).attr('class'));
    
    if (isEmpty(notes[p][m]))     { notes[p][m]    = Array(); }
    if (isEmpty(notes[p][m][b]))  { notes[p][m][b] = Array(); }
    
    notes[p][m][b][c] = t;
  });
  return notes;
}

/*
 * Call this function for when the user wants to save the chart.
 */
function saveChart()
{
  var style = $("#stylelist").val();
  var title = $("#editName").val();
  var file = "#SONG:" + songData.name + ";" + EOL;
  file += "#NOTES:" + EOL;
  file += "   pump-" + style + ":" + EOL;
  file += "   " + title + ":" + EOL;
  file += "   Edit:" + EOL;
  file += "   " + diff + ":" + EOL;
  /*
   * I'm sure the radar line will not be exactly right for Pro 2.
   * Still, until someone actually can let me know what the radar line
   * LOOKS like for a Pro 2 file, I have to stick with the old fashioned way.
   */
  file += "   0,0,0,0,0," + steps[0] + ',' + jumps[0] + ',' + holds[0] + ','
    + mines[0] + ',' + trips[0] + ',' + rolls[0] + ',';
  if (style !== "routine")
  {
    file += "0,0,0,0,0," + steps[0] + ',' + jumps[0] + ',' + holds[0] + ','
    + mines[0] + ',' + trips[0] + ',' + rolls[0] + ':' + EOL + EOL;
  }
  else
  {
    file += "0,0,0,0,0," + steps[1] + ',' + jumps[1] + ',' + holds[1] + ','
    + mines[1] + ',' + trips[1] + ',' + rolls[1] + ':' + EOL + EOL;
  }
  
  notes = SVGtoNOTES();
  
  // And now, we're at measure data.
  LOOP_PLAYER:
  for (var iP = 0; iP < 2; iP++) // for each player
  {
    if (iP)
    {
      if (style !== "routine") { break LOOP_PLAYER; }
      file += "&" + EOL;
    }
    
    LOOP_MEASURE:
    for (var iM = 0; iM < songData.measures; iM++)
    {
      file += (iM ? "," : " ") + "  // measure " + (iM + 1) + EOL;
      
      if (isEmpty(notes[iP][iM]))
      {
        file += stringMul("0", columns) + EOL;
        continue LOOP_MEASURE;
      }
      
      var mul = getMultiplier(notes[iP][iM]);
      LOOP_BEAT:
      for (var iB = 0; iB < BEATS_MAX; iB = iB + mul)
      {
        if (isEmpty(notes[iP][iM][iB]))
        {
          file += stringMul("0", columns) + EOL;
          continue LOOP_BEAT;
        }
        
        LOOP_ROW:
        for (var iR = 0; iR < columns; iR++)
        {
          var tmp = notes[iP][iM][iB][iR];
          file += (isEmpty(tmp) ? "0" : tmp);
        }
        
        file += EOL;
      }
    }
  }
  
  file += ";" + EOL + EOL;
  
  $("#b64").val(Base64.encode(file));
  $("#abbr").val(songData.abbr);
  $("#style").val(style);
  $("#diff").val(diff);
  $("#title").val(title);
}

function genObject(p, m, b, n)
{
  var t = {};
  t['player'] = p + 1;
  t['measure'] = m;
  t['beat'] = b;
  t['note'] = n + 1;
  return t;  
}

/*
 * Update the chart details to show what's going on.
 * Return the data gathered, including the points
 * that are considered invalid for the chart.
 */
function gatherStats()
{
  var data = {};
  data.steps = Array(0, 0);
  data.jumps = Array(0, 0);
  data.holds = Array(0, 0);
  data.mines = Array(0, 0);
  data.trips = Array(0, 0);
  data.rolls = Array(0, 0);
  data.lifts = Array(0, 0);
  data.fakes = Array(0, 0);

  data.badds = Array(); // make a note of where the bad points are.
  var holdCheck = Array();
  var stepCheck = Array();
  var numMeasures = songData.measures;
  
  var oX = -1;
  var oY = -1;
  var numSteps = Array(0, 0);
  var trueC = Array(0, 0);
  
  function checkBasics(sC, hC)
  {
    for (var k = 0; k < columns; k++)
    {
      if      (stepCheck[k]) { trueC[stepCheck[k]['player'] - 1]++; }
      else if (holdCheck[k]) { trueC[holdCheck[k]['player'] - 1]++; }
    }
    for (var playa = 0; playa < 2; playa++)
    {
      if (numSteps[playa] > 0 && trueC[playa] >= 3) { data.trips[playa]++; }
      if (numSteps[playa] >= 2)                     { data.jumps[playa]++; }
      if (numSteps[playa] > 0)                      { data.steps[playa]++; }
    }
  }
  
  $("#svgNote").children().each(function(ind){
    var p = getPlayerByClass($(this).attr('class'));
    var y = parseFloat($(this).attr('y')) - BUFF_TOP;
    var m = Math.floor(y * MEASURE_RATIO / BEATS_MAX) + 1;
    var b = Math.round(y * MEASURE_RATIO % BEATS_MAX);
    var x = parseFloat($(this).attr('x'));
    var c = (x - BUFF_LFT) / ARR_HEIGHT;
    var t = getTypeByClass($(this).attr('class'));
    
    if (oY !== y) // new row
    {
      if (oY >= 0) // calculate all of the old stats first.
      {
        checkBasics(stepCheck, holdCheck);
        
        stepCheck = Array(); // reset.
        for (var i = 0; i < columns; i++) { stepCheck[i] = false; }
        numSteps = Array(0, 0);
        trueC = Array(0, 0);
      }
      oY = y;
    }
    
    if (t === "1") // tap
    {
      // if tap follows hold/roll head
      if (holdCheck[c]) { data.badds.push(holdCheck[c], genObject(p, m, b, c)); }
      holdCheck[c] = false;
      stepCheck[c] = genObject(p, m, b, c);
      numSteps[p]++;
    }
    else if (t === "2") // hold
    {
      // if hold head follows hold/roll head
      if (holdCheck[c]) { data.badds.push(holdCheck[c]); }
      holdCheck[c] = genObject(p, m, b, c);
      stepCheck[c] = genObject(p, m, b, c);
      numSteps[p]++;
      data.holds[p]++;
    }
    else if (t === "3") // hold/roll end
    {
      // if hold/roll end doesn't follow head
      if (!holdCheck[c]) { data.badds.push(genObject(p, m, b, c)); }
      holdCheck[c] = false;
      stepCheck[c] = genObject(p, m, b, c);
    }
    else if (t === "4") // roll
    {
      // if roll head follows hold/roll head
      if (holdCheck[c]) { data.badds.push(holdCheck[c]); }
      holdCheck[c] = genObject(p, m, b, c);
      stepCheck[c] = genObject(p, m, b, c);
      numSteps[p]++;
      data.rolls[p]++;
    }
    else if (t === 'M') // mine
    {
      // if mine follows hold/roll head
      if (holdCheck[c]) { data.badds.push(holdCheck[c], genObject(p, m, b, c)); }
      holdCheck[c] = false;
      data.mines[p]++;
    }
    else if (t === 'L') // lift
    {
      // if lift follows hold/roll head
      if (holdCheck[c]) { badds.push(holdCheck[c], genObject(p, m, b, c)); }
      holdCheck[c] = false;
      lifts[p]++;
    }
    else if (t === 'F') // fake
    {
       // if fake follows hold/roll head
      if (holdCheck[c]) { data.badds.push(holdCheck[c], genObject(p, m, b, c)); }
      holdCheck[c] = false;
      data.fakes[p]++;
    }
  });
  checkBasics(stepCheck, holdCheck);
  for (var i = 0; i < columns; i++) // if hold heads are still active
  {
    if (holdCheck[i]) { data.badds.push(holdCheck[i]) }
  }
  return data;
}
