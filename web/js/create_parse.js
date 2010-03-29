/*
 * Replicate the data from JSON into both my JS format
 * and SVG.
 */
function loadChart(nd)
{
  notes = null; // clear out what's there.
  notes = Array(Array(), Array());
  $("#svgNote").empty();
  $("#svg").attr('width', width);
  $("#svgMeas").empty();
  $("#svgSync").empty();
  
  
  // append the measures.
  for (var i = 0; i < songData.measures; i++)
  {
    $("#svgMeas").append(genMeasure(ADJUST_SIZE, BUFF_TOP + MEASURE_HEIGHT * i, i + 1));
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
  
  var eRow = stringMul("0", columns); // completely empty row.
  
  LOOP_PLAYER:
  for (var iP = 0; iP < 2; iP++)
  {
    if (iP && style !== "routine") { break LOOP_PLAYER; }
    
    LOOP_MEASURE:
    for (var iM = 0; iM < songData.measures; iM++)
    {
      // NONE of these start off empty in nd.
      var rows = nd[iP][iM].length;
      
      LOOP_BEAT:
      for (var iB = 0; iB < rows; iB++)
      {
        if (nd[iP][iM][iB] === eRow) { continue LOOP_BEAT; }
        
        var tmp = notes[iP][iM];
        if (isEmpty(tmp))
        {
          notes[iP][iM] = Array();
        }
        var mul = (BEATS_MAX / rows) * iB;
        notes[iP][iM][mul] = Array(); // has to be something inside.
        LOOP_ROW:
        for (var iR = 0; iR < columns; iR++)
        {
          var ch = nd[iP][iM][iB].charAt(iR);
          if (ch === "0") { continue LOOP_ROW; }
          notes[iP][iM][mul][iR] = ch;
          var note = getNote(mul, ch, iP);
          var x = (iR + 1) * ARR_HEIGHT;
          var y = ((iM * BEATS_MAX) + mul + ADJUST_SIZE) / SCALE;
          $("#svgNote").append(selectArrow(iR, x, y, note));
        }
      }
      
    }
  }
}

/*
 * Determine how much to increment a loop in saveChart.
 */
function getMultiplier(iP, iM)
{
  var mul = Array(1, 2, 4, 6, 8, 12, 16, 24, 32, 48, 64, 192);
  
  MULTIPLIER:
  for (var m = 0; m < mul.length; m++)
  {
    NOTE:
    for (var i = 0; i < BEATS_MAX; i++)
    {
      if (isEmpty(notes[iP][iM][i])) { continue NOTE; }
      if (i % (BEATS_MAX / mul[m]) > 0) { continue MULTIPLIER; }
    }
    return BEATS_MAX / mul[m];
  }
  return BEATS_MAX; // just in case it doesn't catch it up there.
}

/*
 * Call this function for when the user wants to save the chart.
 */
function saveChart()
{
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
      
      var mul = getMultiplier(iP, iM);
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
    file += ";"
  }
  
  file += EOL + EOL;
  
  b64 = Base64.encode(file);
  //var href = "data:;base64," + b64;
  $("#b64").val(b64);
  $("#abbr").val(songData.abbr);
  $("#style").val(style);
  $("#diff").val(diff);
  $("#title").val(title);
}

function genObject(p, m, b, n)
{
  var t = {};
  t['player'] = p + 1;
  t['measure'] = m + 1;
  t['beat'] = b;
  t['note'] = n + 1;
  return t;  
}

/*
 * Update the chart details to show what's going on.
 * Return whatever points are considered invalid for
 * the chart.
 */
