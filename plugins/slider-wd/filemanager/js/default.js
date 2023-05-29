/**
 * Author: Rob
 * Date: 4/18/13
 * Time: 3:56 PM
 */

var keyFileSelected;
var keyFileSelectedML;
var filesSelected;
var dragFiles;
var isUploading;

var ajax = true;

var all_files_selected = false;
var no_selected_files = [];

jQuery(document).ready(function () {
  var elements = 2;
  var all_item_count = jQuery("#explorer_body_container #explorer_body").data("files_count");
  jQuery("#explorer_body_container").scroll(function () {
    var explorer_item_count = jQuery("#explorer_body .explorer_item").length;
    if (ajax && explorer_item_count < all_item_count) {
      var scroll = jQuery(this).scrollTop();
      var scroll_position = jQuery(this).scrollTop() + jQuery(this).innerHeight();
      var scroll_Height = jQuery(this)[0].scrollHeight;
      if (scroll_position >= scroll_Height) {
        var start_count = (elements-1)*element_load_count;
        var end_count = elements*element_load_count;
        var next_files = wdb_all_files.slice(start_count, end_count);
        ajax_print_images(next_files, jQuery("#explorer_body"), 'explorer_item', start_count);
        elements++;
      }
    }
  });

  var all_images_count = jQuery("#file_manager .item_thumb img").length;
  if (!all_images_count) {
    setTimeout(function(){jQuery(document).trigger("onUpload")});
  }
  else {
    setTimeout(function(){jQuery(document).trigger("onSelectAllImage")});
  }
  if (all_images_count == 0 || all_images_count <= 24) {
    loaded();
  }
  setTimeout(function(){loaded()}, 10000);
  filesSelected = [];
  dragFiles = [];

  jQuery("#wrapper").css("top", jQuery("#file_manager_message").css("height"));
  jQuery(window).resize(function () {
    jQuery("#container").css("top", jQuery("#file_manager_message").css("height"));
  });

  isUploading = false;
  jQuery("#uploader").css("display", "none");
  jQuery("#uploader_progress_bar").css("display", "none");
  jQuery("#importer").css("display", "none");

  //decrease explorer header width by scroller width
  jQuery(".scrollbar_filler").css("width", getScrollBarWidth() + "px");
  jQuery(document).keydown(function(e) {
    onKeyDown(e);
  });
  jQuery("#search_by_name .search_by_name").on("input keyup", function() {
    var search_by_name = jQuery(this).val().toLowerCase();
    if (search_by_name) {
      jQuery("#explorer_body .explorer_item").hide();
      jQuery("#explorer_body .explorer_item").each(function () {
        var filename = jQuery(this).attr("filename").toLowerCase();
        if (filename.indexOf(search_by_name) != -1) {
          jQuery(this).show();
        }
      });
    }
    else {
      jQuery("#explorer_body .explorer_item").show();
    }
  });
});

function loaded() {
  jQuery("#opacity_div").hide();
  jQuery("#loading_div").hide();
}

function getClipboardFiles() {
  return jQuery("form[name=adminForm]").find("input[name=clipboard_file]").val();
}

function submit(task, sortBy, sortOrder, itemsView, destDir, fileNewName, newDirName, clipboardTask, clipboardFiles, clipboardSrc, clipboardDest) {
  var names_array = [];
  if (all_files_selected === true) {
    for (i in wdb_all_files) {
      var index = no_selected_files.indexOf(wdb_all_files[i]["name"]);
      if (index < 0) {
        var all_names = wdb_all_files[i]["name"];
        names_array.push(all_names);
      }
    }
    fileNames = names_array.join("**#**");
  }
  else {
    fileNames = filesSelected.join("**#**");
  }

  switch (task) {
    case "rename_item":
      destDir = dir;
      newDirName = "";
      clipboardTask = ""
      clipboardDest = "";
      break;
    case "remove_items":
      destDir = dir;
      fileNewName = "";
      newDirName = "";
      clipboardTask = ""
      clipboardDest = "";
      break;
    case "make_dir":
      destDir = dir;
      fileNewName = "";
      clipboardTask = ""
      clipboardDest = "";
      break;
    case "paste_items":
      destDir = dir;
      fileNewName = "";
      newDirName = "";
      break;
    case "import_items":
      destDir = dir;
      fileNewName = "";
      newDirName = "";
      break;
    default:
      task = "";
      break;

  }

  jQuery("form[name=adminForm]").find("input[name=task]").val(task);

  if (sortBy != null) {
    jQuery("form[name=adminForm]").find("input[name=sort_by]").val(sortBy);
  }
  if (sortOrder != null) {
    jQuery("form[name=adminForm]").find("input[name=sort_order]").val(sortOrder);
  }
  if (itemsView != null) {
    jQuery("form[name=adminForm]").find("input[name=items_view]").val(itemsView);
  }
  if (destDir != null) {
    jQuery("form[name=adminForm]").find("input[name=dir]").val(destDir);
  }
  if (fileNames != null) {
    jQuery("form[name=adminForm]").find("input[name=file_names]").val(fileNames);
  }
  if (fileNewName != null) {
    jQuery("form[name=adminForm]").find("input[name=file_new_name]").val(fileNewName);
  }
  if (newDirName != null) {
    jQuery("form[name=adminForm]").find("input[name=new_dir_name]").val(newDirName);
  }
  if (clipboardTask != null) {
    jQuery("form[name=adminForm]").find("input[name=clipboard_task]").val(clipboardTask);
  }
  if (clipboardFiles != null) {
    jQuery("form[name=adminForm]").find("input[name=clipboard_files]").val(clipboardFiles);
  }
  if (clipboardSrc != null) {
    jQuery("form[name=adminForm]").find("input[name=clipboard_src]").val(clipboardSrc);
  }
  if (clipboardDest != null) {
    jQuery("form[name=adminForm]").find("input[name=clipboard_dest]").val(clipboardDest);
  }
  jQuery("form[name=adminForm]").submit();
}

