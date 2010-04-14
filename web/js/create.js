/*
 * This file deals strictly with setting up the javascript
 * actions that will be called (mostly) in create_event.js.
 */
$(document).ready(function()
{
  init();
  
  $("#songlist").val('');
  $("rect[id^=sel]").attr('x', BUFF_LFT);
  $("#notes").attr('transform', 'scale(' + SCALE + ')');
  
  // Don't show the rectangle when not in play.
  $("#svg").mouseout(function(){ hideRect(); });
  // Show the rectangle if the mouse is over a measure.
  $("#svg").mouseover(function(e){ checkShadow(e); });
  $("#svg").mousemove(function(e){ checkShadow(e); });
  // If the shadow rectangle is out, perform these.
  $("#svg").click(function(){
    if (navigator.userAgent.indexOf("WebKit") >= 0)
    {
      if (Math.floor($("#shadow").attr('x')) <= 0) return;
    }
    else if (!$("#shadow").is(":visible")) return;
    if (selMode == 0) // insert mode
    {
      changeArrow();
      updateStats(gatherStats());
    }
    else if (clipboard) // paste mode
    {
      pasteArrows();
      $("#intro").text("Arrows pasted. Clipboard wiped.");
      updateStats(gatherStats());
      
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
      function loadButtons()
      {
        $("li.edit").hide();
        if (authed > 0)
        {
          $("li.loadChoose").show();
          $("li[class^=load]:not(.loadChoose)").hide();
          $("#intro").text("Computer or account?");
        }
        else { loadHardDrive(); }
      }
      
      if ($("#stylelist").val().length)
      {
        loadButtons();
      }
      else
      {
        $(".choose").slideUp(200, function(){ loadButtons(); });
      }
    }
  });
  
  // Provide help for those that need it.
  $("#but_help").click(function(){
    $("#intro").text("Loading help...");
    window.open(baseURL + "/help", "helpWindow",
      "status = 1, scrollbars = yes, dependent = 1, width = 400, height = 400, left = 100, top = 100");
    $("#intro").text("Help loaded!");
  });
  
  // Force all edits to be validated before saving/uploading.
  $("#but_val").click(function(){
    var data = gatherStats(1);
    if (!data.badds.length)
    {
      saveChart(data);
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
      for (var i = 0; i < data.badds.length; i++)
      {
        ouch += "Player " + data.badds[i]['player'] + " Measure " + data.badds[i]['measure']
          + " Beat " + data.badds[i]['beat'] + " Column " + data.badds[i]['note'] + "\n";
      }
      alert(ouch);
    }
  });
  
  // The account holder wishes to load from the hard drive.
  $("#cho_file").click(function(){ loadHardDrive(); });  
  // The account holder wishes to edit an account edit in place.
  $("#cho_site").click(function(){
    $(".loadChoose").hide();
    if (andamiro > 0) { $(".loadWeb").show(); }
    else              { loadWebEdits(authed); }
  });
  // The account holder wishes to edit one of his account edits.
  $("#web_you").click(function(){
    $(".loadWeb").hide();
    authID = authed;
    loadWebEdits(authID);
  });
  // The account holder wishes to edit one of Andamiro's account edits.
  $("#web_and").click(function(){
    $(".loadWeb").hide();
    authID = 2;
    loadWebEdits(authID);
  });
  
  // The edit contents have to be placed in here due to AJAX requirements.
  $("#fCont").keyup(function(){
    if ($("#fCont").val().length)
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
    $("#intro").text("Loading edit...");
    $.post(baseURL + "/loadFile", { file: $("#fCont").val()}, function(data, status)
    {
      loadEdit(data);
      $("#intro").text("All loaded up!");
      $("#but_save").removeAttr('disabled');
      $("#but_val").attr('disabled', 'disabled');
      if (andamiro) { $(".author").show(); $("#authorlist").removeAttr('disabled'); }
      else          { $(".author").hide(); $("#authorlist").attr("disabled", true); }
      isDirty = false;
    }, "json");
  });
  
  // Load the account holder's specific edit.
  $("#mem_load").click(function(){
    $("#intro").text("Loading edit...");
    editID = $("#mem_edit > option:selected").attr('id');
    $.getJSON(baseURL + "/loadSiteEdit/" + editID, function(data) {
      loadEdit(data);
      $("#intro").text("All loaded up!");
      $("#authorlist").attr("disabled", "disabled");
      $(".author").hide();
      if (data.title.length)
      {
        $("#editName").attr('disabled', true);
      }
      isDirty = false;
    });
  });
  
  // The author decides not to load an edit at all.
  $("#mem_nogo").click(function(){ cancelLoad(); });
  $("#rem_file").click(function(){ cancelLoad(); });
  
  // save to your local hard drive
  $("#but_save").click(function(){
    $("#intro").text("Here it comes!");
  });
  
  // The author uploads the edit directly to the chosen account.
  $("#but_sub").click(function(){
    var data = {};
    data['b64'] = $("#b64").val();
    data['title'] = $("#editName").val();
    data['diff'] = $("#editDiff").val();
    data['style'] = $("#stylelist").val();
    data['editID'] = editID;
    data['songID'] = songID;
    data['userID'] = authID;
    data['steps1'] = $("#statS").text().split("/")[0];
    data['steps2'] = $("#statS").text().split("/")[1];
    data['jumps1'] = $("#statJ").text().split("/")[0];
    data['jumps2'] = $("#statJ").text().split("/")[1];
    data['holds1'] = $("#statH").text().split("/")[0];
    data['holds2'] = $("#statH").text().split("/")[1];
    data['mines1'] = $("#statM").text().split("/")[0];
    data['mines2'] = $("#statM").text().split("/")[1];
    data['rolls1'] = $("#statR").text().split("/")[0];
    data['rolls2'] = $("#statR").text().split("/")[1];
    data['trips1'] = $("#statT").text().split("/")[0];
    data['trips2'] = $("#statT").text().split("/")[1];
    data['fakes1'] = $("#statF").text().split("/")[0];
    data['fakes2'] = $("#statF").text().split("/")[1];
    data['lifts1'] = $("#statL").text().split("/")[0];
    data['lifts2'] = $("#statL").text().split("/")[1];
    
    $("#intro").text("Uploading edit...");
    $.post(baseURL + "/upload", data, function(data, status)
    {
      $("#intro").text("Edit Uploaded");
      $("#editName").attr("disabled", "disabled");
      $("#authorlist").attr("disabled", "disabled");
      $(".author").hide();
    }, "json");
  });
  
  // The author wants to work on this song.
  $('#songlist').change(function(){
    songID = $("#songlist").val();
    if (songID.length > 0)
    {
      $("#intro").text("Setting up styles...")
      $.getJSON(baseURL + "/routine/" + songID, function(data, status)
      {
        $("#stylelist").removeAttr("disabled");
        if (data.isRoutine > 0) { $("#stylelist > option:last-child").show(); }
        else                    { $("#stylelist > option:last-child").hide(); }
        $("#intro").text("What style for today?");
      });
    }
    else { $("#stylelist").attr("disabled", "disabled"); }
  });

  // The author wants to work with this style.
  $("#stylelist").change(function(){
    editMode();
    $("#intro").text("Have fun editing!");
  });
  
  // The author wishes to indicate whose work this really is.
  $("#authorlist").change(function(){
    var val = $("#authorlist").val();
    authID = (val == 0 ? authed : 2);
  });
  
  // The author wishes to change the edit title / name.
  $("#editName").keyup(function(){
    $("#but_save").attr('disabled', true);
    $("#but_sub").attr('disabled', true);
    var t = $("#editName").val().length;
    if (t > 0 && t <= 12)
    {
      if (Math.floor($("#editDiff").val()) > 0)
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
      t = $("#editName").val().length;
      if (t > 0 && t <= 12)
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
      case 49: { $("#quanlist").val(4); break; }
      // 2
      case 50: { $("#quanlist").val(8); break; }
      // 3
      case 51: { $("#quanlist").val(12); break; }
      // 4
      case 52: { $("#quanlist").val(16); break; }
      // 5
      case 53: { $("#quanlist").val(24); break; }
      // 6
      case 54: { $("#quanlist").val(32); break; }
      // 7
      case 55: { $("#quanlist").val(48); break; }
      // 8
      case 56: { $("#quanlist").val(64); break; }
      // 9
      case 57: { $("#quanlist").val(192); break; }
      
      // T
      case 84: { $("#typelist").val("1"); break; }
      // H
      case 72: { $("#typelist").val("2"); break; }
      // E
      case 69: { $("#typelist").val("3"); break; }
      // R
      case 82: { if (!e.ctrlKey) { $("#typelist").val("4"); } break; }
      // M
      case 77: { $("#typelist").val("M"); break; }
      // L
      case 76: { $("#typelist").val("L"); break; }
      // F
      case 70: { $("#typelist").val("F"); break; }
      
      // A
      case 65: {
        if ($("#selTop").attr('style').indexOf('none') == -1)
        {
          rotateColumn(-1); // rotate left.
        }
        break;
      }
      // D
      case 68: {
        if ($("#selTop").attr('style').indexOf('none') == -1)
        {
          rotateColumn(1); // rotate right.
        }
        break;
      }
      // W
      case 87: {
        if ($("#selTop").attr('style').indexOf('none') == -1)
        {
          shiftUp();
          updateStats(gatherStats());
        }
        break;
      }
      // S
      case 83: {
        if ($("#selTop").attr('style').indexOf('none') == -1)
        {
          shiftDown();
          updateStats(gatherStats());
        }
        break;
      }
      // I
      case 73: {
        if ($("#selTop").attr('style').indexOf('none') == -1)
        {
          mirrorRows();
        }
        break;
      }
      // X
      case 88: {
        if ($("#selTop").attr('style').indexOf('none') == -1)
        {
          cutArrows();
          $("#intro").text("Click a row to paste the notes, or swap cursor mode to delete.");
          updateStats(gatherStats());
        }
        break;
      }
      // C
      case 67: {
        if ($("#selTop").attr('style').indexOf('none') == -1)
        {
          copyArrows();
          $("#intro").text("Click a row to paste the notes, or swap cursor mode to cancel.");
        }
        break;
      }
      // V
      case 86: {
        if (clipboard && Math.floor($("#shadow").attr('x')) >= BUFF_LFT)
        {
          pasteArrows();
          updateStats(gatherStats());
          $("#intro").text("Arrows pasted. Clipboard wiped.");
        }
        break;
      }
      
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
        if ($("#stylelist").val() === "routine")
        {
          $("#playerlist").val(parseInt($("#playerlist").val()) ? 0 : 1);
        }
        break;
      }
    }
  });
});
