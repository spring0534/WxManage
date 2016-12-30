 function delpic(fileid){
	$("#"+fileid).fadeOut();
	swfu.cancelUpload(fileid,false);
	}
function preLoad() {
	if (!this.support.loading) {
		alert("You need the Flash Player 9.028 or above to use SWFUpload.");
		return false;
	}
}
function loadFailed() {
	alert("Something went wrong while loading SWFUpload. If this were a real application we'd clean up and then give you an alternative");
}
function fileQueued(file) {
	try {
		$(".upcon").append('<dl id="'+file.id+'"><ul class="img"> <li class="t">等待中</li><li class="ps">0%</li><li class="line"><span></span></li><li class="float" title="取消上传" onclick="delpic(\''+file.id+'\')"></li></ul><ul class="f">'+file.name+'</ul></dl>');

	} catch (ex) {
		this.debug(ex);
	}

}



function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		}

		
	} catch (ex) {
        this.debug(ex);
    }
}

function uploadStart(file) {

	
	try {
		$("#"+file.id+" ul.img li.t").html("上传中");
		$("#"+file.id+" ul.img li.float").hide();
	}
	catch (ex) {}
	
	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
$("#"+file.id+" ul.img li.ps").html(percent+"%");
		$("#"+file.id+" ul.img li.line span").css("width",percent+"%");
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData,responseReceived) {
	try {
		
		$("#"+file.id+" ul.img").html(serverData);
        swfu.startUpload();
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	try {
		

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			$("#"+file.id+" ul.img li.t").html("Upload Error: " + message);
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			$("#"+file.id+" ul.img li.t").html("Upload Failed.");
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			$("#"+file.id+" ul.img li.t").html("Server (IO) Error");
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			$("#"+file.id+" ul.img li.t").html("Security Error");
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			$("#"+file.id+" ul.img li.t").html("Upload limit exceeded.");
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			$("#"+file.id+" ul.img li.t").html("Failed Validation.  Upload skipped.");
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			$("#"+file.id+" ul.img li.t").html("Cancelled");
			progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			$("#"+file.id+" ul.img li.t").html("Stopped");
			break;
		default:
			$("#"+file.id+" ul.img li.t").html("Unhandled Error: " + errorCode);
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

// This event comes from the Queue Plugin
function queueComplete(numFilesUploaded) {
	//var status = document.getElementById("divStatus");
	//status.innerHTML = numFilesUploaded + " file" + (numFilesUploaded === 1 ? "" : "s") + " uploaded.";
}

function upfinish(){
	sfsdf=swfu.getStats();
	$("#upcount").html(sfsdf.successful_uploads);
	
}
function upload_start_function(){
	if($('.img').length<=0){
		alert('请先选择上传图片！');return;
	}
}
function upload_complete_function(){
	
}
function uploadComplete(){
	
}