function gatherStats()
{
  steps = Array(0, 0);
  jumps = Array(0, 0);
  holds = Array(0, 0);
  mines = Array(0, 0);
  trips = Array(0, 0);
  rolls = Array(0, 0);
  lifts = Array(0, 0);
  fakes = Array(0, 0);

  badds = Array(); // make a note of where the bad points are.
  var holdCheck = Array();
  var stepCheck = Array();
  var numMeasures = songData.measures;

  LOOP_PLAYER:
  for (var iP = 0; iP < 2; iP++) // for each player (routine)
  {
    if (isEmpty(notes[iP])) { continue; }
    for (var i = 0; i < columns; i++)
    {
      holdCheck[i] = false;
    }
    LOOP_MEASURE:
    for (var iM in notes[iP]) // for each measure
    {
      LOOP_BEAT:
      for (var iB in notes[iP][iM]) // for each beat
      {
        var numSteps = 0;
        var trueC = 0;
        for (var i = 0; i < columns; i++)
        {
          stepCheck[i] = false;
        }
        
        LOOP_NOTE:
        for (var iN in notes[iP][iM][iB]) // for each note
        {
          // again, don't think switch works on strings in JS.
          var n = notes[iP][iM][iB][iN];
          if (n == '0') { continue LOOP_NOTE; }
          if (n == '1')
          {
            if (holdCheck[iN]) // if tap follows hold/roll head
            {
              badds.push(holdCheck[iN]);
              badds.push(genObject(iP, iM, iB, iN));
            }
            holdCheck[iN] = false;
            stepCheck[iN] = true;
            numSteps++;
            continue LOOP_NOTE;
          }
          if (n == '2')
          {
            if (holdCheck[iN]) // if hold head follows hold/roll head
            {
              badds.push(holdCheck[iN]);
            }
            holdCheck[iN] = genObject(iP, iM, iB, iN);
            stepCheck[iN] = true;
            numSteps++;
            holds[iP]++;
            continue LOOP_NOTE;
          }
          if (n == '3')
          {
            if (!holdCheck[iN]) // if hold/roll end doesn't follow head
            {
              badds.push(genObject(iP, iM, iB, iN));
            }
            holdCheck[iN] = false;
            stepCheck[iN] = true;
            continue LOOP_NOTE;
          }
          if (n == '4')
          {
            if (holdCheck[iN]) // if roll head follows hold/roll head
            {
              badds.push(holdCheck[iN]);
            }
            holdCheck[iN] = genObject(iP, iM, iB, iN);
            stepCheck[iN] = true;
            numSteps++;
            rolls[iP]++;
            continue LOOP_NOTE;
          }
          if (n == 'M')
          {
            if (holdCheck[iN]) // if mine follows hold/roll head
            {
              badds.push(holdCheck[iN]);
              badds.push(genObject(iP, iM, iB, iN));
            }
            holdCheck[iN] = false;
            mines[iP]++;
            continue LOOP_NOTE;
          }
          if (n == 'L')
          {
            if (holdCheck[iN]) // if lift follows hold/roll head
            {
              badds.push(holdCheck[iN]);
              badds.push(genObject(iP, iM, iB, iN));
            }
            holdCheck[iN] = false;
            lifts[iP]++;
            continue LOOP_NOTE;
          }
          if (n == 'F')
          {
            if (holdCheck[iN]) // if fake follows hold/roll head
            {
              badds.push(holdCheck[iN]);
              badds.push(genObject(iP, iM, iB, iN));
            }
            holdCheck[iN] = false;
            fakes[iP]++;
            continue LOOP_NOTE;
          }
        }
        for (var k = 0; k < columns; k++)
        {
          if ((stepCheck[k]) || (holdCheck[k]))
          {
            trueC++;
          }
        }
        if (numSteps > 0 && trueC >= 3)
        {
          trips[iP]++;
        }
        if (numSteps >= 2)
        {
          jumps[iP]++;
        }
        if (numSteps > 0)
        {
          steps[iP]++;
        }      
      } 
    }
    for (var i = 0; i < columns; i++) // if hold heads are still active
    {
      if (holdCheck[i]) { badds.push(holdCheck[i]) }
    }
  }
}