function updateFileNames() {
  var result = "";
  if (filesSelected.length > 0) {
    var fileNames = [];
    for (var i = 0; i < filesSelected.length; i++) {
      fileNames[i] = '"' + filesSelected[i] + '"'; 
    }
    result = fileNames.join(" ");
  }
  jQuery("#file_names_span span").html(result);
}

function submitFiles() {
  if (filesSelected.length == 0) {
    return;
  }
  if ((image_for != "add_slides") && (filesSelected.length > 1)) {
    alert('You cannot add multiple images at once. Please add images one by one or use "Add images" button.');
    return;
  }
  var filesValid = [];
  if (all_files_selected === true) {
    for (i in wdb_all_files) {
      var fileData = [];
      if (wdb_all_files[i]["is_dir"] === false) {
        var index = no_selected_files.indexOf(wdb_all_files[i]["name"]);
        if (index < 0) {
          fileData['name'] = wdb_all_files[i]["name"];
          fileData['filename'] = wdb_all_files[i]["filename"];;
          fileData['url'] = dirUrl + "/" + wdb_all_files[i]["name"];
          fileData['reliative_url'] = dirUrl + "/" + wdb_all_files[i]["name"];
          fileData['thumb_url'] = dirUrl + "/thumb/" + wdb_all_files[i]["name"];
          fileData['thumb'] = wdb_all_files[i]["thumb"];
          fileData['size'] = wdb_all_files[i]["size"];
          fileData['filetype'] = wdb_all_files[i]["type"];
          fileData['date_modified'] = wdb_all_files[i]["date_modified"];
          fileData['resolution'] = wdb_all_files[i]["resolution"];
          filesValid.push(fileData);
        }
      }
    }
  }
  else {
  for (var i = 0; i < filesSelected.length; i++) {
    var file_object = jQuery('.explorer_item[name="' + filesSelected[i] + '"]');
    if (jQuery(file_object).attr("isDir") == "false") {
      var fileData = [];
      fileData['name'] = filesSelected[i];
      fileData['filename'] = jQuery(file_object).attr("filename");
      fileData['url'] = dirUrl + "/" + filesSelected[i];
      fileData['reliative_url'] = dirUrl + "/" + filesSelected[i];
      fileData['thumb_url'] = dirUrl + "/thumb/" + filesSelected[i];
      fileData['thumb'] = jQuery(file_object).attr("filethumb");
      fileData['size'] = jQuery(file_object).attr("filesize");
      fileData['filetype'] = jQuery(file_object).attr("filetype");
      fileData['date_modified'] = jQuery(file_object).attr("date_modified");
      fileData['resolution'] = jQuery(file_object).attr("fileresolution");
        filesValid.push(fileData);
      }
    }
  }
  window.parent[callback](filesValid, image_for, slide_id, layer_id);
  window.parent.tb_remove();
}

