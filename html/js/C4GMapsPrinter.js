window.onload = function() {
	if(window.opener && window.opener.c4gMapsRouter)
		window.opener.c4gMapsRouter.printWindowLoaded();
};

//print the window
function printWindow() {
	window.print();
}
