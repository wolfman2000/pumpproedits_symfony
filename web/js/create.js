$(document).ready(function()
{
  init();
  
  $("#songlist").val('');
  $("rect[id^=sel]").attr('x', BUFF_LFT);
  $("#notes").attr('transform', 'scale(' + SCALE + ')');
  
  // Don't show the rectangle when not in play.
  $("#svg").mouseout(function(){ hideRect(); });
  // Show the rectangle if the mouse is over a measure.
  $("#svg").mouseover(function(e){ shadow(e); });
  $("#svg").mousemove(function(e){ shadow(e); });
  // If the shadow rectangle is out, perform these.
  $("#svg").click(function(){
    if (!$("#shadow").is(":visible")) return;
    if (selMode == 0) // insert mode
    {
      changeArrow();
      gatherStats(); // in parse
      updateStats();
    }
    else // select mode
    {
      selectRow();
    }
  });
  
  // Work on a new file, but make sure it's saved/validated recently.
  $("#but_new").click(function(){
    $("#intro").text("Working... Working...");
    var checking = true;
    if (isDirty)
    {
      checking = confirm("You have work not validated/saved.\nAre you sure you want to start over?");
    }
    if (checking) { init(); }
  });
  
  // Load a chart from your hard drive or your Pump Pro Edits Account.
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
  
  // Provide help for those that need it (TODO: Get this done.)
  $("#but_help").click(function(){
    $("#intro").text("Loading help...");
    window.open(baseURL + "/help", "helpWindow",
      "status = 1, scrollbars = yes, dependent = 1, width = 400, height = 400, left = 100, top = 100");
  });
  
  // Force all edits to be validated before saving/uploading.
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
  
  // The account holder wishes to load from the hard drive.
  $("#cho_file").click(function(){
    $("#fCont").val('');
    $(".loadChoose").hide();
    $(".loadFile").show();
    $("li.loadFile > *").removeAttr('disabled');
    $("#but_file").attr('disabled', true);
    $("#intro").text("You can load your edit now.");
  });
  
  // The account holder wishes to edit an account edit in place.
  $("#cho_site").click(function(){
    $(".loadChoose").hide();
    $(".loadSite").show();
    $("#intro").text("Loading your edits...");
    $("#mem_edit").empty();
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
  
  // The edit contents have to be placed in here due to AJAX requirements.
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
  
  // Load the edit from the...text area, not a pure file.
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
  
  // Load the account holder's specific edit.
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
  
  // The author decides not to load an account edit.
  $("#mem_nogo").click(function(){
    $("#fCont").val('');
    $(".loadSite").hide();
    $("li.edit").show();
  });
  
  // The author decides not to load an edit from the hard drive.
  $("#rem_file").click(function(){
    $("#fCont").val('');
    $(".loadFile").hide();
    $("li.edit").show();
  });
  
  // save to your local hard drive
  $("#but_save").click(function(){
    $("#intro").text("Here it comes!");
  });
  
  // The author uploads his edit directly to his account.
  $("#but_sub").click(function(){
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
  
  // The author wants to work on this song.
  $('#songlist').change(function(){
    songID = $("#songlist").val();
    if (songID.length > 0) { $("#stylelist").removeAttr("disabled"); }
    else { $("#stylelist").attr("disabled", "disabled"); }
  });

  // The author wants to work with this style.
  $("#stylelist").change(function(){
    style = $("#stylelist").val();
    editMode();
    $("#intro").text("Have fun editing!");
  });
  
  // The author wishes to change the syncing and note type.
  $("#quanlist").change(function() { sync = $("#quanlist").val();});
  $("#typelist").change(function() { note = $("#typelist").val();});
  
  // The author wishes to change the edit title / name.
  $("#editName").keyup(function(){
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

  // The author wishes to rate the edit.
  $("#editDiff").keyup(function(){
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
  
  // The author wishes to change how zoomed in the chart is.
  $("#scalelist").change(function(){
    fixScale($("#scalelist").val());
  });

  // The author wishes to change the cursor mode to select rows of arrows.
  $("#modelist").change(function(){
    selMode = $("#modelist").val();
    swapCursor();
  });

  // The author wishes to change which player's routine steps to place.
  $("#playerlist").change(function(){
    player = $("#playerlist").val();
  });

  $("input").focusin(function(){ captured = true; });
  $("select").focusin(function(){ captured = true; });
  $('input').focusout(function(){ captured = false; });
  $('select').focusout(function(){ captured = false; });

  // Keyboard shortcuts.
  $("html").keydown(function(e){
    if (captured) { return; }
    switch (e.which)
    {
      // 1
      case 49: { sync = 4; $("#quanlist").val(4); break; }
      // 2
      case 50: { sync = 8; $("#quanlist").val(8); break; }
      // 3
      case 51: { sync = 12; $("#quanlist").val(12); break; }
      // 4
      case 52: { sync = 16; $("#quanlist").val(16); break; }
      // 5
      case 53: { sync = 24; $("#quanlist").val(24); break; }
      // 6
      case 54: { sync = 32; $("#quanlist").val(32); break; }
      // 7
      case 55: { sync = 48; $("#quanlist").val(48); break; }
      // 8
      case 56: { sync = 64; $("#quanlist").val(64); break; }
      // 9
      case 57: { sync = 192; $("#quanlist").val(192); break; }
      
      // T
      case 84: { note = "1"; $("#typelist").val("1"); break; }
      // H
      case 72: { note = "2"; $("#typelist").val("2"); break; }
      // E
      case 69: { note = "3"; $("#typelist").val("3"); break; }
      // R
      case 82: { if (!e.ctrlKey) { note = "4"; $("#typelist").val("4"); } break; }
      // M
      case 77: { note = "M"; $("#typelist").val("M"); break; }
      // L
      case 76: { note = "L"; $("#typelist").val("L"); break; }
      // F
      case 70: { note = "F"; $("#typelist").val("F"); break; }
      
      // + or =
      case 61: {
        var tmp = $("#scalelist > option:selected").next().val();
        if (!isEmpty(tmp))
        {
          fixScale(tmp);
          $("#scalelist").val(tmp);
        }
        break;
      }
      // - or _
      case 109: {
        var tmp = $("#scalelist > option:selected").prev().val();
        if (!isEmpty(tmp))
        {
          fixScale(tmp);
          $("#scalelist").val(tmp);
        }
        break;
      }
      
      // O
      case 79:
      {
        selMode = (selMode ? 0 : 1);
        $("#modelist").val(selMode);
        swapCursor();
        break;
      }
      
      // P
      case 80: {
        if (style === "routine")
        {
          player = (player ? 0 : 1);
          $("#playerlist").val(player);
        }
        break;
      }
    }
  });
});