function getScrollBarWidth() {
  var inner = document.createElement("p");
  inner.style.width = "100%";
  inner.style.height = "200px";

  var outer = document.createElement("div");
  outer.style.position = "absolute";
  outer.style.top = "0px";
  outer.style.left = "0px";
  outer.style.visibility = "hidden";
  outer.style.width = "200px";
  outer.style.height = "150px";
  outer.style.overflow = "hidden";
  outer.appendChild(inner);

  document.body.appendChild(outer);
  var w1 = inner.offsetWidth;
  outer.style.overflow = "scroll";
  var w2 = inner.offsetWidth;
  if (w1 == w2) {
    w2 = outer.clientWidth;
  }
  document.body.removeChild(outer);

  return (w1 - w2);
}

function getFileName(file) {
  var dotIndex = file.lastIndexOf('.');
  return file.substring(0, dotIndex < 0 ? file.length : dotIndex);
}

function getFileExtension(file) {
  return file.substring(file.lastIndexOf('.') + 1);
}


//ctrls bar handlers
function onBtnUpClick(event, obj) {
  var destDir = dir.substring(0, dir.lastIndexOf(DS));
  submit("", null, null, null, destDir, null, null, null, null, null, null);
}

function onBtnMakeDirClick(event, obj) {
  var newDirName = prompt(messageEnterDirName);
  if ((newDirName) && (newDirName != "")) {
    submit("make_dir", null, null, null, null, null, newDirName.replace(/ /g, "_"), null, null, null, null);
  }
}

function onBtnRenameItemClick(event, obj) {
  if (filesSelected.length != 0) {
    var newName = prompt(messageEnterNewName, getFileName(filesSelected[0]));
    if ((newName != null) && (newName != "")) {
      submit("rename_item", null, null, null, null, newName.replace(/ /g, "_").replace(/%/g, ""), null, null, null, null, null);
    }
  }
}

function onBtnCopyClick(event, obj) {
  if (filesSelected.length != 0) {
    var names_list =  filesSelected.join("**#**");
    var names_array = [];
    if (all_files_selected === true) {
      for (i in wdb_all_files) {
        var index = no_selected_files.indexOf(wdb_all_files[i]["name"]);
        if (index < 0) {
          var all_names = wdb_all_files[i]["name"];
          names_array.push(all_names);
        }
      }
      names_list =  names_array.join("**#**");
    }

    submit("", null, null, null, null, null, null, "copy", names_list, dir, null);
  }
}

function onBtnCutClick(event, obj) {
  if (filesSelected.length != 0) {
    var names_list =  filesSelected.join("**#**");
    var names_array = [];
    if (all_files_selected === true) {
      for (i in wdb_all_files) {
        var index = no_selected_files.indexOf(wdb_all_files[i]["name"]);
        if (index < 0) {
          var all_names = wdb_all_files[i]["name"];
          names_array.push(all_names);
        }
      }
      names_list =  names_array.join("**#**");
    }
    submit("", null, null, null, null, null, null, "cut", names_list, dir, null);
  }
}

function onBtnPasteClick(event, obj) {
  if (getClipboardFiles() != "") {
    submit("paste_items", null, null, null, null, null, null, null, null, null, dir);
  }
}

function onBtnRemoveItemsClick(event, obj) {
  if ((filesSelected.length != 0) && (confirm(warningRemoveItems) == true)) {
    submit("remove_items", null, null, null, null, null, null, null, null, null, null);
  }
}

function onBtnShowUploaderClick(event, obj) {
  jQuery(document).trigger("onUploadFilesPressed");
  jQuery("#uploader").fadeIn();
}

function onBtnViewThumbsClick(event, obj) {
  submit("", null, null, "thumbs", null, null, null, null, null, null, null);
}

function onBtnViewListClick(event, obj) {
  submit("", null, null, "list", null, null, null, null, null, null, null);
}

function onBtnBackClick(event, obj) {
  if ((isUploading == false) || (confirm(warningCancelUploads) == true)) {
    // jQuery("#uploader").fadeOut(function () {
      submit("", null, null, null, null, null, null, null, null, null, null);
    // });
  }
}


function onPathComponentClick(event, obj, key) {
  if (typeof key != "undefined" && key == 0) {
    path = "";
  }
  else {
    path = jQuery(obj).html();
    path = path.trim();
  }
  submit("", null, null, null, path, null, null, null, null, null, null);
}

function onBtnShowImportClick(event, obj) {
  jQuery("#importer").fadeIn();
}

function onNameHeaderClick(event, obj) {
  var newSortOrder = ((sortBy == "name") && (sortOrder == "asc")) ? "desc" : "asc";
  submit("", "name", newSortOrder, null, null, null, null, null, null, null, null);
}

function onSizeHeaderClick(event, obj) {
  var newSortOrder = ((sortBy == "size") && (sortOrder == "asc")) ? "desc" : "asc";
  submit("", "size", newSortOrder, null, null, null, null, null, null, null, null);
}

