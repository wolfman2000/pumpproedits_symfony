function saveChart()
{

}

/*
 * Update the chart details to show what's going on.
 * Return whatever points are considered invalid for
 * the chart.
 */
function updateDetails()
{
  steps = Array();
  jumps = Array();
  holds = Array();
  mines = Array();
  trips = Array();
  rolls = Array();
  lifts = Array();
  fakes = Array();

  var badds = {}; // make a note of where the bad points are.
  var holdCheck = Array();
  for (var i = 0; i < columns; i++)
  {
    holdCheck[i] = false;
  }

  var numMeasures = songData.measures;

  for (var iP = 0; iP < 2; iP++) // for each player (routine)
  {
    if (isEmpty(notes[iP])) { next; }
    for (var iM in notes[iP]) // for each measure
    {
      for (var iB in notes[iP][iM]) // for each beat
      {
        for (var iN in notes[iP][iM][iN]) // for each note
        {

        }
      } 
    }
  }
  
}
