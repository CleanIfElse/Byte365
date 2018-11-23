function doRegister()
{
	$.ajax({
	    	type:"GET",
		dataType: "json",
	    	url: "https://byte365.net/api/?action=doRegister&email=" + document.getElementById('email').value + "&first=" +  document.getElementById('first').value + "&last=" + document.getElementById('last').value + "&pass=" + document.getElementById('pass').value,
	    	success: function(data) {
	            alert(data.message);
	        },
	    	error: function(jqXHR, textStatus, errorThrown) {

	        },
	});
}

function addStyle()
{
	
}

function showShareLink(link)
{
	$('#share-link').text(link);
	$('#modal2').modal('open');
}

function getDir()
{
	$.ajax({
	    	type:"GET",
		dataType: "text",
	    	url: "https://byte365.net/api.php?currentDir",
	    	success: function(data) {
			document.getElementById("currentDir").text(data);
	        },
	    	error: function(jqXHR, textStatus, errorThrown) {

		}
	});
}

function deleteFile(shareKey, encryptionKey, id)
{
	$.ajax({
	    	type:"GET",
		dataType: "text",
	    	url: "https://byte365.net/dashboard/delete.php?k=" + shareKey + "&e=" + encryptionKey,
	    	success: function(data) {
			console.log("https://byte365.net/dashboard/delete.php?k=" + shareKey + "&e=" + encryptionKey);
			document.getElementById("file-" + id).remove();
	        },
	    	error: function(jqXHR, textStatus, errorThrown) {

		}
	});
}

function fileBrowser(dir)
{
	$.ajax({
	    	type:"GET",
		dataType: "text",
	    	url: "https://byte365.net/api/index.php?fileBrowser=" + dir,
	    	success: function(data) {
			$("#fileBrowser").html(data);

			$.ajax({
				type:"GET",
				dataType: "text",
				url: "https://byte365.net/api/?currentDir",
				success: function(data) {
					$("#f-folder-name").val(data);
				  },
				error: function(jqXHR, textStatus, errorThrown) {

				  },
			});
	        },
	    	error: function(jqXHR, textStatus, errorThrown) {

		}
	});
}

function downloadFile(shareKey, encryptionKey)
{
	document.getElementById('download').src = "https://byte365.net/share/?k=" + shareKey + "&e=" + encryptionKey;
}

$.notify.addStyle('upload-error', {
  html: "<div><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> &nbsp;<span data-notify-text/></div>",
  classes: {
    base: {
      "white-space": "nowrap",
      "background-color": "#b10101",
      "padding": "12px"
    },
    errorbase: {
      "color": "white",
      "background-color": "#b10101"
    }
  }
});

$.notify.addStyle('upload-success', {
  html: "<div><i class=\"fa fa-thumbs-up\" aria-hidden=\"true\"></i> &nbsp;<span data-notify-text/></div>",
  classes: {
    base: {
      "white-space": "nowrap",
      "background-color": "#00a25d",
      "padding": "12px"
    },
    successbase: {
      "color": "white",
      "background-color": "#00a25d"
    }
  }
});

function GetAlerts()
{
	 $.ajax({
	    	type:"GET",
		dataType: "json",
	    	url: "https://byte365.net/alerts.php",
	    	success: function(data) {
			if (data["type"] == "error")
			{
				$.notify(data.text, {
				style: 'upload-error',
				className: 'errorbase',
				});
			}
			else
			{
				$.notify(data.text, {
				style: 'upload-success',
				className: 'successbase',
				});
			}
	        },
	    	error: function(jqXHR, textStatus, errorThrown) {

		}
	});
}

    $(window).resize(function() {
        moveProgressBar();
    });

    // SIGNATURE PROGRESS
    function moveProgressBar() {
        var getPercent = ($('.progress-wrap').data('progress-percent') / 100);
        var getProgressWrapWidth = $('.progress-wrap').width();
        var progressTotal = getPercent * getProgressWrapWidth;
        var animationLength = 2500;

        // on page load, animate percentage bar to data percentage length
        // .stop() used to prevent animation queueing
        $('.progress-bar').stop().animate({
            left: progressTotal
        }, animationLength);
    }

    Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}