function onDateModifiedHeaderClick(event, obj) {
  var newSortOrder = ((sortBy == "date_modified") && (sortOrder == "asc")) ? "desc" : "asc";
  submit("", "date_modified", newSortOrder, null, null, null, null, null, null, null, null);
}


//file handlers
function onKeyDown(e) {
  var e = e || window.event;
  var chCode1 = e.which || e.paramlist_keyCode;
  if ((e.ctrlKey || e.metaKey) && chCode1 == 65) {
    all_files_selected = true;
    jQuery(".explorer_item").addClass("explorer_item_select");
    jQuery(".importer_item").addClass("importer_item_select");
    filesSelected = [];
    jQuery(".explorer_item").each(function() {
      var objName = jQuery(this).attr("name");
      if (filesSelected.indexOf(objName) == -1) {
        filesSelected.push(objName);
        keyFileSelected = this;
      }
    });
    e.preventDefault();
  }
}

function onFileMOver(event, obj) {
  jQuery(obj).addClass("explorer_item_hover");
}

function onFileMOut(event, obj) {
  jQuery(obj).removeClass("explorer_item_hover");
}

function onFileClick(event, obj) {
  jQuery(".explorer_item").removeClass("explorer_item_select");
  var objName = jQuery(obj).attr("name");
  if (event.ctrlKey == true || event.metaKey == true) {
    if (all_files_selected === true) {
      if (filesSelected.indexOf(objName) == -1) {
        var index = no_selected_files.indexOf(objName);
        if (index >= 0) {
          no_selected_files.splice( index, 1 );
        }
      }
      else {
        no_selected_files.push(objName);
      }
    }
    if (filesSelected.indexOf(objName) == -1) {
      filesSelected.push(objName);
      keyFileSelected = obj;
    }
    else {
      filesSelected.splice(filesSelected.indexOf(objName), 1);
      jQuery(obj).removeClass("explorer_item_select");
    }
  }
  else if (event.shiftKey == true) {
    filesSelected = [];
    var explorerItems = jQuery(".explorer_item");
    var curFileIndex = explorerItems.index(jQuery(obj));
    var keyFileIndex = explorerItems.index(keyFileSelected);
    var startIndex = Math.min(keyFileIndex, curFileIndex);
    var endIndex = startIndex + Math.abs(curFileIndex - keyFileIndex);
    for (var i = startIndex; i < endIndex + 1; i++) {
      filesSelected.push(jQuery(explorerItems[i]).attr("name"));
    }
  }
  else {
    filesSelected = [jQuery(obj).attr("name")];
    keyFileSelected = obj;
  }

  for (var i = 0; i < filesSelected.length; i++) {
    jQuery('.explorer_item[name="' + filesSelected[i] + '"]').addClass("explorer_item_select");
  }
  updateFileNames();
}

function onFileDblClick(event, obj) {
  if (jQuery(obj).attr("isDir") == "true") {
    submit("", null, null, null, dir + DS + jQuery(obj).attr("name"), null, null, null, null, null, null);
  }
  else {
    filesSelected = [];
    filesSelected.push(jQuery(obj).attr("name"));
    submitFiles();
  }
}

function onFileDragStart(event, obj) {
  var objName = jQuery(obj).attr("name");
  if (filesSelected.indexOf(objName) < 0) {
    jQuery(".explorer_item").removeClass("explorer_item_select");
    if (event.ctrlKey == true || event.metaKey == true) {
      if (filesSelected.indexOf(objName) == -1) {
        filesSelected.push(objName);
        keyFileSelected = obj;
      }
    }
    else if (event.shiftKey == true) {
      filesSelected = [];
      var explorerItems = jQuery(".explorer_item");
      var curFileIndex = explorerItems.index(jQuery(obj));
      var keyFileIndex = explorerItems.index(keyFileSelected);
      var startIndex = Math.min(keyFileIndex, curFileIndex);
      var endIndex = startIndex + Math.abs(curFileIndex - keyFileIndex);
      for (var i = startIndex; i < endIndex + 1; i++) {
        filesSelected.push(jQuery(explorerItems[i]).attr("name"));
      }
    }
    else {
      filesSelected = [jQuery(obj).attr("name")];
      keyFileSelected = obj;
    }

    for (var i = 0; i < filesSelected.length; i++) {
      jQuery('.explorer_item[name="' + filesSelected[i] + '"]').addClass("explorer_item_select");
    }

    updateFileNames();
  }
  dragFiles = filesSelected;
}

function onFileDragOver(event, obj) {
  event.preventDefault();
}

