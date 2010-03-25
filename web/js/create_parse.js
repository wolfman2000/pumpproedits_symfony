function saveChart()
{

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
    for (var iN in holdCheck) // if hold heads are still active
    {
      if (iN) { badds.push(iN) }
    }
  }
}
