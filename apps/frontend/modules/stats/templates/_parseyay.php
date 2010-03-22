<?php slot('title', "Stats on your {$result['title']} edit — Pump Pro Edits");
$style = $result['style'];
function statRow($dt, $dd, $style)
{
  echo "<dt>$dt</dt><dd>${dd[0]}";
  if ($style === "pump-routine") { echo "/${dd[1]}"; }
  echo "</dd>\r\n";
}

?>

<h2>Successful Parse!</h2>

<p>Your edit was parsed successfully. Its stats are as follows:</p>

<dl>
<dt>Title</dt><dd><?php echo $result['title'] ?></dd>
<dt>Style</dt><dd><?php echo $style ?></dd>
<dt>Difficulty</dt><dd><?php echo $result['diff'] ?></dd>
<?php statRow("Steps", $result['steps'], $style);
statRow("Jumps", $result['jumps'], $style);
statRow("Holds", $result['holds'], $style);
statRow("Mines", $result['mines'], $style);
statRow("Trips", $result['trips'], $style);
statRow("Rolls", $result['rolls'], $style);
statRow("Lifts", $result['lifts'], $style);
statRow("Fakes", $result['fakes'], $style); ?>
</dl>

<p>Feel free to submit another edit to get its stats too!</p>