function onFileDrop(event, obj) {
  var destDirName = jQuery(obj).attr("name");
  if ((dragFiles.length == 0) || (dragFiles.indexOf(destDirName) >= 0)) {
    return false;
  }
  var clipboardTask = (event.ctrlKey == true || event.metaKey == true) ? "copy" : "cut";
  var clipboardDest = dir + DS + destDirName;
  submit("paste_items", null, null, null, null, null, null, clipboardTask, dragFiles.join("**#**"), dir, clipboardDest);
  event.preventDefault();
}

function onBtnOpenClick(event, obj) {
  if (jQuery('.explorer_item[name="' + filesSelected[0] + '"]').attr("isDir") == true) {
    filesSelected.length = 1;
    submit("", null, null, null, dir + DS + filesSelected[0], null, null, null, null, null, null);
  }
  else {
    submitFiles();
  }
}

function onBtnCancelClick(event, obj) {
  window.parent.tb_remove();
}

function onBtnSelectAllClick() {
  jQuery(".explorer_item").removeClass("explorer_item_select");
  jQuery(".explorer_item:visible").addClass("explorer_item_select");
  filesSelected = [];
  jQuery(".explorer_item:visible").each(function() {
    var objName = jQuery(this).attr("name");
    if (filesSelected.indexOf(objName) == -1) {
      filesSelected.push(objName);
      keyFileSelected = this;
    }
  });
  all_files_selected = true;
}

function ajax_print_images(files, element, view_type, count) {
  for (i in files) {
    var corent_file = files[i];
    var name = corent_file["name"];
    var filename = corent_file["filename"];
    var filethumb = corent_file["thumb"];
    var filesize = corent_file["size"];
    var filetype = corent_file["type"];
    var date_modified = corent_file["date_modified"];
    var fileresolution = corent_file["resolution"];
    var fileCredit = corent_file["credit"];
    var fileAperture = corent_file["aperture"];
    var fileCamera = corent_file["camera"];
    var fileCaption = corent_file["caption"];
    var fileIso = corent_file["iso"];
    var fileOrientation = corent_file["orientation"];
    var fileCopyright = corent_file["copyright"];
    var onmouseover = "onFileMOver(event, this);";
    var onmouseout = "onFileMOut(event, this);";
    var onclick = "onFileClick(event, this);";
    var ondblclick = "onFileDblClick(event, this);";
    var ondragstart = "onFileDragStart(event, this);";
    var ondragover = "";
    var ondrop = "";
    if (corent_file['is_dir'] == true) {
      ondragover = "onFileDragOver(event, this);";
      ondrop = "onFileDrop(event, this);";
    }
    var isDir = false;
    if (corent_file['is_dir'] === true) {
      isDir = 'true';
    }

    var item_number = count;
    count++;
    var item_thumb = '<span class="item_thumb"><img src="' + corent_file['thumb'] + '"/></span>';
    var item_icon = '<span class="item_icon"><img src="'+corent_file['icon']+'"/> </span>';
    var item_name = '<span class="item_name">'+corent_file['name']+'</span>';
    var item_size = '<span class="item_size">'+corent_file['size']+'</span>';
    var item_date_modified = '<span class="item_date_modified">'+corent_file['date_modified']+'</span>';
    var item_numbering =  '<span class="item_numbering">'+item_number+'</span>';
    var explorer_item = '<div class="explorer_item" ' +
      'name="' + name + '" ' +
      'filename="' + filename + '" ' +
      'filethumb="' + filethumb + '" ' +
      'filesize="' + filesize + '" ' +
      'filetype="' + filetype + '" ' +
      'date_modified="' + date_modified + '" ' +
      'fileresolution="' + fileresolution + '" ' +
      'fileCredit="' + fileCredit + '" ' +
      'fileAperture="' + fileAperture + '" ' +
      'fileCamera="' + fileCamera + '" ' +
      'fileCaption="' + fileCaption + '" ' +
      'fileIso="' + fileIso + '" ' +
      'fileOrientation="' + fileOrientation + '" ' +
      'fileCopyright="' + fileCopyright + '" ' +
      'isDir="' + isDir + '" ' +
      'onmouseover="' + onmouseover + '" ' +
      'onmouseout="' + onmouseout + '" ' +
      'onclick="' + onclick + '" ' +
      'ondblclick="' + ondblclick + '" ' +
      'ondragstart="' + ondragstart + '" ' +
      'ondragover="' + ondragover + '" ' +
      'ondrop="' + ondrop + '" ' +
      'draggable="true">'+item_numbering + item_thumb + item_icon+item_name+item_size+item_date_modified+'</div>';
    element.append(explorer_item);
  }
}