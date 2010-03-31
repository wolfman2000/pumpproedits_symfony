/*
 * Load all of the following when the page is done loading.
 */
$(document).ready(function()
{
  init();
  
  $("#shadow").attr('width', ADJUST_SIZE).attr('height', ADJUST_SIZE);
  $("#songlist").val('');
  
  /*
   * The various action functions are set here.
   */
  $("#svg").mouseout(function(){ hideRect(); });
  $("#svg").mouseover(function(e){ shadow(e); });
  $("#svg").mousemove(function(e){ shadow(e); });
  $("#svg").click(function(){ changeArrow(); gatherStats(); updateStats(); });
  
  /*
   * When you ant to work on a new file, make sure you saved recently.
   */
  $("#but_new").click(function(){
    $("#intro").text("Working... Working...");
    var checking = true;
    if (isDirty)
    {
      checking = confirm("You have work not validated/saved.\nAre you sure you want to start over?");
    }
    if (checking) { init(); }
  });
  
  /*
   * Load a chart from either your hard drive (textarea) or your PPEdits account.
   */
  $("#but_load").click(function(){
    $("#intro").text("Working... Working...");
    var checking = true;
    if (isDirty) // implement later.
    {
      checking = confirm("You have work not validated/saved.\nAre you sure you want to load a new edit?");
    }
    if (checking)
    {
      $("li.edit").hide();
      if (authed > 0)
      {
        $("li.loadChoose").show();
        $("#intro").text("Computer or account?");
      }
      else
      {
        $("#fCont").val('');
        $("li.loadFile").show();
        $("li.loadFile > *").removeAttr('disabled');
        $("#but_file").attr('disabled', true);
        $("#intro").text("You can load your edit now.");
      }
    }
  });
  
  /*
   * Provide help for those that require it.
   */
  $("#but_help").click(function(){
    alert("Help will be available shortly.");
  });
  
  /*
   * All edits must be validated before the user can save their work.
   * Allowing malformed edits would not be good.
   */
  $("#but_val").click(function(){
    if (!badds.length)
    {
      saveChart();
      $("#intro").text("You can save your work!");
      $("#but_save").removeAttr('disabled');
      $("#but_val").attr('disabled', true);
      if (authed > 0)
      {
        $("#but_sub").removeAttr('disabled');
      }
    }
    else
    {
      $("#intro").text("Please fix your errors.");
      var ouch = "Errors were found here:\n\n";
      for (var i = 0; i < badds.length; i++)
      {
        ouch += "Player " + badds[i]['player'] + " Measure " + badds[i]['measure']
          + " Beat " + badds[i]['beat'] + " Note " + badds[i]['note'] + "\n";
      }
      alert(ouch);
    }
  });
  
  /*
   * An authed user decides to load from a file after all.
   */
  $("#cho_file").click(function(){
    $("#fCont").val('');
    $(".loadChoose").hide();
    $(".loadFile").show();
    $("li.loadFile > *").removeAttr('disabled');
    $("#but_file").attr('disabled', true);
    $("#intro").text("You can load your edit now.");
  });
  
  /*
   * An authed user wants to load one of his edits.
   */
  $("#cho_site").click(function(){
    $(".loadChoose").hide();
    $(".loadSite").show();
    $("#intro").text("Loading your edits...");
    $("#mem_edit").empty();
    // have to do another AJAX call.
    $.ajax({ async: true, dataType: 'json', url: baseURL + '/loadSite/' + authed, success: function(data)
    {
      for (var i = 0; i < data.length; i++)
      {
        var out = data[i].title + " (" + data[i].abbr + ") " + data[i].style.charAt(0).capitalize() + data[i].diff;
        var html = '<option id="' + data[i].id + '">' + out + '</option>';
        $("#mem_edit").append(html);
      }
      $("#intro").text("Choose your edit!");
    }});
  });
  
  
  /*
   * This text area is where the edit will be loaded from.
   */
  $("#fCont").keyup(function(){
    tarea = $("#fCont").val();
    if (tarea.length)
    {
      $("#but_file").removeAttr('disabled');
    }
    else
    {
      $("#but_file").attr('disabled', true);
    }
  });
  
  /*
   * Load the edit from the text area with this button.
   */
  $("#but_file").click(function(){
    
    tarea = $("#fCont").val();
    var done;
    $.post(window.location.href + "/loadFile", { file: Base64.encode(tarea)}, function(data, status)
    {
      songID = data.id;
      style = data.style;
      diff = data.diff;
      title = data.title;
      steps = data.steps;
      jumps = data.jumps;
      holds = data.holds;
      mines = data.mines;
      trips = data.trips;
      rolls = data.rolls;
      fakes = data.fakes;
      lifts = data.lifts;
      updateStats();
      $("#editDiff").val(diff);
      $("#editName").val(title);
      $("#fCont").val('');
      $(".loadFile").hide();
      $("li.edit").show();
      done = editMode();
      $("#intro").text("Loading chart...");
      loadChart(data.notes);
      $("#intro").text("All loaded up!");
      $("#but_save").removeAttr('disabled');
      $("#but_val").attr('disabled', 'disabled');
      isDirty = false;
    }, "json");
  });
  /*
   * Load the specific member edit.
   */
  $("#mem_load").click(function(){
    $("#intro").text("Loading edit...");
    editID = $("#mem_edit > option:selected").attr('id');
    $.getJSON(baseURL + "/loadSiteEdit/" + editID, function(data) {
      songID = data.id;
      style = data.style;
      diff = data.diff;
      title = data.title;
      steps = data.steps;
      jumps = data.jumps;
      holds = data.holds;
      mines = data.mines;
      trips = data.trips;
      rolls = data.rolls;
      fakes = data.fakes;
      lifts = data.lifts;
      updateStats();
      $("#editDiff").val(diff);
      $("#editName").val(title);
      $("#fCont").val('');
      $(".loadSite").hide();
      $("li.edit").show();
      done = editMode();
      $("#intro").text("Loading chart...");
      loadChart(data.notes);
      $("#intro").text("All loaded up!");
      if (title.length)
      {
        $("#editName").attr('disabled', true);
      }
      isDirty = false;
    });
  });
  
  $("#mem_nogo").click(function(){ // On second thought, don't deal with choosing your edit.
    $("#fCont").val('');
    $(".loadSite").hide();
    $("li.edit").show();
  });
  
  $("#rem_file").click(function(){ // On second thought, don't deal with uploading.
    $("#fCont").val('');
    $(".loadFile").hide();
    $("li.edit").show();
  });
  
  $("#but_save").click(function(){ // save to your local hard drive
    $("#intro").text("Here it comes!");
  });
  
  $("#but_sub").click(function(){ // submit online directly
    var data = {};
    data['b64'] = b64;
    data['title'] = title;
    data['diff'] = diff;
    data['style'] = style;
    data['editID'] = editID;
    data['songID'] = songID;
    data['userID'] = authed;
    data['steps1'] = steps[0];
    data['steps2'] = steps[1];
    data['jumps1'] = jumps[0];
    data['jumps2'] = jumps[1];
    data['holds1'] = holds[0];
    data['holds2'] = holds[1];
    data['mines1'] = mines[0];
    data['mines2'] = mines[1];
    data['rolls1'] = rolls[0];
    data['rolls2'] = rolls[1];
    data['trips1'] = trips[0];
    data['trips2'] = trips[1];
    data['fakes1'] = fakes[0];
    data['fakes2'] = fakes[1];
    data['lifts1'] = lifts[0];
    data['lifts2'] = lifts[1];
    
    $("#intro").text("Uploading edit...");
    $.post(window.location.href + "/upload", data, function(data, status)
    {
      $("#intro").text("Edit Uploaded");
      $("#editName").attr("disabled", "disabled");
    }, "json");
  });
  
  $('#songlist').change(function(){ // choose the song you want
    songID = $("#songlist").val();
    if (songID.length > 0) { $("#stylelist").removeAttr("disabled"); }
    else { $("#stylelist").attr("disabled", "disabled"); }
  });
  $("#stylelist").change(function(){ // choose the style: single, double, etc
    style = $("#stylelist").val();
    editMode();
    $("#intro").text("Have fun editing!");
  });
  
  $("#quanlist").change(function() { sync = $("#quanlist").val();});
  $("#typelist").change(function() { note = $("#typelist").val();});
  
  $("#editName").keyup(function(){ // what will the edit be called?
    $("#but_save").attr('disabled', true);
    $("#but_sub").attr('disabled', true);
    var t = $("#editName").val();
    if (t.length > 0 && t.length <= 12)
    {
      title = t;
      if (diff > 0)
      {
        $("#but_val").removeAttr('disabled');
        $("#intro").text("Validate your edit before saving.");
      }
    }
    else
    {
      $("#but_val").attr('disabled', true);
      $("#intro").text("Provide an edit title and difficulty.");
    }
  });
  $("#editDiff").keyup(function(){ // how hard is the edit?
    $("#but_save").attr('disabled', true);
    $("#but_sub").attr('disabled', true);
    var t = parseInt($("#editDiff").val());
    if (t > 0 && t < 100)
    {
      diff = t;
      if (title)
      {
        $("#but_val").removeAttr('disabled');
        $("#intro").text("Validate your edit before saving.");
      }
    }
    else
    {
      $("#but_val").attr('disabled', true);
      $("#intro").text("Provide an edit title and difficulty.");
    }
  });
  
  $("#p1").change(function() { player = 0; });
  $("#p2").change(function() { player = 1; });
  
});
